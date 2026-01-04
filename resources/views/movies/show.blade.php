@extends('layouts.app')

@section('content')
{{-- Library Alpine.js untuk Tab Jadwal --}}
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<main class="pt-24 pb-16 min-h-screen bg-[#050509] text-white">
    <div class="max-w-screen-2xl mx-auto px-6 space-y-20">

        {{-- TOMBOL KEMBALI --}}
        <a href="{{ route('home') }}" class="inline-flex items-center gap-4 text-[11px] font-black text-zinc-500 hover:text-red-500 transition-all group uppercase tracking-[0.3em]">
            <span class="w-10 h-10 rounded-full border border-white/10 bg-white/5 flex items-center justify-center group-hover:bg-red-600 group-hover:border-red-600 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 19l-7-7m0 0l7-7m-7 7h18" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </span>
            Katalog Film
        </a>

        {{-- SECTION 1: HERO DETAIL (POSTER & SINOPSIS) --}}
        <section class="perspective-wrap">
            <div class="relative overflow-hidden rounded-[4rem] border border-white/5 bg-zinc-900/10 backdrop-blur-xl shadow-2xl">
                <div class="relative p-10 md:p-16 grid lg:grid-cols-[380px,1fr] gap-16 items-center">
                    
                    {{-- 3D TILT POSTER --}}
                    <div class="movie-tilt-card relative group mx-auto lg:mx-0 w-full max-w-[320px] aspect-[2/3] rounded-[2.5rem] overflow-hidden shadow-2xl border border-white/10 block">
                        <div class="glow"></div>
                        <img src="{{ $movie->poster_url }}" 
                             alt="{{ $movie->title }}" 
                             class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110 poster-wrap">
                    </div>
                    
                    {{-- INFO FILM --}}
                    <div class="space-y-8">
                        <div class="space-y-4">
                            <div class="flex items-center gap-4">
                                <span class="px-4 py-1.5 rounded-full bg-red-600/10 border border-red-600/20 text-[10px] font-black text-red-500 uppercase tracking-widest italic">Movie Profile</span>
                                <span class="text-zinc-500 text-[11px] font-black uppercase tracking-[0.3em]">{{ \Carbon\Carbon::parse($movie->release_date)->format('Y') }}</span>
                            </div>
                            
                            {{-- Judul (Ukuran Proporsional) --}}
                            <h1 class="text-4xl md:text-6xl font-black leading-[1] tracking-tighter italic uppercase text-white">
                                {{ $movie->title }}
                            </h1>

                            <div class="flex flex-wrap gap-3 pt-2">
                                <div class="px-5 py-2 rounded-2xl bg-white/5 border border-white/10 text-[10px] font-black text-zinc-300 uppercase italic">{{ $movie->duration_minutes }} MINS</div>
                                <div class="px-5 py-2 rounded-2xl bg-red-600 text-[10px] font-black text-white uppercase italic">{{ $movie->genre }}</div>
                            </div>
                        </div>

                        <p class="text-zinc-400 leading-relaxed max-w-3xl text-xl italic font-medium border-l-4 border-red-600 pl-6">
                            "{{ $movie->description }}"
                        </p>

                        <div class="flex flex-wrap items-center gap-6 pt-4">
                            <a href="#jadwal" class="px-12 py-5 rounded-full bg-red-600 hover:bg-red-700 text-white text-[11px] font-black uppercase tracking-[0.2em] shadow-xl shadow-red-600/20 transition-all transform hover:scale-105 italic">
                                Beli Tiket
                            </a>
                            @if($movie->trailer_url)
                            <a href="{{ $movie->trailer_url }}" target="_blank" class="px-12 py-5 rounded-full bg-white/5 border border-white/10 hover:bg-white/10 text-white text-[11px] font-black uppercase tracking-[0.2em] transition-all italic">
                                Trailer
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- SECTION 2: JADWAL TAYANG --}}
        @php
            $groupedShowtimes = $movie->showtimes->groupBy(function($st) {
                return \Carbon\Carbon::parse($st->start_time)->format('Y-m-d');
            })->sortKeys();
        @endphp

        <section id="jadwal" class="space-y-10 scroll-mt-32" x-data="{ activeDate: '{{ $groupedShowtimes->keys()->first() }}' }">
            <div class="flex items-end justify-between border-b border-white/5 pb-8">
                <h2 class="text-3xl font-black uppercase italic tracking-tighter text-white">Jadwal <span class="text-red-600">Tayang</span></h2>
                <span class="text-[10px] font-black text-zinc-500 uppercase tracking-widest italic">Cabang: {{ session('branch_name', 'Semua Lokasi') }}</span>
            </div>
            
            {{-- Tabs Tanggal --}}
            <div class="flex gap-4 overflow-x-auto pb-4 nice-scroll">
                @foreach($groupedShowtimes as $date => $shows)
                    <button @click="activeDate = '{{ $date }}'"
                        :class="activeDate === '{{ $date }}' ? 'border-red-600 bg-red-600 text-white shadow-lg shadow-red-600/20' : 'border-white/10 bg-white/5 text-zinc-500'"
                        class="flex-shrink-0 px-8 py-5 rounded-3xl border transition-all text-center min-w-[140px]">
                        <span class="block text-[10px] font-black uppercase tracking-widest mb-1 opacity-60">{{ \Carbon\Carbon::parse($date)->translatedFormat('D') }}</span>
                        <span class="block text-xl font-black italic uppercase">{{ \Carbon\Carbon::parse($date)->translatedFormat('d M') }}</span>
                    </button>
                @endforeach
            </div>

            {{-- Grid Jam Tayang (Studio) --}}
            <div class="perspective-wrap">
                @forelse($groupedShowtimes as $date => $shows)
                    <div x-show="activeDate === '{{ $date }}'" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-y-4"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        
                        @foreach($shows->groupBy('studio.name') as $studioName => $times)
                            <div class="movie-tilt-card bg-zinc-900/40 border border-white/5 p-8 rounded-[3rem] backdrop-blur-md relative overflow-hidden flex flex-col justify-between group h-full">
                                <div class="glow"></div>
                                <div class="info-wrap">
                                    <h3 class="font-black text-red-600 uppercase tracking-widest text-sm italic">{{ $studioName }}</h3>
                                    <p class="text-[9px] font-bold text-zinc-500 uppercase tracking-widest mt-2">
                                        {{ $times->first()->studio->type }} CLASS • {{ $times->first()->studio->capacity }} SEATS
                                    </p>
                                </div>
                                
                                {{-- Link ke halaman movies.ticket --}}
                                <div class="grid grid-cols-2 gap-3 mt-8 poster-wrap">
                                    @foreach($times as $st)
                                        <a href="{{ route('movies.ticket', $movie->id) }}?showtime_id={{ $st->id }}" 
                                           class="p-4 rounded-2xl border border-white/5 bg-white/5 hover:bg-white hover:text-black transition-all text-center group/time shadow-lg">
                                            <div class="text-lg font-black italic tracking-tighter">{{ \Carbon\Carbon::parse($st->start_time)->format('H:i') }}</div>
                                            <div class="text-[8px] font-black text-zinc-500 group-hover/time:text-zinc-600 uppercase mt-1 italic leading-none">
                                                Rp {{ number_format($st->price, 0, ',', '.') }}
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @empty
                    <div class="py-24 text-center border-2 border-dashed border-zinc-800 rounded-[3rem]">
                        <p class="text-zinc-600 font-black uppercase tracking-widest italic text-sm">Jadwal tayang tidak tersedia.</p>
                    </div>
                @endforelse
            </div>
        </section>
    </div>
