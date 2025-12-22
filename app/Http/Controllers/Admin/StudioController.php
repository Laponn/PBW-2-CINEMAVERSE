<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Studio;
use App\Models\Branch;
use Illuminate\Http\Request;

class StudioController extends Controller
{
    public function index()
    {
        // Load relasi branch untuk menampilkan nama cabang di tabel
        
        $studios = Studio::with('branch')->latest()->get();
        return view('admin.studios.index', compact('studios'));
    }

    public function create()
    {
        // Ambil data cabang untuk dropdown
        $branches = Branch::all();
        return view('admin.studios.create', compact('branches'));
    }

    public function store(Request $request)
    {
        // Validasi sesuai kolom di database
        $request->validate([
            'branch_id'  => 'required|exists:branches,id',
            'name'       => 'required|string|max:255',
            'type'       => 'required', // regular, vip, dll
            'base_price' => 'required|numeric|min:0', // Kolom baru sesuai gambar DB
            'capacity'   => 'required|integer|min:1',
        ]);

        Studio::create($request->all());

        return redirect()->route('admin.studios.index')->with('success', 'Studio berhasil ditambahkan');
    }

    public function edit(Studio $studio)
    {
        $branches = Branch::all();
        return view('admin.studios.edit', compact('studio', 'branches'));
    }

    public function update(Request $request, Studio $studio)
    {
        $request->validate([
            'branch_id'  => 'required|exists:branches,id',
            'name'       => 'required|string|max:255',
            'type'       => 'required',
            'base_price' => 'required|numeric|min:0',
            'capacity'   => 'required|integer|min:1',
        ]);

        $studio->update($request->all());

        return redirect()->route('admin.studios.index')->with('success', 'Studio berhasil diperbarui');
    }

    public function destroy(Studio $studio)
    {
        $studio->delete();
        return redirect()->route('admin.studios.index')->with('success', 'Studio dihapus');
    }
}