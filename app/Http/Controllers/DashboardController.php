<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\Division;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Approval;

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
        
        // Data untuk KPI
        $kpi = [
            'approval_rate' => 0,
            'avg_approval_time' => 0,
            'total_division' => 0,
            'total_approval' => 0,
        ];
        
        // Data untuk chart
        $chartData = [];
        $chartDivision = [];
        $chartApprovalTime = [];
        
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
            
            // KPI
            $totalApproval = Approval::whereYear('created_at', 2025)->count();
            $totalApproved = Approval::where('status', 'approved')->whereYear('created_at', 2025)->count();
            $approvalRate = $totalApproval > 0 ? round($totalApproved / $totalApproval * 100, 2) : 0;
            $avgApprovalTime = Approval::where('status', 'approved')->whereYear('created_at', 2025)
                ->selectRaw('AVG(TIMESTAMPDIFF(SECOND, created_at, approved_at)) as avg_time')->value('avg_time');
            $avgApprovalTime = $avgApprovalTime ? round($avgApprovalTime / 3600, 2) : 0; // jam
            $totalDivision = Division::where('status', 'active')->count();
            $kpi = [
                'approval_rate' => $approvalRate,
                'avg_approval_time' => $avgApprovalTime,
                'total_division' => $totalDivision,
                'total_approval' => $totalApproval,
            ];
            
            // Data untuk chart (statistik per bulan)
            $chartData = (clone $documentQuery)->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                ->whereYear('created_at', 2025)
                ->groupBy('month')
                ->orderBy('month')
                ->get();
            
            // Chart dokumen per divisi
            $chartDivision = (clone $documentQuery)
                ->selectRaw('division_id, COUNT(*) as count')
                ->whereYear('created_at', 2025)
                ->groupBy('division_id')
                ->with('division')
                ->get();
            
            // Chart waktu approval rata-rata per bulan
            $chartApprovalTime = Approval::where('status', 'approved')
                ->whereYear('created_at', 2025)
                ->selectRaw('MONTH(created_at) as month, AVG(TIMESTAMPDIFF(SECOND, created_at, approved_at)) as avg_time')
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
            $approvalHistory = Approval::with(['document.user', 'document.documentType', 'document.division'])
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
                ->whereYear('created_at', 2025)
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
        
        // Jika request AJAX (XHR), kembalikan data JSON + HTML partial
        if ($request->ajax()) {
            $html = view('partials.dashboard-statistics', compact('stats', 'role', 'kpi'))->render();
            return response()->json([
                'html' => $html,
                'chartData' => $chartData,
                'chartDivision' => $chartDivision,
                'chartApprovalTime' => $chartApprovalTime,
            ]);
        }
        return view('dashboard', compact('stats', 'role', 'chartData', 'recentDocuments', 'pendingApprovals', 'approvalHistory', 'kpi', 'chartDivision', 'chartApprovalTime'));
    }

    // Export PDF statistik dashboard
    public function exportPdf(Request $request)
    {
        $user = Auth::user();
        if ($user->role->name !== 'admin') {
            abort(403);
        }
        // Ambil data statistik sama seperti index
        $divisionFilter = $request->get('division_filter');
        $documentQuery = Document::query();
        if ($divisionFilter) {
            $documentQuery->where('division_id', $divisionFilter);
        }
        $stats = [
            'total' => $documentQuery->count(),
            'pending' => (clone $documentQuery)->where('status', 'pending')->count(),
            'approved' => (clone $documentQuery)->where('status', 'approved')->count(),
            'rejected' => (clone $documentQuery)->where('status', 'rejected')->count(),
        ];
        $kpi = [
            'approval_rate' => 0,
            'avg_approval_time' => 0,
            'total_division' => Division::where('status', 'active')->count(),
            'total_approval' => Approval::whereYear('created_at', 2025)->count(),
        ];
        $totalApproval = Approval::whereYear('created_at', 2025)->count();
        $totalApproved = Approval::where('status', 'approved')->whereYear('created_at', 2025)->count();
        $kpi['approval_rate'] = $totalApproval > 0 ? round($totalApproved / $totalApproval * 100, 2) : 0;
        $avgApprovalTime = Approval::where('status', 'approved')->whereYear('created_at', 2025)
            ->selectRaw('AVG(TIMESTAMPDIFF(SECOND, created_at, approved_at)) as avg_time')->value('avg_time');
        $kpi['avg_approval_time'] = $avgApprovalTime ? round($avgApprovalTime / 3600, 2) : 0;
        $chartData = (clone $documentQuery)->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', 2025)
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        $chartDivision = (clone $documentQuery)
            ->selectRaw('division_id, COUNT(*) as count')
            ->whereYear('created_at', 2025)
            ->groupBy('division_id')
            ->with('division')
            ->get();
        $chartApprovalTime = Approval::where('status', 'approved')
            ->whereYear('created_at', 2025)
            ->selectRaw('MONTH(created_at) as month, AVG(TIMESTAMPDIFF(SECOND, created_at, approved_at)) as avg_time')
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        $pdf = Pdf::loadView('dashboard-pdf', compact('stats', 'kpi', 'chartData', 'chartDivision', 'chartApprovalTime'));
        return $pdf->download('dashboard-statistik-'.date('Y-m-d').'.pdf');
    }
}
