@extends('layouts.app')

@section('content')
<main class="pt-24 pb-16 min-h-screen bg-[#050509]">
    <div class="max-w-screen-2xl mx-auto px-6 space-y-16">

        <a href="{{ route('home') }}" class="inline-flex items-center gap-3 text-[10px] font-black text-zinc-400 hover:text-red-500 transition group uppercase tracking-[0.2em]">
            <span class="w-8 h-8 rounded-full border border-white/10 bg-white/5 flex items-center justify-center group-hover:bg-red-600 group-hover:border-red-600 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 19l-7-7m0 0l7-7m-7 7h18" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </span>
            Kembali
        </a>

        <section class="relative overflow-hidden rounded-[3.5rem] border border-white/5 bg-zinc-900/10 backdrop-blur-md shadow-2xl">
            <div class="absolute inset-0 bg-gradient-to-br from-red-600/5 via-transparent to-transparent"></div>
            
            <div class="relative p-8 md:p-16 grid md:grid-cols-[380px,1fr] gap-16 items-center">
                <div class="relative group mx-auto md:mx-0 w-full max-w-[320px] md:max-w-none">
                    <div class="absolute -inset-4 bg-red-600 rounded-[2.5rem] blur-2xl opacity-10"></div>
                    <img src="{{ str_starts_with($movie->poster_url, 'http') ? $movie->poster_url : asset($movie->poster_url) }}" 
     alt="{{ $movie->title }}" 
     onerror="this.src='https://via.placeholder.com/500x750?text=Poster+Tidak+Tersedia'"
     class="w-full h-full object-cover">
                </div>
                
                <div class="space-y-10">
                    <div class="space-y-6">
                        <div class="flex items-center gap-4">
                            <span class="px-4 py-1 rounded-full bg-red-600/10 border border-red-600/20 text-[10px] font-black text-red-500 uppercase tracking-widest">Movie Detail</span>
                            <span class="text-zinc-500 text-[10px] font-black uppercase tracking-widest">{{ $movie->release_date }}</span>
                        </div>
                        <h1 class="text-5xl md:text-8xl font-black leading-none tracking-tighter italic uppercase text-white">
                            {{ $movie->title }}
                        </h1>
                        <div class="flex flex-wrap gap-3">
                            <div class="px-5 py-2 rounded-2xl bg-white/5 border border-white/10 text-[10px] font-black tracking-widest text-zinc-300 uppercase italic">{{ $movie->duration_minutes }} MINS</div>
                            <div class="px-5 py-2 rounded-2xl bg-red-600/10 border border-red-600/20 text-[10px] font-black tracking-widest text-red-500 uppercase italic">{{ $movie->genre }}</div>
                        </div>
                    </div>

                    <p class="text-zinc-400 leading-relaxed max-w-3xl text-xl italic font-medium border-l-4 border-red-600 pl-6">
                        "{{ $movie->description }}"
                    </p>

                    <div class="flex flex-wrap items-center gap-4 pt-4">
                        <a href="{{ route('movies.ticket', $movie->id) }}" 
                           class="px-12 py-5 rounded-full bg-red-600 hover:bg-red-700 text-white text-[11px] font-black uppercase tracking-[0.2em] shadow-xl shadow-red-600/20 transition-all transform hover:scale-105 active:scale-95">
                            Beli Tiket Sekarang
                        </a>
                        @if($movie->trailer_url)
                        <a href="{{ $movie->trailer_url }}" target="_blank"
                           class="px-12 py-5 rounded-full bg-white/5 border border-white/10 hover:bg-white/10 text-white text-[11px] font-black uppercase tracking-[0.2em] transition-all">
                            Lihat Trailer
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        {{-- JADWAL TAYANG --}}
        <section id="jadwal" class="space-y-10">
            <div class="flex items-center justify-between">
                <h2 class="text-3xl font-black uppercase italic tracking-tighter text-white">Jadwal <span class="text-red-600">Tayang</span></h2>
                <div class="text-[10px] font-black text-zinc-500 uppercase tracking-[0.3em] bg-zinc-900 px-5 py-2 rounded-full border border-zinc-800">
                    LOKASI: {{ session('branch_name', 'Semua Cabang') }}
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($movie->showtimes->groupBy('studio.name') as $studioName => $showtimes)
                    <div class="bg-zinc-900/30 border border-white/5 p-8 rounded-[2.5rem] backdrop-blur-sm relative overflow-hidden group">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-red-600/5 blur-3xl -mr-16 -mt-16"></div>
                        
                        <div class="flex items-center justify-between mb-8 relative z-10">
                            <div>
                                <h3 class="font-black text-red-600 uppercase tracking-widest text-sm italic">{{ $studioName }}</h3>
                                <p class="text-[9px] font-bold text-zinc-500 uppercase tracking-widest mt-1">{{ $showtimes->first()->studio->type }} Class</p>
                            </div>
                            <span class="text-[9px] font-black text-zinc-500 italic uppercase">{{ $showtimes->first()->studio->branch->city }}</span>
                        </div>
                        
                        <div class="grid grid-cols-3 gap-3 relative z-10">
                            @foreach($showtimes as $st)
                                <a href="{{ route('movies.ticket', $movie->id) }}?time={{ $st->id }}" 
                                   class="p-4 rounded-2xl border border-white/5 bg-white/5 hover:bg-white hover:text-black transition-all text-center group/time shadow-lg">
                                    <div class="text-lg font-black leading-tight">{{ \Carbon\Carbon::parse($st->start_time)->format('H:i') }}</div>
                                    <div class="text-[9px] font-black text-zinc-500 group-hover/time:text-zinc-600 uppercase mt-1 italic">
                                        Rp {{ number_format($st->price, 0, ',', '.') }}
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-24 text-center border-2 border-dashed border-zinc-800 rounded-[3rem]">
                        <p class="text-zinc-600 font-black uppercase tracking-widest italic">Belum ada jadwal tayang untuk lokasi ini.</p>
                    </div>
                @endforelse
            </div>
        </section>
    </div>
</main>
@endsection