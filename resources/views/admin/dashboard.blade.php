@extends('layouts.admin')

@section('content')
    {{-- HEADER: Judul & Tombol Home --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">Dashboard Overview</h2>
            <p class="text-gray-500 text-sm mt-1">Selamat datang kembali, Admin!</p>
        </div>
        
        {{-- Tombol Kembali ke Home --}}
        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-800 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition shadow-md group">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Home
        </a>
    </div>

    {{-- KONTEN STATISTIK --}}
    {{-- Ubah grid jadi 4 kolom agar muat semua --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        
        {{-- 1. Total Pendapatan --}}
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 border-l-4 border-l-green-500 relative overflow-hidden">
            <div class="relative z-10">
                <h3 class="text-gray-500 text-xs font-bold uppercase tracking-wider">Total Pendapatan</h3>
                <p class="text-2xl font-extrabold text-gray-800 mt-2">Rp {{ number_format($totalEarnings) }}</p>
            </div>
            <div class="absolute right-4 top-4 text-green-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
        </div>

        {{-- 2. Total Film --}}
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 border-l-4 border-l-blue-500 relative overflow-hidden">
            <div class="relative z-10">
                <h3 class="text-gray-500 text-xs font-bold uppercase tracking-wider">Total Film</h3>
                <p class="text-3xl font-extrabold text-gray-800 mt-2">{{ $totalMovies }}</p>
            </div>
            <div class="absolute right-4 top-4 text-blue-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" /></svg>
            </div>
        </div>

        {{-- 3. Total Cabang (BARU) --}}
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 border-l-4 border-l-orange-500 relative overflow-hidden">
            <div class="relative z-10">
                <h3 class="text-gray-500 text-xs font-bold uppercase tracking-wider">Total Cabang</h3>
                <p class="text-3xl font-extrabold text-gray-800 mt-2">{{ $totalBranches }}</p>
            </div>
            <div class="absolute right-4 top-4 text-orange-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
            </div>
        </div>

        {{-- 4. Total Studio (BARU) --}}
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 border-l-4 border-l-purple-500 relative overflow-hidden">
            <div class="relative z-10">
                <h3 class="text-gray-500 text-xs font-bold uppercase tracking-wider">Total Studio</h3>
                <p class="text-3xl font-extrabold text-gray-800 mt-2">{{ $totalStudios }}</p>
            </div>
            <div class="absolute right-4 top-4 text-purple-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" /></svg>
            </div>
        </div>

    </div>
@endsection