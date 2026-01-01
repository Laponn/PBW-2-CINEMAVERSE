<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::latest()->get();
        return view('admin.branches.index', compact('branches'));
    }

    public function create()
    {
        return view('admin.branches.create');
    }

    public function store(Request $request)
    {
        // Validasi menyertakan address
        $request->validate([
            'name'    => 'required|string|max:255',
            'city'    => 'required|string|max:255',
            'address' => 'required|string', 
            'latitude'  => 'nullable|numeric', // Validasi angka
        'longitude' => 'nullable|numeric',
        ]);

        Branch::create($request->all());

        return redirect()->route('admin.branches.index')->with('success', 'Cabang berhasil ditambahkan');
    }

    public function edit(Branch $branch)
    {
        return view('admin.branches.edit', compact('branch'));
    }

    public function update(Request $request, Branch $branch)
    {
        // Validasi update menyertakan address
        $request->validate([
            'name'    => 'required|string|max:255',
            'city'    => 'required|string|max:255',
            'address' => 'required|string',
            'latitude'  => 'nullable|numeric', // Validasi angka
        'longitude' => 'nullable|numeric',
        ]);

        $branch->update($request->all());

        return redirect()->route('admin.branches.index')->with('success', 'Cabang berhasil diperbarui');
    }

    public function destroy(Branch $branch)
    {
        $branch->delete();
        return redirect()->route('admin.branches.index')->with('success', 'Cabang dihapus');
    }
}