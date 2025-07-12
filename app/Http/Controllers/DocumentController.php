<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use Illuminate\Support\Facades\Auth;
use App\Models\DocumentType;
use App\Models\Approval;
use App\Models\ApprovalLevel;
use App\Models\Division;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        if ($user->role->name === 'admin') {
            // Admin bisa lihat semua dokumen semua divisi
            $query = Document::with(['user', 'documentType', 'division']);
            
            // Filter divisi jika ada
            if ($request->has('division_filter') && $request->division_filter) {
                $query->where('division_id', $request->division_filter);
            }
            
            $documents = $query->latest()->paginate(10);
        } elseif ($user->role->name === 'staff') {
            // Staff hanya bisa lihat dokumen yang mereka ajukan sendiri
            $documents = Document::with(['user', 'documentType', 'division'])
                ->where('user_id', $user->id)
                ->latest()
                ->paginate(10);
        } else {
            // Dept head, section head, manager hanya bisa lihat dokumen divisinya
            $documents = Document::with(['user', 'documentType', 'division'])
                ->where('division_id', $user->division_id)
                ->latest()
                ->paginate(10);
        }
        
        return view('documents.index', compact('documents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        $userDivisions = collect();
        
        // Use primary division
        if ($user->division_id) {
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
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg|max:20480',
        ]);

        // Check if user has access to this division
        if ($validated['division_id'] != $user->division_id) {
            return back()->with('error', 'Anda tidak memiliki akses ke divisi ini.');
        }

        $filePath = $request->file('file')->store('documents', 'public');

        $document = Document::create([
            'user_id' => Auth::id(),
            'division_id' => $validated['division_id'],
            'document_type_id' => $validated['document_type_id'],
            'title' => $validated['title'],
            'file_path' => $filePath,
            'description' => $validated['description'] ?? null,
            'status' => 'pending',
        ]);

        return redirect()->route('documents.index')->with('success', 'Dokumen berhasil diajukan!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = Auth::user();
        $document = Document::with(['user', 'documentType', 'approvals.user'])->findOrFail($id);
        
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
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
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
}
