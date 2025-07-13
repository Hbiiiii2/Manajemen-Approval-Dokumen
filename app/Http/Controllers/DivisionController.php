<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DivisionController extends Controller
{
    public function __construct()
    {
        // Admin middleware sudah ditangani di route
    }

    public function index()
    {
        $divisions = Division::with(['department', 'users'])->paginate(10);
        return view('divisions.index', compact('divisions'));
    }

    public function create()
    {
        $departments = Department::all();
        return view('divisions.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'department_id' => 'required|exists:departments,id'
        ]);

        Division::create($request->all());

        return redirect()->route('divisions.index')
            ->with('success', 'Divisi berhasil ditambahkan.');
    }

    public function edit(Division $division)
    {
        $departments = Department::all();
        return view('divisions.edit', compact('division', 'departments'));
    }

    public function update(Request $request, Division $division)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'department_id' => 'required|exists:departments,id'
        ]);

        $division->update($request->all());

        return redirect()->route('divisions.index')
            ->with('success', 'Divisi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $division = Division::findOrFail($id);
        $division->delete();
        return redirect()->route('divisions.index')->with('success', 'Divisi berhasil dihapus!');
    }
}
