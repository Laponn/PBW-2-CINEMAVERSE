@extends('layouts.app')

@section('content')
<div class="w-full space-y-20 pb-20">

    {{-- HERO SECTION --}}
    @php $featured = $movies->first(); @endphp
    @if($featured)
        <section class="relative w-full h-[550px] md:h-[750px] flex items-center overflow-hidden">
            <div class="absolute inset-0 z-0">
                {{-- Smart Image Logic --}}
                <img src="{{ str_starts_with($featured->poster_url, 'http') ? $featured->poster_url : asset($featured->poster_url) }}" 
                     class="w-full h-full object-cover opacity-40 blur-md">
                <div class="absolute inset-0 bg-gradient-to-t from-[#050509] via-[#050509]/40 to-transparent"></div>
                <div class="absolute inset-0 bg-gradient-to-r from-[#050509] via-transparent to-[#050509]"></div>
            </div>

            <div class="relative z-10 max-w-screen-2xl mx-auto px-6 w-full flex flex-col md:flex-row items-center gap-12">
                <div class="hidden md:block w-72 lg:w-80 flex-shrink-0">
                    <img src="{{ str_starts_with($featured->poster_url, 'http') ? $featured->poster_url : asset($featured->poster_url) }}" 
                         class="w-full rounded-2xl shadow-2xl border border-white/10 transform -rotate-2">
                </div>

                <div class="flex-1 text-center md:text-left">
                    <span class="inline-block px-4 py-1 rounded-full bg-red-600 text-[10px] font-black uppercase tracking-[0.3em] mb-6 animate-pulse">
                        Featured Movie
                    </span>
                    <h1 class="text-5xl md:text-8xl font-black tracking-tighter leading-none mb-6 italic uppercase">
                        {{ $featured->title }}
                    </h1>
                    <p class="text-zinc-400 text-lg md:text-xl max-w-2xl mb-8 leading-relaxed italic">
                        "{{ Str::limit($featured->description, 180) }}"
                    </p>
                    
                    <div class="flex flex-col sm:flex-row items-center gap-4 justify-center md:justify-start">
                        <a href="{{ route('movies.show', $featured->id) }}" 
                           class="inline-flex items-center justify-center px-10 py-4 bg-red-600 hover:bg-red-700 text-white font-black rounded-full transition transform hover:scale-105 shadow-[0_0_30px_rgba(220,38,38,0.4)] uppercase tracking-widest text-xs">
                            Beli Tiket Sekarang
                        </a>
                        @if($featured->trailer_url)
                        <a href="{{ $featured->trailer_url }}" target="_blank"
                           class="inline-flex items-center justify-center px-10 py-4 bg-white/10 hover:bg-white/20 text-white font-black rounded-full transition border border-white/10 uppercase tracking-widest text-xs">
                            Lihat Trailer
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- NOW SHOWING --}}
    <section class="max-w-screen-2xl mx-auto px-6">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 border-b border-zinc-800 pb-8 gap-4">
            <div>
                <h2 class="text-4xl font-black tracking-tighter uppercase italic">
                    NOW <span class="text-red-600">SHOWING</span>
                </h2>
                <p class="text-zinc-500 text-sm mt-2 font-medium">Daftar film blockbuster terbaik minggu ini</p>
            </div>
            <div class="flex items-center gap-3 bg-zinc-900 px-6 py-3 rounded-full border border-zinc-800">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                </span>
                <span class="text-[10px] font-black text-zinc-300 uppercase tracking-widest">
                    Lokasi: {{ session('branch_name', 'Semua Cabang') }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-10">
            @forelse($movies as $movie)
                <div class="group cursor-pointer" onclick="window.location='{{ route('movies.show', $movie->id) }}'">
                    <div class="relative aspect-[2/3] rounded-3xl overflow-hidden border border-zinc-800 transition-all duration-500 group-hover:border-red-600/50 group-hover:shadow-[0_0_40px_rgba(220,38,38,0.15)]">
                      <img src="{{ str_starts_with($movie->poster_url, 'http') ? $movie->poster_url : asset($movie->poster_url) }}" 
     alt="{{ $movie->title }}" 
     onerror="this.src='https://via.placeholder.com/500x750?text=No+Poster+Available'"
     class="w-full h-full object-cover">
     
                        <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-80"></div>
                        
                        <div class="absolute top-4 right-4">
                            <span class="bg-black/60 backdrop-blur-md px-3 py-1 rounded-lg border border-white/10 text-[9px] font-black text-white uppercase tracking-widest">
                                {{ $movie->duration_minutes }} Min
                            </span>
                        </div>
                    </div>
                    <div class="mt-6 space-y-2">
                        <h3 class="font-black text-white text-lg tracking-tight leading-tight group-hover:text-red-500 transition-colors uppercase italic">
                            {{ $movie->title }}
                        </h3>
                        <p class="text-[9px] text-zinc-500 uppercase tracking-[0.2em] font-black">{{ $movie->genre }}</p>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-24 text-center border-2 border-dashed border-zinc-800 rounded-[3rem]">
                    <p class="text-zinc-500 font-bold uppercase tracking-widest italic">Belum ada film yang tayang di lokasi ini.</p>
                </div>
            @endforelse
        </div>
    </section>
</div>
@endsection