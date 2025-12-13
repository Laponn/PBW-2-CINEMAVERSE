@extends('layouts.admin')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Daftar Cabang Bioskop</h2>
    <a href="{{ route('admin.branches.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        + Tambah Cabang
    </a>
</div>

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead class="bg-gray-100 text-gray-700 uppercase text-xs font-bold">
            <tr>
                <th class="px-6 py-3">Nama Cabang</th>
                <th class="px-6 py-3">Kota</th>
                {{-- Menambahkan Kolom Alamat --}}
                <th class="px-6 py-3">Alamat</th>
                <th class="px-6 py-3 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @foreach($branches as $branch)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 font-bold text-gray-800">{{ $branch->name }}</td>
                <td class="px-6 py-4">{{ $branch->city }}</td>
                {{-- Menampilkan Data Alamat --}}
                <td class="px-6 py-4 text-sm text-gray-600">{{ Str::limit($branch->address, 50) }}</td>
                <td class="px-6 py-4 text-center space-x-2 whitespace-nowrap">
                    <a href="{{ route('admin.branches.edit', $branch->id) }}" class="text-blue-600 hover:text-blue-900 font-bold text-sm">Edit</a>
                    
                    <form action="{{ route('admin.branches.destroy', $branch->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin hapus cabang ini? Studio di dalamnya juga akan terhapus!');">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900 font-bold text-sm">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection