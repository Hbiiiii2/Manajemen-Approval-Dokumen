<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use Illuminate\Support\Facades\Auth;
use App\Models\Approval;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = $user->role->name ?? 'staff';
        $stats = [
            'total' => 0,
            'pending' => 0,
            'approved' => 0,
            'rejected' => 0,
            'to_approve' => 0,
        ];
        if (in_array($role, ['manager', 'section_head'])) {
            $stats['total'] = Document::count();
            $stats['pending'] = Document::where('status', 'pending')->count();
            $stats['approved'] = Document::where('status', 'approved')->count();
            $stats['rejected'] = Document::where('status', 'rejected')->count();
            // Approval yang harus diproses user ini
            $stats['to_approve'] = Document::where('status', 'pending')->whereDoesntHave('approvals', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->count();
        } else {
            $stats['total'] = Document::where('user_id', $user->id)->count();
            $stats['pending'] = Document::where('user_id', $user->id)->where('status', 'pending')->count();
            $stats['approved'] = Document::where('user_id', $user->id)->where('status', 'approved')->count();
            $stats['rejected'] = Document::where('user_id', $user->id)->where('status', 'rejected')->count();
        }
        return view('dashboard', compact('stats', 'role'));
    }
}
