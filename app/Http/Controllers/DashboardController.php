<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\Division;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $role = $user->role->name ?? 'staff';
        
        // Filter divisi untuk admin
        $divisionFilter = $request->get('division_filter');
        
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
            // Base query untuk dokumen
            $documentQuery = Document::query();
            
            // Filter divisi untuk admin
            if ($role == 'admin' && $divisionFilter) {
                $documentQuery->where('division_id', $divisionFilter);
            } elseif ($role != 'admin') {
                // Manager dan Section Head hanya lihat dokumen divisi mereka
                $userDivisions = $user->divisionRoles()->pluck('division_id');
                $documentQuery->whereIn('division_id', $userDivisions);
            }
            
            // Manager, Section Head, dan Admin bisa lihat semua dokumen
            $stats['total'] = $documentQuery->count();
            $stats['pending'] = (clone $documentQuery)->where('status', 'pending')->count();
            $stats['approved'] = (clone $documentQuery)->where('status', 'approved')->count();
            $stats['rejected'] = (clone $documentQuery)->where('status', 'rejected')->count();
            $stats['to_approve'] = (clone $documentQuery)->where('status', 'pending')->whereDoesntHave('approvals', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->count();
            
            // Data untuk chart (statistik per bulan)
            $chartData = (clone $documentQuery)->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                ->whereYear('created_at', date('Y'))
                ->groupBy('month')
                ->orderBy('month')
                ->get();
            
            // Dokumen terbaru
            $recentDocuments = (clone $documentQuery)->with(['user', 'documentType', 'division'])
                ->latest()
                ->limit(5)
                ->get();
            
            // Dokumen yang perlu diapprove
            $pendingApprovals = (clone $documentQuery)->with(['user', 'documentType', 'division'])
                ->where('status', 'pending')
                ->whereDoesntHave('approvals', function($q) use ($user) {
                    $q->where('user_id', $user->id);
                })
                ->latest()
                ->limit(5)
                ->get();
            
            // Riwayat approval yang dilakukan user ini
            $approvalHistory = \App\Models\Approval::with(['document.user', 'document.documentType', 'document.division'])
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
            $recentDocuments = Document::with(['user', 'documentType', 'division'])
                ->where('user_id', $user->id)
                ->latest()
                ->limit(5)
                ->get();
        }
        
        return view('dashboard', compact('stats', 'role', 'chartData', 'recentDocuments', 'pendingApprovals', 'approvalHistory'));
    }
}
