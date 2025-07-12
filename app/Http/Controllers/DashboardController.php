<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = $user->role->name ?? 'staff';
        
        // Statistik dasar
        $stats = [
            'total' => 0,
            'pending' => 0,
            'approved' => 0,
            'rejected' => 0,
            'to_approve' => 0,
        ];
        
        // Data untuk chart
        $chartData = [];
        
        // Data untuk tabel
        $recentDocuments = [];
        $pendingApprovals = [];
        $approvalHistory = [];
        
        if (in_array($role, ['manager', 'section_head', 'admin'])) {
            // Manager, Section Head, dan Admin bisa lihat semua dokumen
            $stats['total'] = Document::count();
            $stats['pending'] = Document::where('status', 'pending')->count();
            $stats['approved'] = Document::where('status', 'approved')->count();
            $stats['rejected'] = Document::where('status', 'rejected')->count();
            $stats['to_approve'] = Document::where('status', 'pending')->whereDoesntHave('approvals', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->count();
            
            // Data untuk chart (statistik per bulan)
            $chartData = Document::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                ->whereYear('created_at', date('Y'))
                ->groupBy('month')
                ->orderBy('month')
                ->get();
            
            // Dokumen terbaru
            $recentDocuments = Document::with(['user', 'documentType'])
                ->latest()
                ->limit(5)
                ->get();
            
            // Dokumen yang perlu diapprove
            $pendingApprovals = Document::with(['user', 'documentType'])
                ->where('status', 'pending')
                ->whereDoesntHave('approvals', function($q) use ($user) {
                    $q->where('user_id', $user->id);
                })
                ->latest()
                ->limit(5)
                ->get();
            
            // Riwayat approval yang dilakukan user ini
            $approvalHistory = \App\Models\Approval::with(['document.user', 'document.documentType'])
                ->where('user_id', $user->id)
                ->latest()
                ->limit(10)
                ->get();
                
        } else {
            // Staff hanya bisa lihat dokumen miliknya
            $stats['total'] = Document::where('user_id', $user->id)->count();
            $stats['pending'] = Document::where('user_id', $user->id)->where('status', 'pending')->count();
            $stats['approved'] = Document::where('user_id', $user->id)->where('status', 'approved')->count();
            $stats['rejected'] = Document::where('user_id', $user->id)->where('status', 'rejected')->count();
            
            // Data untuk chart (statistik per bulan untuk user ini)
            $chartData = Document::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                ->where('user_id', $user->id)
                ->whereYear('created_at', date('Y'))
                ->groupBy('month')
                ->orderBy('month')
                ->get();
            
            // Dokumen terbaru user ini
            $recentDocuments = Document::with(['user', 'documentType'])
                ->where('user_id', $user->id)
                ->latest()
                ->limit(5)
                ->get();
        }
        
        return view('dashboard', compact('stats', 'role', 'chartData', 'recentDocuments', 'pendingApprovals', 'approvalHistory'));
    }
}
