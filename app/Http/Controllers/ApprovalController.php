<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\Approval;
use App\Models\ApprovalLevel;
use Illuminate\Support\Facades\Auth;

class ApprovalController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = $user->role->name;
        $documents = collect();
        
        if ($role === 'dept_head') {
            // Dept Head approve dokumen dari semua divisi di departemennya yang sudah di-approve Section Head
            $documents = Document::where('status', 'pending')
                ->whereHas('division', function($q) use ($user) {
                    $q->where('department_id', $user->division->department_id);
                })
                ->whereDoesntHave('approvals', function($q) use ($user) {
                    $q->where('user_id', $user->id);
                })
                ->whereHas('approvals', function($q) {
                    $q->whereHas('user.role', function($qr) {
                        $qr->where('name', 'section_head');
                    })->where('status', 'approved');
                })
                ->with(['user', 'documentType', 'division'])
                ->get();
        } else if ($role === 'section_head') {
            // Section Head approve dokumen dari staff
            $documents = Document::where('status', 'pending')
                ->where('division_id', $user->division_id)
                ->whereDoesntHave('approvals', function($q) use ($user) {
                    $q->where('user_id', $user->id);
                })
                ->whereHas('user.role', function($q) {
                    $q->where('name', 'staff');
                })
                ->with(['user', 'documentType'])
                ->get();
        } else if (in_array($role, ['manager', 'admin'])) {
            // Manager/admin bisa lihat semua (atau sesuai kebutuhan)
            $documents = Document::where('status', 'pending')
                ->where('division_id', $user->division_id)
                ->whereDoesntHave('approvals', function($q) use ($user) {
                    $q->where('user_id', $user->id);
                })
                ->with(['user', 'documentType'])
                ->get();
        }
        // Staff tidak bisa approve, jadi $documents tetap collect() kosong
        
        return view('approvals.index', compact('documents', 'role'));
    }

    public function approve(Request $request, Document $document)
    {
        $user = Auth::user();
        
        // Cek apakah user memiliki role yang bisa approve
        if (!in_array($user->role->name, ['manager', 'section_head', 'dept_head', 'admin'])) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
            }
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk melakukan approval.');
        }
        
        // Cek apakah user sudah approve dokumen ini
        $existingApproval = Approval::where('document_id', $document->id)
            ->where('user_id', $user->id)
            ->first();
            
        if ($existingApproval) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Anda sudah melakukan approval untuk dokumen ini.'], 409);
            }
            return redirect()->back()->with('error', 'Anda sudah melakukan approval untuk dokumen ini.');
        }
        
        // Tentukan level berdasarkan role user
        $levelId = null;
        if ($user->role->name === 'section_head') {
            $levelId = ApprovalLevel::where('name', 'Section Head')->first()->id;
        } elseif ($user->role->name === 'dept_head') {
            $levelId = ApprovalLevel::where('name', 'Dept Head')->first()->id;
        } else {
            // Fallback untuk manager/admin
            $levelId = ApprovalLevel::where('name', 'Section Head')->first()->id;
        }
        
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
        
        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'id' => $document->id]);
        }
        return redirect()->back()->with('success', 'Dokumen berhasil diapprove.');
    }

    public function reject(Request $request, Document $document)
    {
        $user = Auth::user();
        
        // Cek apakah user memiliki role yang bisa approve
        if (!in_array($user->role->name, ['manager', 'section_head', 'dept_head', 'admin'])) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk melakukan approval.');
        }
        
        // Cek apakah user sudah approve dokumen ini
        $existingApproval = Approval::where('document_id', $document->id)
            ->where('user_id', $user->id)
            ->first();
            
        if ($existingApproval) {
            return redirect()->back()->with('error', 'Anda sudah melakukan approval untuk dokumen ini.');
        }
        
        // Tentukan level berdasarkan role user
        $levelId = null;
        if ($user->role->name === 'section_head') {
            $levelId = ApprovalLevel::where('name', 'Section Head')->first()->id;
        } elseif ($user->role->name === 'dept_head') {
            $levelId = ApprovalLevel::where('name', 'Dept Head')->first()->id;
        } else {
            // Fallback untuk manager/admin
            $levelId = ApprovalLevel::where('name', 'Section Head')->first()->id;
        }
        
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
        $creatorRole = $document->user->role->name;

        if ($creatorRole === 'section_head') {
            // Jika Section Head yang submit, cukup Dept Head approve
            $deptHeadApproval = $document->approvals()
                ->where('status', 'approved')
                ->whereHas('user.role', function($q) {
                    $q->where('name', 'dept_head');
                })->exists();

            if ($deptHeadApproval) {
                $document->update(['status' => 'approved']);
            }
        } else {
            // Jika Staff yang submit, butuh Section Head dan Dept Head approve
            $sectionHeadApproval = $document->approvals()
                ->where('status', 'approved')
                ->whereHas('user.role', function($q) {
                    $q->where('name', 'section_head');
                })->exists();

            $deptHeadApproval = $document->approvals()
                ->where('status', 'approved')
                ->whereHas('user.role', function($q) {
                    $q->where('name', 'dept_head');
                })->exists();

            if ($sectionHeadApproval && $deptHeadApproval) {
                $document->update(['status' => 'approved']);
            }
        }
    }
} 