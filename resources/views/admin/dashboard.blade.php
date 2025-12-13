@extends('layouts.admin')

@section('content')
    {{-- UBAHAN: Menggunakan Flexbox untuk memisahkan Judul dan Tombol --}}
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-3xl font-bold text-gray-800">Dashboard</h2>
        
        {{-- Tombol Kembali ke Home --}}
        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-800 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition shadow-md">
            {{-- Icon Panah Kiri --}}
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Home
        </a>
    </div>

    {{-- KONTEN STATISTIK (Tetap sama) --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-lg shadow border-l-4 border-blue-500">
            <h3 class="text-gray-500 text-sm font-bold uppercase">Total Film</h3>
            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalMovies }}</p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow border-l-4 border-green-500">
            <h3 class="text-gray-500 text-sm font-bold uppercase">Pendapatan Tiket</h3>
            <p class="text-3xl font-bold text-gray-800 mt-2">Rp {{ number_format($totalEarnings) }}</p>
        </div>
    </div>
@endsection