@extends('layouts.app')

@section('content')
<main class="pt-24 pb-16 min-h-screen bg-[#050509]">
    <div class="max-w-screen-2xl mx-auto px-6 space-y-12">

        {{-- HEADER (RAPIH) --}}
        <section class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-start">

            {{-- KIRI: TITLE --}}
            <div class="space-y-4">
                <span class="inline-flex items-center px-4 py-2 rounded-full bg-red-600/10 border border-red-600/20 text-[10px] font-black text-red-500 uppercase tracking-widest">
                    Now Showing
                </span>

                <h1 class="text-5xl md:text-7xl font-black leading-[0.9] tracking-tighter italic uppercase text-white">
                    Film <span class="text-red-600">Sedang</span><br class="hidden md:block"/>
                    <span class="text-red-600">Tayang</span>
                </h1>

                <p class="text-zinc-500 font-bold uppercase tracking-[0.2em] text-[10px]">
                    Pilih film untuk lihat detail & beli tiket
                </p>
            </div>

            {{-- KANAN: TAB + SEARCH --}}
            <div class="space-y-4 lg:justify-self-end w-full lg:w-[560px]">

                {{-- Tabs --}}
                <div class="flex items-center justify-end">
                    <div class="inline-flex items-center p-2 rounded-full bg-white/5 border border-white/10">
                        {{-- Active: Now Showing --}}
                        <a href="{{ route('home') }}"
                           class="px-6 py-3 rounded-full bg-red-600 text-white text-[10px] font-black uppercase tracking-[0.25em]
                                  shadow shadow-red-600/30">
                            Now Showing
                        </a>

                        {{-- Coming Soon --}}
                        <a href="{{ route('movies.comingSoon') }}"
                           class="ml-2 px-6 py-3 rounded-full text-zinc-200 text-[10px] font-black uppercase tracking-[0.25em]
                                  hover:bg-red-600 hover:text-white transition">
                            Coming Soon
                        </a>
                    </div>
                </div>

                {{-- Search --}}
                <form action="{{ route('movie.search') }}" method="GET" class="w-full">
                    <div class="flex items-center gap-3">
                        <div class="relative flex-1">
                            <input type="text" name="q" value="{{ $q ?? '' }}"
                                   class="w-full px-6 py-4 rounded-full bg-white/5 border border-white/10 text-white placeholder-zinc-500
                                          focus:outline-none focus:ring-2 focus:ring-red-600/50"
                                   placeholder="Cari judul film...">

                            <span class="absolute right-5 top-1/2 -translate-y-1/2 text-zinc-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M21 21l-4.35-4.35m1.6-5.1a7.5 7.5 0 11-15 0 7.5 7.5 0 0115 0z"
                                          stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                        </div>

                        <button type="submit"
                                class="px-8 py-4 rounded-full bg-red-600 hover:bg-red-700 text-white text-[11px] font-black uppercase tracking-[0.2em] transition">
                            Cari
                        </button>
                    </div>
                </form>
            </div>
        </section>

        {{-- GRID MOVIES --}}
        <section class="pt-2">
            @if($movies->isEmpty())
                <div class="py-24 text-center border-2 border-dashed border-zinc-800 rounded-[3rem]">
                    <p class="text-zinc-600 font-black uppercase tracking-widest italic">
                        Belum ada film yang sedang tayang.
                    </p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    @foreach($movies as $movie)
                        <a href="{{ route('movies.show', $movie->id) }}"
                           class="group bg-zinc-900/30 border border-white/5 rounded-[2.5rem] overflow-hidden backdrop-blur-sm hover:border-red-600/30 transition shadow-2xl">

                            {{-- Poster --}}
                            <div class="relative overflow-hidden">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent"></div>

                                <img
                                    src="{{ $movie->poster_url ? (str_starts_with($movie->poster_url, 'http') ? $movie->poster_url : asset($movie->poster_url)) : 'https://via.placeholder.com/500x750?text=Poster+Tidak+Tersedia' }}"
                                    alt="{{ $movie->title }}"
                                    class="w-full h-[420px] object-cover group-hover:scale-105 transition-transform duration-500"
                                    onerror="this.src='https://via.placeholder.com/500x750?text=Poster+Tidak+Tersedia'">

                                {{-- Durasi --}}
                                <div class="absolute top-5 left-5 px-4 py-2 rounded-full bg-white/5 border border-white/10 text-[10px] font-black tracking-widest text-white uppercase italic">
                                    {{ $movie->duration_minutes }} mins
                                </div>

                                {{-- Genre --}}
                                @if($movie->genre)
                                    <div class="absolute top-5 right-5 px-4 py-2 rounded-full bg-red-600/10 border border-red-600/20 text-[10px] font-black tracking-widest text-red-500 uppercase italic">
                                        {{ $movie->genre }}
                                    </div>
                                @endif
                            </div>

                            {{-- Content --}}
                            <div class="p-6 space-y-3">
                                <div class="text-[10px] font-black uppercase tracking-widest text-zinc-500">
                                    {{ \Carbon\Carbon::parse($movie->release_date)->format('d M Y') }}
                                </div>

                                <h3 class="text-xl font-black tracking-tighter italic uppercase text-white leading-tight">
                                    {{ $movie->title }}
                                </h3>

                                <p class="text-zinc-400 text-sm leading-relaxed font-medium italic">
                                    "{{ \Illuminate\Support\Str::limit($movie->description, 90) }}"
                                </p>

                                <div class="pt-2">
                                    <span class="inline-flex items-center gap-3 text-[10px] font-black text-zinc-400 group-hover:text-red-500 transition uppercase tracking-[0.2em]">
                                        Lihat Detail
                                        <span class="w-8 h-8 rounded-full border border-white/10 bg-white/5 flex items-center justify-center group-hover:bg-red-600 group-hover:border-red-600 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path d="M9 5l7 7-7 7" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </section>

    </div>
</main>
@endsection
