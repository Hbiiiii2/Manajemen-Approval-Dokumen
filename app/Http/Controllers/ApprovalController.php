<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\Approval;
use Illuminate\Support\Facades\Auth;

class ApprovalController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = $user->role->name;
        
        // Dokumen yang perlu diapprove berdasarkan role
        if (in_array($role, ['manager', 'section_head', 'admin'])) {
            $documents = Document::where('status', 'pending')
                ->whereDoesntHave('approvals', function($q) use ($user) {
                    $q->where('user_id', $user->id);
                })
                ->with(['user', 'documentType'])
                ->get();
        } else {
            $documents = collect(); // Staff tidak bisa approve
        }
        
        return view('approvals.index', compact('documents', 'role'));
    }

    public function approve(Request $request, Document $document)
    {
        $user = Auth::user();
        
        // Cek apakah user sudah approve dokumen ini
        $existingApproval = Approval::where('document_id', $document->id)
            ->where('user_id', $user->id)
            ->first();
            
        if ($existingApproval) {
            return redirect()->back()->with('error', 'Anda sudah melakukan approval untuk dokumen ini.');
        }
        
        // Tentukan level berdasarkan role user
        $levelId = $user->role->name === 'manager' ? 1 : 2; // 1 = Manager, 2 = Section Head
        
        // Buat approval baru
        Approval::create([
            'document_id' => $document->id,
            'user_id' => $user->id,
            'level_id' => $levelId,
            'status' => 'approved',
            'notes' => $request->comment ?? 'Approved',
            'approved_at' => now(),
        ]);
        
        // Update status dokumen jika sudah diapprove oleh semua level yang diperlukan
        $this->updateDocumentStatus($document);
        
        return redirect()->back()->with('success', 'Dokumen berhasil diapprove.');
    }

    public function reject(Request $request, Document $document)
    {
        $user = Auth::user();
        
        // Cek apakah user sudah approve dokumen ini
        $existingApproval = Approval::where('document_id', $document->id)
            ->where('user_id', $user->id)
            ->first();
            
        if ($existingApproval) {
            return redirect()->back()->with('error', 'Anda sudah melakukan approval untuk dokumen ini.');
        }
        
        // Tentukan level berdasarkan role user
        $levelId = $user->role->name === 'manager' ? 1 : 2; // 1 = Manager, 2 = Section Head
        
        // Buat approval baru dengan status rejected
        Approval::create([
            'document_id' => $document->id,
            'user_id' => $user->id,
            'level_id' => $levelId,
            'status' => 'rejected',
            'notes' => $request->comment ?? 'Rejected',
            'approved_at' => now(),
        ]);
        
        // Update status dokumen menjadi rejected
        $document->update(['status' => 'rejected']);
        
        return redirect()->back()->with('success', 'Dokumen berhasil ditolak.');
    }

    private function updateDocumentStatus(Document $document)
    {
        // Logika untuk menentukan apakah dokumen sudah diapprove semua level
        // Ini bisa disesuaikan dengan kebutuhan bisnis
        $totalApprovals = $document->approvals()->where('status', 'approved')->count();
        
        // Contoh: jika sudah diapprove 2 orang, maka status menjadi approved
        if ($totalApprovals >= 2) {
            $document->update(['status' => 'approved']);
        }
    }
} 