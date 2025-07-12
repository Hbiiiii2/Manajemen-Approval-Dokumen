<?php

namespace App\Http\Controllers;

use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DivisionController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!Auth::user() || Auth::user()->role->name !== 'admin') {
                abort(403, 'Unauthorized');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $divisions = Division::paginate(10);
        return view('divisions.index', compact('divisions'));
    }

    public function create()
    {
        return view('divisions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:20|unique:divisions,code',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);
        Division::create($validated);
        return redirect()->route('divisions.index')->with('success', 'Divisi berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $division = Division::findOrFail($id);
        return view('divisions.edit', compact('division'));
    }

    public function update(Request $request, $id)
    {
        $division = Division::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:20|unique:divisions,code,' . $division->id,
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);
        $division->update($validated);
        return redirect()->route('divisions.index')->with('success', 'Divisi berhasil diupdate!');
    }

    public function destroy($id)
    {
        $division = Division::findOrFail($id);
        $division->delete();
        return redirect()->route('divisions.index')->with('success', 'Divisi berhasil dihapus!');
    }
}
