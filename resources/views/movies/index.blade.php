@extends('layouts.app')

@section('content')
@php
    // Helper untuk ambil ID YouTube dari URL
    if (!function_exists('getYoutubeId')) {
        function getYoutubeId($url) {
            preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match);
            return $match[1] ?? null;
        }
    }
    $featuredMovies = $movies->take(5);
@endphp

<main class="min-h-screen bg-[#050509] text-white">

    {{-- NETFLIX-STYLE HERO VIDEO SLIDER --}}
    <section class="relative h-[85vh] w-full overflow-hidden border-b border-white/5">
        <div id="heroSlider" class="relative h-full w-full">
            
            @foreach($featuredMovies as $index => $fMovie)
                @php $videoId = getYoutubeId($fMovie->trailer_url); @endphp
                
                <div class="hero-slide absolute inset-0 opacity-0 transition-opacity duration-1000 ease-in-out {{ $index == 0 ? 'active-slide opacity-100' : '' }}" data-index="{{ $index }}">
                    
                    <div class="absolute inset-0 z-0 bg-black">
                        {{-- Fallback Poster --}}
                        <img src="{{ $fMovie->poster_url }}" class="h-full w-full object-cover object-top opacity-50" alt="{{ $fMovie->title }}">
                        
                        {{-- Video Layer --}}
                        @if($videoId)
                            <div class="absolute inset-0 pointer-events-none">
                                <iframe 
                                    id="yt-player-{{ $index }}"
                                    class="yt-video-frame absolute top-1/2 left-1/2 w-[115%] h-[115%] -translate-x-1/2 -translate-y-1/2 opacity-60"
                                    src="https://www.youtube.com/embed/{{ $videoId }}?enablejsapi=1&mute=1&controls=0&loop=1&playlist={{ $videoId }}&rel=0&showinfo=0&modestbranding=1&iv_load_policy=3" 
                                    frameborder="0" 
                                    allow="autoplay; encrypted-media">
                                </iframe>
                            </div>
                        @endif

                        {{-- Netflix Gradients --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-[#050509] via-transparent to-black/40"></div>
                        <div class="absolute inset-0 bg-gradient-to-r from-[#050509] via-[#050509]/40 to-transparent"></div>
                    </div>

                    {{-- Hero Content --}}
                    <div class="relative z-10 flex h-full items-center px-6 md:px-16 lg:px-24">
                        <div class="max-w-3xl space-y-6">
                            <div class="flex items-center gap-3">
                                <span class="bg-red-600 px-3 py-1 text-[10px] font-black uppercase tracking-widest italic shadow-lg shadow-red-600/50">Featured Movie</span>
                                <span class="text-xs font-bold text-zinc-400 uppercase tracking-widest">{{ $fMovie->genre }} • {{ $fMovie->duration_minutes }} Mins</span>
                            </div>
                            
                            <h2 class="hero-title text-6xl md:text-8xl font-black italic uppercase leading-[0.85] tracking-tighter text-white">
                                {{ $fMovie->title }}
                            </h2>

                            <p class="max-w-xl text-lg font-medium italic leading-relaxed text-zinc-300 line-clamp-3">
                                "{{ $fMovie->description }}"
                            </p>

                            <div class="flex items-center gap-4 pt-6">
                                <a href="{{ route('movies.show', $fMovie->id) }}" class="flex items-center gap-3 bg-white px-10 py-4 text-[11px] font-black uppercase tracking-[0.2em] text-black transition hover:bg-red-600 hover:text-white shadow-xl">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                    Beli Tiket
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            {{-- Slider Navigation --}}
            <div class="absolute bottom-12 right-6 md:right-16 z-30 flex flex-col gap-4">
                @foreach($featuredMovies as $index => $fMovie)
                    <button class="slider-dot group flex items-center gap-4 text-right" data-go="{{ $index }}">
                        <span class="text-[9px] font-black text-white opacity-0 group-hover:opacity-100 transition-opacity uppercase tracking-widest">{{ $fMovie->title }}</span>
                        <div class="dot-bar h-1 w-8 bg-white/20 transition-all duration-500 {{ $index == 0 ? 'bg-red-600 w-16' : '' }}"></div>
                    </button>
                @endforeach
            </div>
        </div>
    </section>

    {{-- GRID MOVIES --}}
    <div class="max-w-screen-2xl mx-auto px-6 space-y-12 py-16">
        <section class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-end border-b border-white/5 pb-12">
            <div class="space-y-4">
                <span class="inline-flex items-center px-4 py-2 rounded-full bg-red-600/10 border border-red-600/20 text-[10px] font-black text-red-500 uppercase tracking-widest">CinemaVerse Catalog</span>
                <h2 class="text-4xl md:text-6xl font-black tracking-tighter italic uppercase text-white">Explore <span class="text-red-600">Now Playing</span></h2>
            </div>
            {{-- Search & Tabs --}}
            <div class="space-y-4 lg:justify-self-end w-full lg:w-[560px]">
                <div class="flex items-center justify-end">
                    <div class="inline-flex items-center p-2 rounded-full bg-white/5 border border-white/10">
                        <a href="{{ route('home') }}" class="px-6 py-3 rounded-full bg-red-600 text-white text-[10px] font-black uppercase tracking-[0.25em] shadow-lg shadow-red-600/30">Now Showing</a>
                        <a href="{{ route('movies.comingSoon') }}" class="ml-2 px-6 py-3 rounded-full text-zinc-400 text-[10px] font-black uppercase tracking-[0.25em] hover:text-white transition">Coming Soon</a>
                    </div>
                </div>
                <form action="{{ route('movie.search') }}" method="GET" class="w-full">
                    <div class="flex items-center gap-3">
                        <input type="text" name="q" class="flex-1 px-6 py-4 rounded-full bg-white/5 border border-white/10 text-white placeholder-zinc-600 focus:outline-none focus:ring-2 focus:ring-red-600/50" placeholder="Cari film favoritmu...">
                        <button class="px-8 py-4 rounded-full bg-red-600 text-white text-[11px] font-black uppercase tracking-[0.2em] hover:bg-red-700 transition">Cari</button>
                    </div>
                </form>
            </div>
        </section>

        {{-- 3D TILT GRID DENGAN DETAIL --}}
        <section class="perspective-wrap">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10">
                @foreach($movies as $movie)
                    <a href="{{ route('movies.show', $movie->id) }}" class="movie-tilt-card group relative bg-zinc-900/40 border border-white/5 rounded-[2.5rem] overflow-hidden backdrop-blur-sm transition-all shadow-2xl block">
                        <div class="glow"></div>
                        
                        {{-- Poster --}}
                        <div class="relative overflow-hidden z-10 poster-wrap">
                            <div class="absolute inset-0 bg-gradient-to-t from-[#050509] via-transparent to-transparent opacity-80"></div>
                            <img src="{{ $movie->poster_url }}" class="w-full h-[420px] object-cover transition-transform duration-700 group-hover:scale-110" alt="{{ $movie->title }}">
                            
                            {{-- Durasi (Hitam on White) --}}
                            <div class="absolute top-5 left-5 px-4 py-2 rounded-xl bg-white text-[9px] font-black text-black uppercase italic z-20 shadow-lg">
                                {{ $movie->duration_minutes }} mins
                            </div>
                            
                            {{-- Genre (Red Badge) --}}
                            <div class="absolute top-5 right-5 px-4 py-2 rounded-xl bg-red-600 text-[9px] font-black text-white uppercase italic z-20 shadow-lg shadow-red-600/40">
                                {{ $movie->genre }}
                            </div>
                        </div>

                        {{-- Content --}}
                        <div class="p-8 space-y-4 relative z-20 info-wrap">
                            <div class="text-[9px] font-black uppercase tracking-[0.3em] text-red-500">
                                Release: {{ \Carbon\Carbon::parse($movie->release_date)->format('d M Y') }}
                            </div>
                            <h3 class="text-2xl font-black tracking-tighter italic uppercase text-white leading-tight group-hover:text-red-500 transition-colors">
                                {{ $movie->title }}
                            </h3>
                            <p class="text-zinc-500 text-xs leading-relaxed font-medium italic line-clamp-2">"{{ \Illuminate\Support\Str::limit($movie->description, 80) }}"</p>
                            
                            <div class="pt-4 flex items-center justify-between">
                                <span class="text-[10px] font-black text-white/30 uppercase tracking-widest group-hover:text-white">Get Tickets</span>
                                <div class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center group-hover:bg-red-600 transition-all duration-500">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    </div>
</main>

<style>
    .perspective-wrap { perspective: 2000px; }
    .movie-tilt-card { transform-style: preserve-3d; transition: transform 0.2s ease-out; }
    .movie-tilt-card .glow {
        position: absolute; inset: -20%; pointer-events: none; z-index: 5;
        background: radial-gradient(circle at var(--gx, 50%) var(--gy, 50%), rgba(220, 38, 38, 0.15) 0%, transparent 60%)
        pointer-events: none;;
    }
    .poster-wrap { transform: translateZ(30px) ; }
    .info-wrap { transform: translateZ(60px); }
    .hero-slide iframe { min-width: 100%; min-height: 100%; aspect-ratio: 16/9; pointer-events: none; }
</style>

{{-- JAVASCRIPT --}}
<script>
    var players = {};
    var currentSlide = 0;
    var slides = document.querySelectorAll('.hero-slide');
    var dots = document.querySelectorAll('.slider-dot');
    var slideInterval;

    var tag = document.createElement('script');
    tag.src = "https://www.youtube.com/iframe_api";
    var firstScriptTag = document.getElementsByTagName('script')[0];
    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

    window.onYouTubeIframeAPIReady = function() {
        document.querySelectorAll('.yt-video-frame').forEach((iframe, index) => {
            const playerId = iframe.id;
            players[index] = new YT.Player(playerId, {
                events: {
                    'onReady': (event) => {
                        event.target.mute();
                        if (index === 0) event.target.playVideo();
                    },
                    'onStateChange': (event) => {
                        if (event.data === YT.PlayerState.ENDED) event.target.playVideo();
                    }
                }
            });
        });
        startAutoSlide();
    };

    function showSlide(index) {
        if (index === currentSlide) return;
        if (players[currentSlide] && players[currentSlide].pauseVideo) players[currentSlide].pauseVideo();

        slides.forEach(s => s.classList.remove('active-slide', 'opacity-100'));
        slides[index].classList.add('active-slide', 'opacity-100');
        
        dots.forEach(d => {
            const bar = d.querySelector('.dot-bar');
            bar.classList.remove('bg-red-600', 'w-16');
            bar.classList.add('bg-white/20', 'w-8');
        });
        const activeBar = dots[index].querySelector('.dot-bar');
        activeBar.classList.remove('bg-white/20', 'w-8');
        activeBar.classList.add('bg-red-600', 'w-16');

        if (players[index] && players[index].playVideo) players[index].playVideo();
        currentSlide = index;
    }

    function startAutoSlide() {
        if(slideInterval) clearInterval(slideInterval);
        slideInterval = setInterval(() => {
            showSlide((currentSlide + 1) % slides.length);
        }, 10000);
    }

    dots.forEach(dot => {
        dot.addEventListener('click', () => {
            clearInterval(slideInterval);
            showSlide(parseInt(dot.dataset.go));
            startAutoSlide();
        });
    });

    document.querySelectorAll('.movie-tilt-card').forEach(card => {
        if (window.matchMedia('(pointer: coarse)').matches) return;
        card.addEventListener('mousemove', e => {
            const rect = card.getBoundingClientRect();
            const x = (e.clientX - rect.left) / rect.width;
            const y = (e.clientY - rect.top) / rect.height;
            card.style.transform = `rotateX(${-(y - 0.5) * 12}deg) rotateY(${(x - 0.5) * 12}deg) scale(1.03)`;
            card.style.setProperty('--gx', `${x * 100}%`);
            card.style.setProperty('--gy', `${y * 100}%`);
        });
        card.addEventListener('mouseleave', () => card.style.transform = 'rotateX(0deg) rotateY(0deg) scale(1)');
    });
</script>
@endsection