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

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Base query dengan eager loading
        $query = Document::with(['user', 'documentType', 'division']);
        
        // Role-based filtering
        if ($user->role->name === 'admin') {
            // Admin bisa lihat semua dokumen semua divisi
        } elseif ($user->role->name === 'staff') {
            // Staff hanya bisa lihat dokumen yang mereka ajukan sendiri
            $query->where('user_id', $user->id);
        } else {
            // Dept head, section head, manager hanya bisa lihat dokumen divisinya
            $query->where('division_id', $user->division_id);
        }
        
        // Advanced Search & Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', '%' . $search . '%')
                               ->orWhere('email', 'like', '%' . $search . '%');
                  });
            });
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by document type
        if ($request->filled('document_type_id')) {
            $query->where('document_type_id', $request->document_type_id);
        }
        
        // Filter by division (only for admin)
        if ($user->role->name === 'admin' && $request->filled('division_id')) {
            $query->where('division_id', $request->division_id);
        }
        
        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        // Filter by approval status (if user is approver)
        if (in_array($user->role->name, ['manager', 'section_head', 'dept_head', 'admin']) && $request->filled('approval_status')) {
            if ($request->approval_status === 'pending_approval') {
                $query->where('status', 'pending')
                      ->whereDoesntHave('approvals', function($q) use ($user) {
                          $q->where('user_id', $user->id);
                      });
            } elseif ($request->approval_status === 'approved_by_me') {
                $query->whereHas('approvals', function($q) use ($user) {
                    $q->where('user_id', $user->id)
                      ->where('status', 'approved');
                });
            } elseif ($request->approval_status === 'rejected_by_me') {
                $query->whereHas('approvals', function($q) use ($user) {
                    $q->where('user_id', $user->id)
                      ->where('status', 'rejected');
                });
            }
        }
        
        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        // Validate sort fields
        $allowedSortFields = ['title', 'status', 'created_at', 'updated_at'];
        if (!in_array($sortBy, $allowedSortFields)) {
            $sortBy = 'created_at';
        }
        
        $query->orderBy($sortBy, $sortOrder);
        
        // Pagination
        $perPage = $request->get('per_page', 10);
        $allowedPerPage = [5, 10, 25, 50, 100];
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 10;
        }
        
        $documents = $query->paginate($perPage);
        
        // Get filter options for the view
        $filterOptions = $this->getFilterOptions($user);
        
        return view('documents.index', compact('documents', 'filterOptions'));
    }
    
    /**
     * Get filter options based on user role
     */
    private function getFilterOptions($user)
    {
        $options = [
            'documentTypes' => DocumentType::orderBy('name')->get(),
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
            $options['divisions'] = Division::where('status', 'active')->orderBy('name')->get();
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
        $userDivisions = collect();
        
        if ($user->role->name === 'admin') {
            // Admin bisa pilih semua divisi
            $userDivisions = Division::where('status', 'active')->get();
        } elseif ($user->division_id) {
            // User lain hanya bisa pilih divisi mereka sendiri
            $userDivisions = collect([$user->division]);
        }
        
        $documentTypes = DocumentType::all();
        
        return view('documents.create', compact('documentTypes', 'userDivisions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'document_type_id' => 'required|exists:document_types,id',
            'division_id' => 'required|exists:divisions,id',
            'description' => 'nullable|string',
            'files.*' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg|max:20480',
            'file_descriptions.*' => 'nullable|string|max:500',
        ]);

        // Check if user has access to this division
        if ($user->role->name !== 'admin' && $validated['division_id'] != $user->division_id) {
            return back()->with('error', 'Anda tidak memiliki akses ke divisi ini.');
        }

        // Create document
        $document = Document::create([
            'user_id' => Auth::id(),
            'division_id' => $validated['division_id'],
            'document_type_id' => $validated['document_type_id'],
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'status' => 'pending',
        ]);

        // Upload multiple files
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $index => $file) {
                $filePath = $file->store('documents', 'public');
                $description = $request->input("file_descriptions.{$index}");
                
                DocumentFile::create([
                    'document_id' => $document->id,
                    'file_path' => $filePath,
                    'original_name' => $file->getClientOriginalName(),
                    'file_extension' => $file->getClientOriginalExtension(),
                    'file_size' => $file->getSize(),
                    'version' => 1,
                    'status' => 'active',
                    'description' => $description,
                ]);
            }
        }

        return redirect()->route('documents.index')->with('success', 'Dokumen berhasil diajukan!');
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
        $userDivisions = collect();
        if ($user->role->name === 'admin') {
            $userDivisions = Division::where('status', 'active')->get();
        } elseif ($user->division_id) {
            $userDivisions = collect([$user->division]);
        }
        $documentTypes = DocumentType::all();
        return view('documents.edit', compact('document', 'documentTypes', 'userDivisions'));
    }

    /**
     * Update the specified document in storage.
     */
    public function update(Request $request, $id)
    {
        $document = Document::findOrFail($id);
        $user = Auth::user();
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'document_type_id' => 'required|exists:document_types,id',
            'division_id' => 'required|exists:divisions,id',
            'description' => 'nullable|string',
        ]);
        // Check if user has access to this division
        if ($user->role->name !== 'admin' && $validated['division_id'] != $user->division_id) {
            return back()->with('error', 'Anda tidak memiliki akses ke divisi ini.');
        }
        $document->update([
            'title' => $validated['title'],
            'document_type_id' => $validated['document_type_id'],
            'division_id' => $validated['division_id'],
            'description' => $validated['description'] ?? null,
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
