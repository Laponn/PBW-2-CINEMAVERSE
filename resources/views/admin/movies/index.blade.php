{{-- resources/views/admin/movies/index.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="space-y-8">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-zinc-200 pb-8">
        <div>
            <h2 class="text-3xl font-black uppercase italic tracking-tighter text-zinc-900">
                Daftar <span class="text-red-600">Film</span>
            </h2>
            <p class="text-sm text-zinc-500 font-bold uppercase tracking-widest mt-1">Kelola katalog film CinemaVerse Anda</p>
        </div>
        <a href="{{ route('admin.movies.create') }}" 
           class="inline-flex items-center justify-center px-8 py-3 bg-red-600 hover:bg-red-700 text-white text-xs font-black rounded-full transition transform hover:scale-105 shadow-xl shadow-red-600/20 uppercase tracking-widest">
            + Tambah Film Baru
        </a>
    </div>

    {{-- Table Section --}}
    <div class="bg-white rounded-[2.5rem] shadow-2xl overflow-hidden border border-zinc-200">
        <table class="w-full text-left border-collapse">
            <thead class="bg-zinc-900 text-zinc-400 uppercase text-[10px] font-black tracking-[0.2em]">
                <tr>
                    <th class="px-8 py-5">Poster</th>
                    <th class="px-8 py-5">Judul & Genre</th>
                    <th class="px-8 py-5 text-center">Status</th>
                    <th class="px-8 py-5 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100">
                @foreach($movies as $movie)
                <tr class="hover:bg-zinc-50 transition-colors group">
                    {{-- Poster --}}
                    <td class="px-8 py-5">
                        <div class="w-16 h-24 rounded-xl overflow-hidden shadow-lg border border-zinc-200 transform group-hover:scale-105 transition-transform">
                            {{-- Logika Gambar Pintar --}}
                            <img src="{{ str_starts_with($movie->poster_url, 'http') ? $movie->poster_url : asset($movie->poster_url) }}" 
                                 class="w-full h-full object-cover"
                                 onerror="this.src='https://placehold.co/500x750?text=No+Poster'">
                        </div>
                    </td>

                    {{-- Judul & Genre --}}
                    <td class="px-8 py-5">
                        <div class="flex flex-col">
                            <span class="text-lg font-black text-zinc-900 uppercase italic tracking-tight group-hover:text-red-600 transition-colors">
                                {{ $movie->title }}
                            </span>
                            <span class="text-[10px] text-zinc-400 font-black uppercase tracking-widest mt-1">
                                {{ $movie->genre ?? 'Uncategorized' }} | {{ $movie->duration_minutes }} Min
                            </span>
                        </div>
                    </td>

                    {{-- Status Badge --}}
                    <td class="px-8 py-5 text-center">
                        @php
                            $statusClasses = [
                                'now_showing' => 'bg-green-100 text-green-700 border-green-200',
                                'coming_soon' => 'bg-blue-100 text-blue-700 border-blue-200',
                                'ended'       => 'bg-zinc-100 text-zinc-700 border-zinc-200',
                            ];
                            $class = $statusClasses[$movie->status] ?? 'bg-zinc-100 text-zinc-700';
                        @endphp
                        <span class="px-4 py-1.5 rounded-full border {{ $class }} text-[9px] font-black uppercase tracking-widest">
                            {{ str_replace('_', ' ', $movie->status) }}
                        </span>
                    </td>

                    {{-- Aksi --}}
                    <td class="px-8 py-5 text-right">
                        <div class="flex justify-end gap-3">
                            <a href="{{ route('admin.movies.edit', $movie->id) }}" 
                               class="p-2 rounded-xl bg-zinc-100 text-zinc-600 hover:bg-red-600 hover:text-white transition-all shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </a>
                            
                            <form action="{{ route('admin.movies.destroy', $movie->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus film ini? Semua jadwal tayang juga akan terhapus.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 rounded-xl bg-zinc-100 text-red-500 hover:bg-red-600 hover:text-white transition-all shadow-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection