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
    public function index()
    {
        $users = User::with(['role', 'division', 'divisionRoles.division'])->latest()->paginate(10);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        $divisions = Division::where('status', 'active')->get();
        return view('users.create', compact('roles', 'divisions'));
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
        $divisions = Division::where('status', 'active')->get();
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