</main>

<style>
    .perspective-wrap { perspective: 2000px; }
    .movie-tilt-card { transform-style: preserve-3d; transition: transform 0.2s ease-out; }
    .movie-tilt-card .glow {
        position: absolute; inset: -20%; pointer-events: none; z-index: 5;
        background: radial-gradient(circle at var(--gx, 50%) var(--gy, 50%), rgba(220, 38, 38, 0.15) 0%, transparent 60%);
    }
    .poster-wrap { transform: translateZ(30px); }
    .info-wrap { transform: translateZ(50px); }
    
    html { scroll-behavior: smooth; }
    .nice-scroll::-webkit-scrollbar { height: 4px; }
    .nice-scroll::-webkit-scrollbar-thumb { background: #dc2626; border-radius: 10px; }
</style>

<script>
    // Logika Tilt 3D untuk Poster & Kartu Jadwal
    document.querySelectorAll('.movie-tilt-card').forEach(card => {
        card.addEventListener('mousemove', e => {
            const rect = card.getBoundingClientRect();
            const x = (e.clientX - rect.left) / rect.width;
            const y = (e.clientY - rect.top) / rect.height;
            card.style.transform = `rotateX(${-(y - 0.5) * 12}deg) rotateY(${(x - 0.5) * 12}deg) scale(1.02)`;
            card.style.setProperty('--gx', `${x * 100}%`);
            card.style.setProperty('--gy', `${y * 100}%`);
        });
        card.addEventListener('mouseleave', () => {
            card.style.transform = 'rotateX(0deg) rotateY(0deg) scale(1)';
        });
    });
</script>
@endsection