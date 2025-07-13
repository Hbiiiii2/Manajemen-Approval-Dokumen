<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\DocumentFile;
use App\Models\DocumentComment;
use Illuminate\Support\Facades\Auth;
use App\Models\DocumentType;
use App\Models\Approval;
use App\Models\ApprovalLevel;
use App\Models\Division;
use Illuminate\Support\Facades\Storage;
use App\Models\Notification;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Document::with(['user', 'documentType', 'division', 'approvals']);
        
        // Filter berdasarkan role user
        if ($user->role->name === 'staff') {
            // Staff hanya bisa lihat dokumen yang dia buat sendiri
            $query->where('user_id', $user->id);
        } elseif ($user->role->name !== 'admin') {
            // Selain admin & staff (misal section head), hanya lihat dokumen di divisinya
            $query->where('division_id', $user->division_id);
        }
        
        // Filter berdasarkan request
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('division_id')) {
            $query->where('division_id', $request->division_id);
        }
        
        if ($request->filled('document_type_id')) {
            $query->where('document_type_id', $request->document_type_id);
        }
        
        $documents = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // Filter options untuk admin
        $options = [
            'documentTypes' => DocumentType::orderBy('name')->get()
        ];
        
        if ($user->role->name === 'admin') {
            $options['divisions'] = Division::orderBy('name')->get();
        }
        
        return view('documents.index', compact('documents', 'options'));
    }
    
    /**
     * Get filter options based on user role
     */
    private function getFilterOptions($user)
    {
        $options = [
            'documentTypes' => DocumentType::all(),
            'statuses' => [
                'pending' => 'Pending',
                'approved' => 'Approved',
                'rejected' => 'Rejected'
            ],
            'sortOptions' => [
                'created_at' => 'Tanggal Dibuat',
                'title' => 'Judul Dokumen',
                'status' => 'Status',
                'updated_at' => 'Tanggal Update'
            ]
        ];
        
        // Add division options for admin
        if ($user->role->name === 'admin') {
            $options['divisions'] = Division::orderBy('name')->get();
        }
        
        // Add approval status options for approvers
        if (in_array($user->role->name, ['manager', 'section_head', 'dept_head', 'admin'])) {
            $options['approvalStatuses'] = [
                'pending_approval' => 'Menunggu Approval Saya',
                'approved_by_me' => 'Sudah Saya Approve',
                'rejected_by_me' => 'Sudah Saya Reject'
            ];
        }
        
        return $options;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        $documentTypes = DocumentType::all();
        
        // Filter divisi berdasarkan role user
        if ($user->role->name === 'admin') {
            // Admin bisa pilih semua divisi
            $userDivisions = Division::orderBy('name')->get();
        } else {
            // Staff dan Section Head hanya bisa pilih divisi mereka sendiri
            $userDivisions = collect([$user->division]);
        }
        
        return view('documents.create', compact('documentTypes', 'userDivisions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'title' => 'required|string|max:255',
            'document_type_id' => 'required|exists:document_types,id',
            'description' => 'nullable|string',
            'division_id' => 'required|exists:divisions,id',
            'files.*' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg|max:20480',
            'file_descriptions.*' => 'nullable|string|max:500'
        ]);
        
        // Cek akses divisi: staff dan section head hanya bisa pilih divisi mereka
        if ($user->role->name !== 'admin' && $request->division_id != $user->division_id) {
            return redirect()->back()->with('error', 'Anda hanya bisa membuat dokumen untuk divisi Anda sendiri.');
        }
        
        $document = Document::create([
            'title' => $request->title,
            'document_type_id' => $request->document_type_id,
            'division_id' => $request->division_id,
            'description' => $request->description,
            'user_id' => $user->id,
            'status' => 'pending'
        ]);
        
        // Handle file uploads
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $index => $file) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('documents', $fileName, 'public');
                
                $description = $request->input("file_descriptions.{$index}");
                
                $document->files()->create([
                    'original_name' => $file->getClientOriginalName(),
                    'file_path' => $filePath,
                    'file_extension' => $file->getClientOriginalExtension(),
                    'file_size' => $file->getSize(),
                    'version' => 1,
                    'status' => 'active',
                    'description' => $description
                ]);
            }
        }
        
        return redirect()->route('documents.index')->with('success', 'Dokumen berhasil dibuat!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = Auth::user();
        $document = Document::with(['user', 'documentType', 'approvals.user', 'files', 'topLevelComments'])->findOrFail($id);
        
        // Check access based on role and division
        if ($user->role->name === 'admin') {
            // Admin can access all documents
        } elseif ($user->role->name === 'staff') {
            // Staff can only access their own documents
            if ($document->user_id !== $user->id) {
                abort(403, 'Anda tidak memiliki akses ke dokumen ini.');
            }
        } elseif (in_array($user->role->name, ['dept_head', 'section_head', 'manager'])) {
            // Dept head, section head, and manager can access documents from their divisions
            // Check if user has access to the document's division
            if ($user->division_id !== $document->division_id) {
                abort(403, 'Anda tidak memiliki akses ke dokumen ini.');
            }
        } else {
            // Default: Staff can only access their own documents
            if ($document->user_id !== $user->id) {
                abort(403, 'Anda tidak memiliki akses ke dokumen ini.');
            }
        }
        
        return view('documents.show', compact('document'));
    }

    /**
     * Show the form for editing the specified document.
     */
    public function edit($id)
    {
        $document = Document::findOrFail($id);
        $user = Auth::user();
        
        // Cek akses: staff dan section head hanya bisa edit dokumen dari divisi mereka
        if ($user->role->name !== 'admin' && $document->division_id !== $user->division_id) {
            return redirect()->route('documents.index')->with('error', 'Anda tidak memiliki akses untuk mengedit dokumen ini.');
        }
        
        $documentTypes = DocumentType::all();
        
        // Filter divisi berdasarkan role user
        if ($user->role->name === 'admin') {
            $userDivisions = Division::orderBy('name')->get();
        } else {
            $userDivisions = collect([$user->division]);
        }
        
        return view('documents.edit', compact('document', 'documentTypes', 'userDivisions'));
    }

    /**
     * Update the specified document in storage.
     */
    public function update(Request $request, $id)
    {
        $document = Document::findOrFail($id);
        $user = Auth::user();
        
        // Cek akses: staff dan section head hanya bisa update dokumen dari divisi mereka
        if ($user->role->name !== 'admin' && $document->division_id !== $user->division_id) {
            return redirect()->route('documents.index')->with('error', 'Anda tidak memiliki akses untuk mengupdate dokumen ini.');
        }
        
        $request->validate([
            'title' => 'required|string|max:255',
            'document_type_id' => 'required|exists:document_types,id',
            'description' => 'nullable|string',
            'division_id' => 'required|exists:divisions,id'
        ]);
        
        // Cek akses divisi: staff dan section head hanya bisa pilih divisi mereka
        if ($user->role->name !== 'admin' && $request->division_id != $user->division_id) {
            return redirect()->back()->with('error', 'Anda hanya bisa mengupdate dokumen untuk divisi Anda sendiri.');
        }
        
        $document->update([
            'title' => $request->title,
            'document_type_id' => $request->document_type_id,
            'division_id' => $request->division_id,
            'description' => $request->description
        ]);
        
        return redirect()->route('documents.index')->with('success', 'Dokumen berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Add comment to document
     */
    public function addComment(Request $request, $id)
    {
        $request->validate([
            'comment' => 'required|string|max:1000',
            'type' => 'required|in:general,approval,revision',
            'parent_id' => 'nullable|exists:document_comments,id',
        ]);

        $document = Document::findOrFail($id);
        $user = Auth::user();

        // Check access
        if ($user->role->name === 'staff' && $document->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke dokumen ini.');
        }

        DocumentComment::create([
            'document_id' => $document->id,
            'user_id' => $user->id,
            'parent_id' => $request->parent_id,
            'comment' => $request->comment,
            'type' => $request->type,
        ]);

        return back()->with('success', 'Komentar berhasil ditambahkan!');
    }

    /**
     * Upload new version of document file
     */
    public function uploadNewVersion(Request $request, $id)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg|max:20480',
            'description' => 'nullable|string|max:500',
        ]);

        $document = Document::findOrFail($id);
        $user = Auth::user();

        // Check access - only document owner or admin can upload new version
        if ($document->user_id !== $user->id && $user->role->name !== 'admin') {
            abort(403, 'Anda tidak memiliki akses untuk mengupload versi baru.');
        }

        $file = $request->file('file');
        $filePath = $file->store('documents', 'public');
        $nextVersion = $document->next_version;

        DocumentFile::create([
            'document_id' => $document->id,
            'file_path' => $filePath,
            'original_name' => $file->getClientOriginalName(),
            'file_extension' => $file->getClientOriginalExtension(),
            'file_size' => $file->getSize(),
            'version' => $nextVersion,
            'status' => 'active',
            'description' => $request->description,
        ]);

        return back()->with('success', 'Versi baru berhasil diupload!');
    }

    /**
     * Download document file
     */
    public function download($fileId)
    {
        $file = DocumentFile::findOrFail($fileId);
        $document = $file->document;
        $user = Auth::user();

        // Check access
        if ($user->role->name === 'staff' && $document->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke file ini.');
        }

        if (!Storage::disk('public')->exists($file->file_path)) {
            abort(404, 'File tidak ditemukan.');
        }

        return Storage::disk('public')->download($file->file_path, $file->original_name);
    }

    public function approve(Request $request, $id)
    {
        $request->validate([
            'level_id' => 'required|exists:approval_levels,id',
            'notes' => 'nullable|string',
            'action' => 'required|in:approved,rejected',
        ]);
        $document = Document::findOrFail($id);
        $user = Auth::user();
        $levelId = $request->level_id;
        $status = $request->action;

        // Cek apakah sudah pernah approve di level ini
        $existing = $document->approvals()->where('user_id', $user->id)->where('level_id', $levelId)->first();
        if ($existing) {
            return back()->with('error', 'Anda sudah melakukan approval pada level ini.');
        }

        // Simpan approval
        $document->approvals()->create([
            'user_id' => $user->id,
            'level_id' => $levelId,
            'status' => $status,
            'notes' => $request->notes,
            'approved_at' => now(),
        ]);

        // Jika Section Head, update status dokumen
        $level = ApprovalLevel::find($levelId);
        if ($level && $level->name === 'Section Head') {
            $document->status = $status;
            $document->save();
        }
        // Jika Manager dan status reject, dokumen langsung rejected
        if ($level && $level->name === 'Manager' && $status === 'rejected') {
            $document->status = 'rejected';
            $document->save();
        }

        // Notifikasi ke pengaju dokumen
        Notification::create([
            'user_id' => $document->user_id,
            'title' => 'Status Dokumen Berubah',
            'message' => 'Status dokumen "' . $document->title . '" menjadi ' . strtoupper($status) . '.',
            'link' => route('documents.show', $document->id),
            'type' => $status === 'approved' ? 'success' : 'warning',
        ]);
        // Notifikasi ke semua approver lain di divisi (kecuali yang melakukan approval ini)
        $approverRoles = ['section_head', 'dept_head', 'manager'];
        $approvers = \App\Models\User::whereIn('role_id', function($q) use ($approverRoles) {
            $q->select('id')->from('roles')->whereIn('name', $approverRoles);
        })->where('division_id', $document->division_id)->where('id', '!=', $user->id)->get();
        foreach ($approvers as $approver) {
            Notification::create([
                'user_id' => $approver->id,
                'title' => 'Status Dokumen Divisi Berubah',
                'message' => 'Status dokumen "' . $document->title . '" di divisi Anda berubah menjadi ' . strtoupper($status) . '.',
                'link' => route('documents.show', $document->id),
                'type' => $status === 'approved' ? 'success' : 'warning',
            ]);
        }

        return redirect()->route('documents.show', $document->id)->with('success', 'Approval berhasil!');
    }

    /**
     * Show the form for editing a document file.
     */
    public function editFile($id)
    {
        $file = DocumentFile::findOrFail($id);
        $document = $file->document;
        return view('documents.edit-file', compact('file', 'document'));
    }

    /**
     * Update the specified document file in storage.
     */
    public function updateFile(Request $request, $id)
    {
        $file = DocumentFile::findOrFail($id);
        $document = $file->document;

        $validated = $request->validate([
            'original_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'replace_file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg|max:20480',
        ]);

        $file->original_name = $validated['original_name'];
        $file->description = $validated['description'] ?? null;

        if ($request->hasFile('replace_file')) {
            // Hapus file lama
            if (Storage::disk('public')->exists($file->file_path)) {
                Storage::disk('public')->delete($file->file_path);
            }
            $uploaded = $request->file('replace_file');
            $filePath = $uploaded->store('documents', 'public');
            $file->file_path = $filePath;
            $file->file_extension = $uploaded->getClientOriginalExtension();
            $file->file_size = $uploaded->getSize();
        }

        $file->save();
        return redirect()->route('documents.show', $document->id)->with('success', 'File dokumen berhasil diupdate!');
    }
}
