@extends('layouts.admin')

@section('content')
<div class="space-y-8 pb-12">
    {{-- HEADER: Abu-abu Muda dengan Garis Tepi --}}
    <div class="bg-gray-50 p-8 rounded-[2rem] border border-gray-200 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-3xl font-black uppercase italic tracking-tighter text-gray-900">
                Dashboard <span class="text-red-600">Overview</span>
            </h2>
            <p class="text-gray-500 text-[10px] font-black uppercase tracking-[0.2em] mt-1">
                Selamat datang kembali, {{ Auth::user()->name }}
            </p>
        </div>

        <a href="{{ route('home') }}"
           class="inline-flex items-center gap-2 px-6 py-3 bg-white border border-gray-300 text-gray-700 text-[10px] font-black uppercase tracking-widest rounded-full hover:bg-gray-100 transition shadow-sm">
            Kembali ke Beranda
        </a>
    </div>

    {{-- KONTEN STATISTIK: Kartu Putih dengan Garis Abu-abu --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @php
            $stats = [
                ['label' => 'Total Pendapatan', 'value' => 'Rp ' . number_format($totalEarnings, 0, ',', '.'), 'color' => 'text-green-600', 'border' => 'border-green-200'],
                ['label' => 'Katalog Film', 'value' => $totalMovies, 'color' => 'text-red-600', 'border' => 'border-red-200'],
                ['label' => 'Total Cabang', 'value' => $totalBranches, 'color' => 'text-blue-600', 'border' => 'border-blue-200'],
                ['label' => 'Total Studio', 'value' => $totalStudios, 'color' => 'text-purple-600', 'border' => 'border-purple-200'],
            ];
        @endphp

        @foreach($stats as $stat)
        <div class="bg-white p-8 rounded-[2rem] border-2 {{ $stat['border'] }} shadow-sm">
            <h3 class="text-gray-400 text-[10px] font-black uppercase tracking-widest mb-2">{{ $stat['label'] }}</h3>
            <p class="text-2xl font-black {{ $stat['color'] }} italic tracking-tighter">{{ $stat['value'] }}</p>
        </div>
        @endforeach
    </div>

    {{-- TABEL RINGKASAN: Putih dengan Border Tegas --}}
    <div class="bg-white rounded-[2.5rem] border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-8 border-b border-gray-200 flex items-center justify-between bg-gray-50/50">
            <h3 class="text-lg font-black uppercase italic tracking-tighter text-gray-900">
                Update <span class="text-red-600">Katalog</span>
            </h3>
            <a href="{{ route('admin.movies.index') }}" class="text-[10px] font-black uppercase text-gray-400 hover:text-red-600 transition">Lihat Semua →</a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 text-gray-400 uppercase text-[9px] font-black tracking-widest border-b border-gray-200">
                    <tr>
                        <th class="px-8 py-4">Judul Film</th>
                        <th class="px-8 py-4 text-center">Status</th>
                        <th class="px-8 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($movies as $movie)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-8 py-5">
                            <span class="font-bold text-gray-800 uppercase italic text-sm tracking-tight">{{ $movie->title }}</span>
                        </td>
                        <td class="px-8 py-5 text-center">
                            <span class="px-4 py-1 rounded-full bg-gray-100 border border-gray-200 text-[8px] font-black uppercase text-gray-500">
                                {{ str_replace('_', ' ', $movie->status) }}
                            </span>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <a href="{{ route('admin.movies.edit', $movie->id) }}" class="text-[10px] font-black text-red-600 uppercase hover:underline italic">Edit</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-8 py-12 text-center text-gray-400 text-xs italic font-medium">Belum ada data film terbaru.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection