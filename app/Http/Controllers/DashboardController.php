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
        
        // Cek akses dashboard analitik
        if (!in_array($role, ['dept_head', 'section_head', 'admin'])) {
            // Staff - redirect ke dashboard staff
            return redirect()->route('dashboard-staff');
        }
        
        if (in_array($role, ['dept_head', 'section_head', 'admin'])) {
            // Base query untuk dokumen
            $documentQuery = Document::query();
            
            // Filter berdasarkan role dan divisi
            if ($role === 'admin') {
                // Admin bisa lihat semua dengan filter divisi
                if ($divisionFilter) {
                    $documentQuery->where('division_id', $divisionFilter);
                }
            } elseif ($role === 'dept_head') {
                // Dept Head bisa lihat semua divisi di departemennya
                $documentQuery->whereHas('division', function($q) use ($user) {
                    $q->where('department_id', $user->division->department_id);
                });
            } elseif ($role === 'section_head') {
                // Section Head hanya lihat divisi mereka
                $documentQuery->where('division_id', $user->division_id);
            }
            
            // Statistik dokumen
            $stats['total'] = $documentQuery->count();
            $stats['pending'] = (clone $documentQuery)->where('status', 'pending')->count();
            $stats['approved'] = (clone $documentQuery)->where('status', 'approved')->count();
            $stats['rejected'] = (clone $documentQuery)->where('status', 'rejected')->count();
            $stats['to_approve'] = (clone $documentQuery)->where('status', 'pending')->whereDoesntHave('approvals', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->count();
            
            // KPI berdasarkan scope user
            $approvalQuery = Approval::query();
            if ($role === 'dept_head') {
                // Dept Head: approval untuk semua divisi di departemennya
                $approvalQuery->whereHas('document.division', function($q) use ($user) {
                    $q->where('department_id', $user->division->department_id);
                });
            } elseif ($role === 'section_head') {
                // Section Head: approval untuk divisi mereka
                $approvalQuery->whereHas('document', function($q) use ($user) {
                    $q->where('division_id', $user->division_id);
                });
            }
            
            $totalApproval = (clone $approvalQuery)->whereYear('created_at', 2025)->count();
            $totalApproved = (clone $approvalQuery)->where('status', 'approved')->whereYear('created_at', 2025)->count();
            $approvalRate = $totalApproval > 0 ? round($totalApproved / $totalApproval * 100, 2) : 0;
            $avgApprovalTime = (clone $approvalQuery)->where('status', 'approved')->whereYear('created_at', 2025)
                ->selectRaw('AVG(TIMESTAMPDIFF(SECOND, created_at, approved_at)) as avg_time')->value('avg_time');
            $avgApprovalTime = $avgApprovalTime ? round($avgApprovalTime / 3600, 2) : 0; // jam
            
            // Total divisi berdasarkan scope
            $divisionCount = 0;
            if ($role === 'admin') {
                $divisionCount = Division::count();
            } elseif ($role === 'dept_head') {
                $divisionCount = Division::where('department_id', $user->division->department_id)->count();
            } elseif ($role === 'section_head') {
                $divisionCount = 1; // Hanya divisi mereka
            }
            
            $kpi = [
                'approval_rate' => $approvalRate,
                'avg_approval_time' => $avgApprovalTime,
                'total_division' => $divisionCount,
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
            $chartApprovalTime = (clone $approvalQuery)->where('status', 'approved')
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

    /**
     * Dashboard khusus untuk Staff
     */
    public function staffDashboard(Request $request)
    {
        $user = Auth::user();
        
        // Cek apakah user adalah staff
        if (in_array($user->role->name, ['dept_head', 'section_head', 'admin'])) {
            return redirect()->route('dashboard');
        }
        
        // Statistik dokumen staff
        $stats = [
            'total' => Document::where('user_id', $user->id)->count(),
            'pending' => Document::where('user_id', $user->id)->where('status', 'pending')->count(),
            'approved' => Document::where('user_id', $user->id)->where('status', 'approved')->count(),
            'rejected' => Document::where('user_id', $user->id)->where('status', 'rejected')->count(),
            'to_approve' => 0, // Staff tidak bisa approve
        ];
        
        // Personal stats untuk staff
        $personalStats = [
            'total' => Document::where('user_id', $user->id)->count(),
            'pending' => Document::where('user_id', $user->id)->where('status', 'pending')->count(),
            'approved' => Document::where('user_id', $user->id)->where('status', 'approved')->count(),
            'rejected' => Document::where('user_id', $user->id)->where('status', 'rejected')->count(),
        ];
        
        // KPI untuk staff
        $totalDocuments = $stats['total'];
        $approvedDocuments = $stats['approved'];
        $approvalRate = $totalDocuments > 0 ? round($approvedDocuments / $totalDocuments * 100, 2) : 0;
        
        // Rata-rata waktu approval untuk dokumen staff
        $avgApprovalTime = Approval::whereHas('document', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->where('status', 'approved')->whereYear('created_at', 2025)
            ->selectRaw('AVG(TIMESTAMPDIFF(SECOND, created_at, approved_at)) as avg_time')->value('avg_time');
        $avgApprovalTime = $avgApprovalTime ? round($avgApprovalTime / 3600, 2) : 0;
        
        // Dokumen bulan ini
        $documentsThisMonth = Document::where('user_id', $user->id)
            ->whereYear('created_at', 2025)
            ->whereMonth('created_at', now()->month)
            ->count();
        
        $kpi = [
            'approval_rate' => $approvalRate,
            'avg_approval_time' => $avgApprovalTime,
            'total_division' => 1, // Hanya divisi mereka
            'total_approval' => Approval::whereHas('document', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->whereYear('created_at', 2025)->count(),
            'documents_this_month' => $documentsThisMonth,
        ];
        
        // Data untuk chart (statistik per bulan untuk dokumen staff)
        $chartData = Document::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->where('user_id', $user->id)
            ->whereYear('created_at', 2025)
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        
        // Dokumen terbaru staff
        $recentDocuments = Document::with(['user', 'documentType', 'division'])
            ->where('user_id', $user->id)
            ->latest()
            ->limit(5)
            ->get();
        
        // Riwayat approval untuk dokumen staff
        $approvalHistory = Approval::with(['document.user', 'document.documentType', 'document.division'])
            ->whereHas('document', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->latest()
            ->limit(10)
            ->get();
        
        $role = 'staff';
        $pendingApprovals = collect(); // Staff tidak punya pending approval
        $chartDivision = collect(); // Tidak ada chart divisi untuk staff
        $chartApprovalTime = collect(); // Tidak ada chart approval time untuk staff
        
        return view('dashboard-staff', compact('stats', 'role', 'chartData', 'recentDocuments', 'approvalHistory', 'kpi', 'personalStats'));
    }

    /**
     * Dashboard khusus untuk Staff (private method untuk internal use)
     */
    private function staffDashboardPrivate($user)
    {
        // Statistik dokumen staff
        $stats = [
            'total' => Document::where('user_id', $user->id)->count(),
            'pending' => Document::where('user_id', $user->id)->where('status', 'pending')->count(),
            'approved' => Document::where('user_id', $user->id)->where('status', 'approved')->count(),
            'rejected' => Document::where('user_id', $user->id)->where('status', 'rejected')->count(),
            'to_approve' => 0, // Staff tidak bisa approve
        ];
        
        // Personal stats untuk staff
        $personalStats = [
            'total' => Document::where('user_id', $user->id)->count(),
            'pending' => Document::where('user_id', $user->id)->where('status', 'pending')->count(),
            'approved' => Document::where('user_id', $user->id)->where('status', 'approved')->count(),
            'rejected' => Document::where('user_id', $user->id)->where('status', 'rejected')->count(),
        ];
        
        // KPI untuk staff
        $totalDocuments = $stats['total'];
        $approvedDocuments = $stats['approved'];
        $approvalRate = $totalDocuments > 0 ? round($approvedDocuments / $totalDocuments * 100, 2) : 0;
        
        // Rata-rata waktu approval untuk dokumen staff
        $avgApprovalTime = Approval::whereHas('document', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->where('status', 'approved')->whereYear('created_at', 2025)
            ->selectRaw('AVG(TIMESTAMPDIFF(SECOND, created_at, approved_at)) as avg_time')->value('avg_time');
        $avgApprovalTime = $avgApprovalTime ? round($avgApprovalTime / 3600, 2) : 0;
        
        // Dokumen bulan ini
        $documentsThisMonth = Document::where('user_id', $user->id)
            ->whereYear('created_at', 2025)
            ->whereMonth('created_at', now()->month)
            ->count();
        
        $kpi = [
            'approval_rate' => $approvalRate,
            'avg_approval_time' => $avgApprovalTime,
            'total_division' => 1, // Hanya divisi mereka
            'total_approval' => Approval::whereHas('document', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->whereYear('created_at', 2025)->count(),
            'documents_this_month' => $documentsThisMonth,
        ];
        
        // Data untuk chart (statistik per bulan untuk dokumen staff)
        $chartData = Document::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->where('user_id', $user->id)
            ->whereYear('created_at', 2025)
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        
        // Dokumen terbaru staff
        $recentDocuments = Document::with(['user', 'documentType', 'division'])
            ->where('user_id', $user->id)
            ->latest()
            ->limit(5)
            ->get();
        
        // Riwayat approval untuk dokumen staff
        $approvalHistory = Approval::with(['document.user', 'document.documentType', 'document.division'])
            ->whereHas('document', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->latest()
            ->limit(10)
            ->get();
        
        $role = 'staff';
        $pendingApprovals = collect(); // Staff tidak punya pending approval
        $chartDivision = collect(); // Tidak ada chart divisi untuk staff
        $chartApprovalTime = collect(); // Tidak ada chart approval time untuk staff
        
        return view('dashboard-staff', compact('stats', 'role', 'chartData', 'recentDocuments', 'approvalHistory', 'kpi', 'personalStats'));
    }

    // Export PDF statistik dashboard
    public function exportPdf(Request $request)
    {
        $user = Auth::user();
        $role = $user->role->name;
        
        // Cek akses export PDF
        if (!in_array($role, ['dept_head', 'section_head', 'admin'])) {
            return redirect()->route('access-denied');
        }
        
        // Ambil data statistik sama seperti index
        $divisionFilter = $request->get('division_filter');
        $documentQuery = Document::query();
        
        // Filter berdasarkan role
        if ($role === 'admin') {
            if ($divisionFilter) {
                $documentQuery->where('division_id', $divisionFilter);
            }
        } elseif ($role === 'dept_head') {
            $documentQuery->whereHas('division', function($q) use ($user) {
                $q->where('department_id', $user->division->department_id);
            });
        } elseif ($role === 'section_head') {
            $documentQuery->where('division_id', $user->division_id);
        }
        
        $stats = [
            'total' => $documentQuery->count(),
            'pending' => (clone $documentQuery)->where('status', 'pending')->count(),
            'approved' => (clone $documentQuery)->where('status', 'approved')->count(),
            'rejected' => (clone $documentQuery)->where('status', 'rejected')->count(),
        ];
        
        // KPI berdasarkan scope
        $approvalQuery = Approval::query();
        if ($role === 'dept_head') {
            $approvalQuery->whereHas('document.division', function($q) use ($user) {
                $q->where('department_id', $user->division->department_id);
            });
        } elseif ($role === 'section_head') {
            $approvalQuery->whereHas('document', function($q) use ($user) {
                $q->where('division_id', $user->division_id);
            });
        }
        
        $totalApproval = (clone $approvalQuery)->whereYear('created_at', 2025)->count();
        $totalApproved = (clone $approvalQuery)->where('status', 'approved')->whereYear('created_at', 2025)->count();
        $approvalRate = $totalApproval > 0 ? round($totalApproved / $totalApproval * 100, 2) : 0;
        $avgApprovalTime = (clone $approvalQuery)->where('status', 'approved')->whereYear('created_at', 2025)
            ->selectRaw('AVG(TIMESTAMPDIFF(SECOND, created_at, approved_at)) as avg_time')->value('avg_time');
        $avgApprovalTime = $avgApprovalTime ? round($avgApprovalTime / 3600, 2) : 0;
        
        $divisionCount = 0;
        if ($role === 'admin') {
            $divisionCount = Division::count();
        } elseif ($role === 'dept_head') {
            $divisionCount = Division::where('department_id', $user->division->department_id)->count();
        } elseif ($role === 'section_head') {
            $divisionCount = 1;
        }
        
        $kpi = [
            'approval_rate' => $approvalRate,
            'avg_approval_time' => $avgApprovalTime,
            'total_division' => $divisionCount,
            'total_approval' => $totalApproval,
        ];
        
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
        $chartApprovalTime = (clone $approvalQuery)->where('status', 'approved')
            ->whereYear('created_at', 2025)
            ->selectRaw('MONTH(created_at) as month, AVG(TIMESTAMPDIFF(SECOND, created_at, approved_at)) as avg_time')
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        $pdf = Pdf::loadView('dashboard-pdf', compact('stats', 'kpi', 'chartData', 'chartDivision', 'chartApprovalTime'));
        return $pdf->download('dashboard-statistik-'.date('Y-m-d').'.pdf');
    }
}
