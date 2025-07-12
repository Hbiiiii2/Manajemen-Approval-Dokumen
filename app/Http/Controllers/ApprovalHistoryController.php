<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Approval;
use Illuminate\Support\Facades\Auth;

class ApprovalHistoryController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Ambil semua approval yang dilakukan oleh user ini
        $approvalHistory = Approval::with(['document.user', 'document.documentType', 'document.division', 'level'])
            ->where('user_id', $user->id)
            ->latest('approved_at')
            ->paginate(15);
        
        // Statistik approval
        $stats = [
            'total_approved' => Approval::where('user_id', $user->id)->where('status', 'approved')->count(),
            'total_rejected' => Approval::where('user_id', $user->id)->where('status', 'rejected')->count(),
            'total_pending' => Approval::where('user_id', $user->id)->where('status', 'pending')->count(),
        ];
        
        return view('approval-history.index', compact('approvalHistory', 'stats'));
    }

    public function show($id)
    {
        $approval = Approval::with(['document.user', 'document.documentType', 'document.division', 'level'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);
        
        return view('approval-history.show', compact('approval'));
    }
}
