<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use Illuminate\Support\Facades\Auth;
use App\Models\DocumentType;
use App\Models\Approval;
use App\Models\ApprovalLevel;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->role && in_array($user->role->name, ['manager', 'section_head', 'admin'])) {
            // Manager/Section Head/Admin bisa lihat semua dokumen
            $documents = Document::with('user', 'documentType')->latest()->paginate(10);
        } else {
            // Staff hanya bisa lihat dokumen miliknya
            $documents = Document::with('user', 'documentType')->where('user_id', $user->id)->latest()->paginate(10);
        }
        return view('documents.index', compact('documents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $documentTypes = DocumentType::all();
        return view('documents.create', compact('documentTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'document_type_id' => 'required|exists:document_types,id',
            'description' => 'nullable|string',
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg|max:20480',
        ]);

        $filePath = $request->file('file')->store('documents', 'public');

        $document = Document::create([
            'user_id' => Auth::id(),
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
        $document = Document::with(['user', 'documentType', 'approvals.user'])->findOrFail($id);
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
