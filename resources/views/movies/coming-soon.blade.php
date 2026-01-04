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
    // Mengambil 5 film pertama untuk Slider
    $featuredComingSoon = $movies->take(5);
@endphp

<main class="min-h-screen bg-[#050509] text-white">

    {{-- 1. HERO VIDEO SLIDER (PERSIS INDEX) --}}
    <section class="relative h-[85vh] w-full overflow-hidden border-b border-white/5">
        <div id="heroSlider" class="relative h-full w-full">
            
            @foreach($featuredComingSoon as $index => $fMovie)
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

                        {{-- Netflix-style Gradients --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-[#050509] via-transparent to-black/40"></div>
                        <div class="absolute inset-0 bg-gradient-to-r from-[#050509] via-[#050509]/40 to-transparent"></div>
                    </div>

                    {{-- Hero Content --}}
                    <div class="relative z-10 flex h-full items-center px-6 md:px-16 lg:px-24">
                        <div class="max-w-3xl space-y-6">
                            <div class="flex items-center gap-3">
                                <span class="bg-red-600 px-3 py-1 text-[10px] font-black uppercase tracking-widest italic shadow-lg shadow-red-600/50">Upcoming Feature</span>
                                <span class="text-xs font-bold text-zinc-400 uppercase tracking-widest">{{ $fMovie->genre }} • Release: {{ \Carbon\Carbon::parse($fMovie->release_date)->format('d M Y') }}</span>
                            </div>
                            
                            <h2 class="hero-title text-6xl md:text-8xl font-black italic uppercase leading-[0.85] tracking-tighter text-white">
                                {{ $fMovie->title }}
                            </h2>

                            <p class="max-w-xl text-lg font-medium italic leading-relaxed text-zinc-300 line-clamp-3">
                                "{{ $fMovie->description }}"
                            </p>

                            <div class="flex items-center gap-4 pt-6">
                                <a href="{{ $fMovie->trailer_url }}" target="_blank" class="flex items-center gap-3 bg-white px-10 py-4 text-[11px] font-black uppercase tracking-[0.2em] text-black transition hover:bg-red-600 hover:text-white shadow-xl">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                    Watch Trailer
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            {{-- Slider Navigation Dots --}}
            <div class="absolute bottom-12 right-6 md:right-16 z-30 flex flex-col gap-4">
                @foreach($featuredComingSoon as $index => $fMovie)
                    <button class="slider-dot group flex items-center gap-4 text-right" data-go="{{ $index }}">
                        <span class="text-[9px] font-black text-white opacity-0 group-hover:opacity-100 transition-opacity uppercase tracking-widest">{{ $fMovie->title }}</span>
                        <div class="dot-bar h-1 w-8 bg-white/20 transition-all duration-500 {{ $index == 0 ? 'bg-red-600 w-16' : '' }}"></div>
                    </button>
                @endforeach
            </div>
        </div>
    </section>

    {{-- 2. GRID CONTENT --}}
    <div class="max-w-screen-2xl mx-auto px-6 space-y-12 py-16">
        
        {{-- HEADER (DENGAN TABS ANIMASI) --}}
        <section class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-end border-b border-white/5 pb-12">
            <div class="space-y-4">
                <span class="inline-flex items-center px-4 py-2 rounded-full bg-red-600/10 border border-red-600/20 text-[10px] font-black text-red-500 uppercase tracking-widest">Coming Soon</span>
                <h2 class="text-4xl md:text-6xl font-black tracking-tighter italic uppercase text-white">Future <span class="text-red-600">Releases</span></h2>
            </div>

            <div class="space-y-4 lg:justify-self-end w-full lg:w-[560px]">
                {{-- TABS DENGAN PILL BERGESER KE KANAN --}}
                <div class="flex items-center justify-end">
                    <div class="relative inline-flex items-center p-1.5 rounded-full bg-white/5 border border-white/10">
                        {{-- Pill Merah di Kanan (Coming Soon Active) --}}
                        <div class="absolute inset-y-1.5 right-1.5 w-[130px] bg-red-600 rounded-full shadow-lg shadow-red-600/40 transition-all duration-500"></div>
                        
                        <a href="{{ route('home') }}" class="relative z-10 px-8 py-3 rounded-full text-zinc-400 text-[10px] font-black uppercase tracking-[0.25em] hover:text-white transition-colors duration-300">
                            Now Showing
                        </a>
                        <span class="relative z-10 px-8 py-3 rounded-full text-white text-[10px] font-black uppercase tracking-[0.25em] w-[130px] text-center">
                            Coming Soon
                        </span>
                    </div>
                </div>

                {{-- Search --}}
                <form action="{{ route('movies.comingSoon') }}" method="GET" class="w-full">
                    <div class="flex items-center gap-3">
                        <input type="text" name="q" value="{{ request('q') }}" class="flex-1 px-6 py-4 rounded-full bg-white/5 border border-white/10 text-white placeholder-zinc-600 focus:outline-none focus:ring-2 focus:ring-red-600/50 transition-all" placeholder="Cari film yang akan datang...">
                        <button class="px-8 py-4 rounded-full bg-red-600 text-white text-[11px] font-black uppercase tracking-[0.2em] hover:bg-red-700 transition shadow-lg shadow-red-600/20">Cari</button>
                    </div>
                </form>
            </div>
        </section>

        {{-- 3D TILT GRID --}}
        <section class="perspective-wrap">
            @if($movies->isEmpty())
                <div class="py-24 text-center border-2 border-dashed border-zinc-800 rounded-[3rem]">
                    <p class="text-zinc-600 font-black uppercase tracking-widest italic">Belum ada film yang dijadwalkan.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10">
                    @foreach($movies as $movie)
                        <div class="movie-tilt-card group relative bg-zinc-900/40 border border-white/5 rounded-[2.5rem] overflow-hidden backdrop-blur-sm transition-all shadow-2xl block">
                            <div class="glow"></div>
                            
                            <div class="relative overflow-hidden z-10 poster-wrap">
                                <div class="absolute inset-0 bg-gradient-to-t from-[#050509] via-transparent to-transparent opacity-80"></div>
                                <img src="{{ $movie->poster_url }}" class="w-full h-[420px] object-cover transition-transform duration-700 group-hover:scale-110" alt="{{ $movie->title }}">
                                
                                <div class="absolute top-5 left-5 px-4 py-2 rounded-xl bg-white text-[9px] font-black text-black uppercase italic z-20 shadow-lg">
                                    Release: {{ \Carbon\Carbon::parse($movie->release_date)->format('d M Y') }}
                                </div>
                                <div class="absolute top-5 right-5 px-4 py-2 rounded-xl bg-red-600 text-[9px] font-black text-white uppercase italic z-20 shadow-lg shadow-red-600/40">{{ $movie->genre }}</div>
                            </div>

                            <div class="p-8 space-y-4 relative z-20 info-wrap">
                                <h3 class="text-2xl font-black tracking-tighter italic uppercase text-white leading-tight group-hover:text-red-500 transition-colors">{{ $movie->title }}</h3>
                                <p class="text-zinc-500 text-xs leading-relaxed font-medium italic line-clamp-2">"{{ \Illuminate\Support\Str::limit($movie->description, 100) }}"</p>
                                
                                <div class="pt-4">
                                    @if($movie->trailer_url)
                                        <a href="{{ $movie->trailer_url }}" target="_blank" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-white/5 border border-white/10 hover:bg-red-600 hover:border-red-600 text-white text-[10px] font-black uppercase tracking-[0.2em] transition-all group/btn">
                                            <svg class="w-4 h-4 transition-transform group-hover/btn:scale-125" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                            Watch Trailer
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>
    </div>
</main>

{{-- STYLES --}}
<style>
    .perspective-wrap { perspective: 2000px; }
    .movie-tilt-card { transform-style: preserve-3d; transition: transform 0.2s ease-out; }
    .movie-tilt-card .glow {
        position: absolute; inset: -20%; pointer-events: none; z-index: 5;
        background: radial-gradient(circle at var(--gx, 50%) var(--gy, 50%), rgba(220, 38, 38, 0.15) 0%, transparent 60%);
    }
    .poster-wrap { transform: translateZ(30px); }
    .info-wrap { transform: translateZ(60px); }
    .hero-slide iframe { min-width: 100%; min-height: 100%; aspect-ratio: 16/9; pointer-events: none; }
    .hero-title { text-shadow: 0 10px 40px rgba(0,0,0,0.8); }
</style>

{{-- SCRIPTS (YOUTUBE API + SLIDER + TILT) --}}
<script>
    var players = {};
    var currentSlide = 0;
    var slides = document.querySelectorAll('.hero-slide');
    var dots = document.querySelectorAll('.slider-dot');
    var slideInterval;

    // YouTube API Setup
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

    // 3D Tilt Logic
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