@extends('layouts.admin')

@section('content')
<div class="flex flex-col md:flex-row items-center justify-between mb-8 gap-4">
    <div>
        <h2 class="text-3xl font-black uppercase italic tracking-tighter text-gray-900">Jadwal <span class="text-red-600">Tayang</span></h2>
        <p class="text-gray-500 text-[10px] font-bold uppercase tracking-widest mt-1">Manajemen Waktu Putar Bioskop</p>
    </div>
    <a href="{{ route('admin.showtimes.create') }}" class="px-6 py-3 bg-red-600 text-white text-xs font-black uppercase tracking-widest rounded-full hover:bg-red-700 transition shadow-lg shadow-red-600/20">
        + Buat Jadwal Baru
    </a>
</div>

{{-- FORM FILTER --}}
{{-- FORM FILTER --}}
<div class="bg-white p-6 rounded-[2rem] border border-gray-200 shadow-sm mb-8">
    <form action="{{ route('admin.showtimes.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
        {{-- Filter Cabang --}}
        <div>
            <label class="text-[10px] font-black uppercase text-gray-400 ml-2">Pilih Cabang</label>
            <select name="branch_id" class="w-full mt-1 border-gray-200 rounded-xl text-sm font-bold bg-gray-50">
                <option value="">Semua Lokasi</option>
                @foreach($branches as $branch)
                    <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                        {{ $branch->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Filter Film --}}
        <div>
            <label class="text-[10px] font-black uppercase text-gray-400 ml-2">Pilih Film</label>
            <select name="movie_id" class="w-full mt-1 border-gray-200 rounded-xl text-sm font-bold bg-gray-50">
                <option value="">Semua Film</option>
                @foreach($movies as $movie)
                    <option value="{{ $movie->id }}" {{ request('movie_id') == $movie->id ? 'selected' : '' }}>
                        {{ $movie->title }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Filter Studio --}}
        <div>
            <label class="text-[10px] font-black uppercase text-gray-400 ml-2">Pilih Studio</label>
            <select name="studio_id" class="w-full mt-1 border-gray-200 rounded-xl text-sm font-bold bg-gray-50">
                <option value="">Semua Studio</option>
                @foreach($studios as $studio)
                    <option value="{{ $studio->id }}" {{ request('studio_id') == $studio->id ? 'selected' : '' }}>
                        {{ $studio->name }} ({{ $studio->branch->city }})
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Filter Tanggal --}}
        <div>
            <label class="text-[10px] font-black uppercase text-gray-400 ml-2">Tanggal Tayang</label>
            <input type="date" name="date" value="{{ request('date') }}" class="w-full mt-1 border-gray-200 rounded-xl text-sm font-bold bg-gray-50">
        </div>

        {{-- Tombol Aksi --}}
        <div class="flex gap-2">
            <button type="submit" class="flex-1 px-4 py-2.5 bg-gray-800 text-white rounded-xl text-[10px] font-black uppercase hover:bg-gray-700 transition">
                Filter
            </button>
            <a href="{{ route('admin.showtimes.index') }}" class="px-4 py-2.5 bg-gray-100 text-gray-500 rounded-xl text-[10px] font-black uppercase hover:bg-gray-200 text-center">
                Reset
            </a>
        </div>
    </form>
</div>

@if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-600 px-6 py-4 rounded-2xl mb-6 text-sm font-bold">
        ✨ {{ session('success') }}
    </div>
@endif

<div class="bg-white rounded-[2rem] border border-gray-200 shadow-sm overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead class="bg-gray-50 text-gray-400 uppercase text-[9px] font-black tracking-widest border-b border-gray-200">
            <tr>
                <th class="px-8 py-5 italic">Movie Information</th>
                <th class="px-8 py-5 italic">Location & Studio</th>
                <th class="px-8 py-5 italic">Schedule</th>
                <th class="px-8 py-5 italic text-right">Price</th>
                <th class="px-8 py-5 text-center italic">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($showtimes as $showtime)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-8 py-5">
                    <div class="font-black text-gray-900 uppercase italic tracking-tight">{{ $showtime->movie->title }}</div>
                    <div class="text-[10px] text-gray-400 font-bold uppercase">{{ $showtime->movie->duration_minutes }} Minutes</div>
                </td>
                
                <td class="px-8 py-5">
                    <div class="font-bold text-gray-700 uppercase text-xs">{{ $showtime->studio->branch->name ?? 'Unknown' }}</div>
                    <div class="text-[10px] text-gray-400 font-black uppercase italic">{{ $showtime->studio->name }} ({{ strtoupper($showtime->studio->type) }})</div>
                </td>
                
                <td class="px-8 py-5">
                    <div class="font-black text-red-600 text-xs">
                        {{ \Carbon\Carbon::parse($showtime->start_time)->format('D, d M Y') }}
                    </div>
                    <div class="text-sm font-black text-gray-900 italic leading-none">
                        {{ \Carbon\Carbon::parse($showtime->start_time)->format('H:i') }} 
                        <span class="text-gray-300 mx-1">-</span> 
                        <span class="text-gray-400 text-xs font-bold">{{ \Carbon\Carbon::parse($showtime->end_time)->format('H:i') }}</span>
                    </div>
                </td>
                
                <td class="px-8 py-5 text-right">
                    <div class="font-black text-gray-900 tracking-tighter">Rp {{ number_format($showtime->price, 0, ',', '.') }}</div>
                </td>
                
                <td class="px-8 py-5 text-center">
                    <div class="flex justify-center gap-4">
                        <a href="{{ route('admin.showtimes.edit', $showtime->id) }}" class="text-[10px] font-black text-gray-400 hover:text-red-600 uppercase transition italic">Edit</a>
                        
                        <form action="{{ route('admin.showtimes.destroy', $showtime->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus jadwal ini?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-[10px] font-black text-gray-300 hover:text-red-600 uppercase transition italic">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-8 py-20 text-center text-gray-400 italic text-xs uppercase font-black tracking-widest">
                    Tidak ada jadwal tayang ditemukan
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection