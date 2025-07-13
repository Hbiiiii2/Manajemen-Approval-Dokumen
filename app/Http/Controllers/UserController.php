<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Division;
use App\Models\DivisionRole;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // Base query dengan eager loading
        $query = User::with(['role', 'division', 'divisionRoles.division']);
        
        // Advanced Search & Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }
        
        // Filter by role
        if ($request->filled('role_id')) {
            $query->where('role_id', $request->role_id);
        }
        
        // Filter by division
        if ($request->filled('division_id')) {
            $query->where('division_id', $request->division_id);
        }
        
        // Filter by status (active/inactive based on last login)
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('last_login_at', '>=', now()->subDays(30));
            } elseif ($request->status === 'inactive') {
                $query->where(function($q) {
                    $q->whereNull('last_login_at')
                      ->orWhere('last_login_at', '<', now()->subDays(30));
                });
            }
        }
        
        // Filter by date range (created_at)
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        // Filter by last login range
        if ($request->filled('last_login_from')) {
            $query->whereDate('last_login_at', '>=', $request->last_login_from);
        }
        
        if ($request->filled('last_login_to')) {
            $query->whereDate('last_login_at', '<=', $request->last_login_to);
        }
        
        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        // Validate sort fields
        $allowedSortFields = ['name', 'email', 'created_at', 'last_login_at'];
        if (!in_array($sortBy, $allowedSortFields)) {
            $sortBy = 'created_at';
        }
        
        $query->orderBy($sortBy, $sortOrder);
        
        // Pagination
        $perPage = $request->get('per_page', 10);
        $allowedPerPage = [5, 10, 25, 50, 100];
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 10;
        }
        
        $users = $query->paginate($perPage);
        
        // Get filter options
        $filterOptions = $this->getFilterOptions();
        
        return view('users.index', compact('users', 'filterOptions'));
    }
    
    /**
     * Get filter options for user management
     */
    private function getFilterOptions()
    {
        return [
            'roles' => Role::orderBy('name')->get(),
            'divisions' => Division::orderBy('name')->get(),
            'statuses' => [
                'active' => 'Active (Login dalam 30 hari)',
                'inactive' => 'Inactive (Tidak login > 30 hari)'
            ],
            'sortOptions' => [
                'name' => 'Nama',
                'email' => 'Email',
                'created_at' => 'Tanggal Dibuat',
                'last_login_at' => 'Last Login'
            ]
        ];
    }

    public function create()
    {
        return view('users.create', [
            'roles' => Role::all(),
            'divisions' => Division::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'primary_division_id' => 'required|exists:divisions,id',
            'primary_role_id' => 'required|exists:roles,id',
            'divisions' => 'array',
            'divisions.*.division_id' => 'exists:divisions,id',
            'divisions.*.role_id' => 'exists:roles,id',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role_id' => $validated['primary_role_id'],
                'division_id' => $validated['primary_division_id'],
            ]);

            // Create primary division role
            DivisionRole::create([
                'user_id' => $user->id,
                'division_id' => $validated['primary_division_id'],
                'role_id' => $validated['primary_role_id'],
                'is_primary' => true,
            ]);

            // Create additional division roles
            if ($request->has('divisions')) {
                foreach ($request->divisions as $divisionData) {
                    if (!empty($divisionData['division_id']) && !empty($divisionData['role_id'])) {
                        DivisionRole::create([
                            'user_id' => $user->id,
                            'division_id' => $divisionData['division_id'],
                            'role_id' => $divisionData['role_id'],
                            'is_primary' => false,
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $user = User::with('divisionRoles.division')->findOrFail($id);
        $roles = Role::all();
        $divisions = Division::get();
        return view('users.edit', compact('user', 'roles', 'divisions'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'primary_division_id' => 'required|exists:divisions,id',
            'primary_role_id' => 'required|exists:roles,id',
            'password' => 'nullable|string|min:8|confirmed',
            'divisions' => 'array',
            'divisions.*.division_id' => 'exists:divisions,id',
            'divisions.*.role_id' => 'exists:roles,id',
        ]);

        DB::beginTransaction();
        try {
            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'role_id' => $validated['primary_role_id'],
                'division_id' => $validated['primary_division_id'],
            ]);

            if ($request->filled('password')) {
                $user->update(['password' => Hash::make($validated['password'])]);
            }

            // Delete existing division roles
            $user->divisionRoles()->delete();

            // Create primary division role
            DivisionRole::create([
                'user_id' => $user->id,
                'division_id' => $validated['primary_division_id'],
                'role_id' => $validated['primary_role_id'],
                'is_primary' => true,
            ]);

            // Create additional division roles
            if ($request->has('divisions')) {
                foreach ($request->divisions as $divisionData) {
                    if (!empty($divisionData['division_id']) && !empty($divisionData['role_id'])) {
                        DivisionRole::create([
                            'user_id' => $user->id,
                            'division_id' => $divisionData['division_id'],
                            'role_id' => $divisionData['role_id'],
                            'is_primary' => false,
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('users.index')->with('success', 'User berhasil diupdate!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Mencegah admin menghapus dirinya sendiri
        if ($user->id === Auth::id()) {
            return redirect()->route('users.index')->with('error', 'Tidak dapat menghapus akun sendiri!');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus!');
    }
} 