@extends('layouts.admin')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Daftar Studio</h2>
    <a href="{{ route('admin.studios.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        + Tambah Studio
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
                <th class="px-6 py-3">Cabang</th>
                <th class="px-6 py-3">Nama Studio</th>
                <th class="px-6 py-3">Tipe</th>
                {{-- Sesuai Gambar DB --}}
                <th class="px-6 py-3">Harga Dasar</th>
                <th class="px-6 py-3">Kapasitas</th>
                <th class="px-6 py-3 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @foreach($studios as $studio)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 text-gray-600 font-semibold">
                    {{ $studio->branch->name ?? '-' }}
                </td>
                <td class="px-6 py-4 font-bold text-gray-800">{{ $studio->name }}</td>
                <td class="px-6 py-4">
                    <span class="px-2 py-1 rounded text-xs font-bold uppercase
                        {{ $studio->type == 'vip' ? 'bg-yellow-100 text-yellow-800 border border-yellow-300' : 'bg-gray-100 text-gray-600' }}">
                        {{ $studio->type }}
                    </span>
                </td>
                {{-- Menampilkan Base Price --}}
                <td class="px-6 py-4">Rp {{ number_format($studio->base_price, 0, ',', '.') }}</td>
                <td class="px-6 py-4">{{ $studio->capacity }} Kursi</td>
                <td class="px-6 py-4 text-center space-x-2 whitespace-nowrap">
                    <a href="{{ route('admin.studios.edit', $studio->id) }}" class="text-blue-600 hover:text-blue-900 font-bold text-sm">Edit</a>
                    
                    <form action="{{ route('admin.studios.destroy', $studio->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus studio ini?');">
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