<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Approval;
use Illuminate\Support\Facades\Auth;

class ApprovalHistoryController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Base query
        $query = Approval::with(['document.user', 'document.documentType', 'document.division', 'level'])
            ->where('user_id', $user->id);
        
        // Advanced Search & Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('document', function($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', '%' . $search . '%')
                               ->orWhere('email', 'like', '%' . $search . '%');
                  });
            });
        }
        
        // Filter by approval status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by document type
        if ($request->filled('document_type_id')) {
            $query->whereHas('document', function($q) use ($request) {
                $q->where('document_type_id', $request->document_type_id);
            });
        }
        
        // Filter by division (if user is admin)
        if ($user->role->name === 'admin' && $request->filled('division_id')) {
            $query->whereHas('document', function($q) use ($request) {
                $q->where('division_id', $request->division_id);
            });
        }
        
        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('approved_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('approved_at', '<=', $request->date_to);
        }
        
        // Filter by approval level
        if ($request->filled('level_id')) {
            $query->where('level_id', $request->level_id);
        }
        
        // Sorting
        $sortBy = $request->get('sort_by', 'approved_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        // Validate sort fields
        $allowedSortFields = ['approved_at', 'status', 'created_at'];
        if (!in_array($sortBy, $allowedSortFields)) {
            $sortBy = 'approved_at';
        }
        
        $query->orderBy($sortBy, $sortOrder);
        
        // Pagination
        $perPage = $request->get('per_page', 15);
        $allowedPerPage = [5, 10, 15, 25, 50, 100];
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 15;
        }
        
        $approvalHistory = $query->paginate($perPage);
        
        // Statistik approval
        $stats = [
            'total_approved' => Approval::where('user_id', $user->id)->where('status', 'approved')->count(),
            'total_rejected' => Approval::where('user_id', $user->id)->where('status', 'rejected')->count(),
            'total_pending' => Approval::where('user_id', $user->id)->where('status', 'pending')->count(),
        ];
        
        // Get filter options
        $filterOptions = $this->getFilterOptions($user);
        
        return view('approval-history.index', compact('approvalHistory', 'stats', 'filterOptions'));
    }
    
    /**
     * Get filter options for approval history
     */
    private function getFilterOptions($user)
    {
        $options = [
            'documentTypes' => \App\Models\DocumentType::orderBy('name')->get(),
            'statuses' => [
                'approved' => 'Approved',
                'rejected' => 'Rejected',
                'pending' => 'Pending'
            ],
            'levels' => \App\Models\ApprovalLevel::orderBy('name')->get(),
            'sortOptions' => [
                'approved_at' => 'Tanggal Approval',
                'status' => 'Status',
                'created_at' => 'Tanggal Dibuat'
            ]
        ];
        
        // Add division options for admin
        if ($user->role->name === 'admin') {
            $options['divisions'] = \App\Models\Division::where('status', 'active')->orderBy('name')->get();
        }
        
        return $options;
    }

    public function show($id)
    {
        $approval = Approval::with(['document.user', 'document.documentType', 'document.division', 'level'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);
        
        return view('approval-history.show', compact('approval'));
    }
}
