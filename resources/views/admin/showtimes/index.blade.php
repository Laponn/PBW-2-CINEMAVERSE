@extends('layouts.admin')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Jadwal Tayang</h2>
    <a href="{{ route('admin.showtimes.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        + Buat Jadwal Baru
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
                <th class="px-6 py-3">Film</th>
                <th class="px-6 py-3">Lokasi & Studio</th>
                <th class="px-6 py-3">Tanggal & Jam</th>
                <th class="px-6 py-3">Harga Tiket</th>
                <th class="px-6 py-3 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @foreach($showtimes as $showtime)
            <tr class="hover:bg-gray-50">
                {{-- Nama Film --}}
                <td class="px-6 py-4">
                    <div class="font-bold text-gray-800">{{ $showtime->movie->title }}</div>
                    <div class="text-xs text-gray-500">{{ $showtime->movie->duration_minutes }} Menit</div>
                </td>
                
                {{-- Lokasi --}}
                <td class="px-6 py-4">
                    <div class="font-semibold text-gray-700">{{ $showtime->studio->branch->name ?? 'Unknown' }}</div>
                    <div class="text-xs text-gray-500">{{ $showtime->studio->name }} ({{ $showtime->studio->type }})</div>
                </td>
                
                {{-- Waktu --}}
                <td class="px-6 py-4">
                    <div class="font-bold text-blue-600">
                        {{ \Carbon\Carbon::parse($showtime->start_time)->format('d M Y') }}
                    </div>
                    <div class="text-sm font-semibold">
                        {{ \Carbon\Carbon::parse($showtime->start_time)->format('H:i') }} 
                        - 
                        <span class="text-gray-400">{{ \Carbon\Carbon::parse($showtime->end_time)->format('H:i') }}</span>
                    </div>
                </td>
                
                {{-- Harga --}}
                <td class="px-6 py-4 font-bold text-green-600">
                    Rp {{ number_format($showtime->price, 0, ',', '.') }}
                </td>
                
                {{-- Aksi --}}
                <td class="px-6 py-4 text-center space-x-2 whitespace-nowrap">
                    <a href="{{ route('admin.showtimes.edit', $showtime->id) }}" class="text-blue-600 hover:text-blue-900 font-bold text-sm">Edit</a>
                    
                    <form action="{{ route('admin.showtimes.destroy', $showtime->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus jadwal tayang ini?');">
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