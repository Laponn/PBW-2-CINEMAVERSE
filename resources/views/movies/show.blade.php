@extends('layouts.app')

@section('content')
<main class="pt-24 pb-16 min-h-screen bg-[#050509] text-white">
    <div class="max-w-screen-2xl mx-auto px-6 space-y-20">

        {{-- TOMBOL KEMBALI --}}
        <a href="{{ route('home') }}" class="inline-flex items-center gap-4 text-[11px] font-black text-zinc-500 hover:text-red-500 transition-all group uppercase tracking-[0.3em]">
            <span class="w-10 h-10 rounded-full border border-white/10 bg-white/5 flex items-center justify-center group-hover:bg-red-600 group-hover:border-red-600 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M10 19l-7-7m0 0l7-7m-7 7h18" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </span>
            Katalog Film
        </a>

        {{-- HERO --}}
        <section class="perspective-wrap">
            <div class="relative overflow-hidden rounded-[4rem] border border-white/5 bg-zinc-900/10 backdrop-blur-xl shadow-2xl">
                <div class="relative p-10 md:p-16 grid lg:grid-cols-[380px,1fr] gap-16 items-center">

                    {{-- POSTER --}}
                    <div class="movie-tilt-card relative group mx-auto lg:mx-0 w-full max-w-[320px] aspect-[2/3] rounded-[2.5rem] overflow-hidden shadow-2xl border border-white/10 block">
                        <div class="glow"></div>
                        <img src="{{ $movie->poster_url }}"
                             alt="{{ $movie->title }}"
                             class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110 poster-wrap">
                    </div>

                    {{-- INFO --}}
                    <div class="space-y-8">
                        <div class="space-y-4">
                            <div class="flex items-center gap-4">
                                <span class="px-4 py-1.5 rounded-full bg-red-600/10 border border-red-600/20 text-[10px] font-black text-red-500 uppercase tracking-widest italic">
                                    Movie Profile
                                </span>
                                <span class="text-zinc-500 text-[11px] font-black uppercase tracking-[0.3em]">
                                    {{ \Carbon\Carbon::parse($movie->release_date)->format('Y') }}
                                </span>
                            </div>

                            <h1 class="text-4xl md:text-6xl font-black leading-[1] tracking-tighter italic uppercase text-white">
                                {{ $movie->title }}
                            </h1>

                            <div class="flex flex-wrap gap-3 pt-2">
                                <div class="px-5 py-2 rounded-2xl bg-white/5 border border-white/10 text-[10px] font-black text-zinc-300 uppercase italic">
                                    {{ $movie->duration_minutes }} MINS
                                </div>
                                <div class="px-5 py-2 rounded-2xl bg-red-600 text-[10px] font-black text-white uppercase italic">
                                    {{ $movie->genre }}
                                </div>
                            </div>
                        </div>

                        {{-- TEASER --}}
                        <p class="text-zinc-400 leading-relaxed max-w-3xl text-xl italic font-medium border-l-4 border-red-600 pl-6">
                            "{{ $movie->description }}"
                        </p>

                        <div class="flex flex-wrap items-center gap-6 pt-4">
                                <a href="{{ route('movies.ticket', $movie->id) }}"
                                   class="px-10 py-4 rounded-full bg-red-600 hover:bg-red-700 text-white text-[11px] font-black uppercase tracking-[0.2em] shadow-xl shadow-red-600/20 transition-all transform hover:scale-105 italic">
                                    Beli Tiket
                                </a>
                            @if($movie->trailer_url)
                                <button type="button" id="btnTrailer"
                                    data-trailer-url="{{ $movie->trailer_url }}"
                                    class="px-12 py-5 rounded-full bg-white/5 border border-white/10 hover:bg-white/10 text-white text-[11px] font-black uppercase tracking-[0.2em] transition-all italic">
                                    Trailer
                                </button>
                            @endif
                        </div>
                        
                    </div>

                </div>
            </div>
        </section>
        {{-- SECTION: DESKRIPSI STUDIO VIP & REGULER --}}
        @php
            $vipShowtimes = $movie->showtimes->filter(function($st) {
                $type = strtoupper($st->studio->type ?? '');
                return str_contains($type, 'VIP');
            });

            $regularShowtimes = $movie->showtimes->reject(function($st) {
                $type = strtoupper($st->studio->type ?? '');
                return str_contains($type, 'VIP');
            });

            $vipMinPrice = $vipShowtimes->isNotEmpty() ? $vipShowtimes->min('price') : null;
            $regMinPrice = $regularShowtimes->isNotEmpty() ? $regularShowtimes->min('price') : null;

            $hasVip = $vipShowtimes->isNotEmpty();
            $hasReg = $regularShowtimes->isNotEmpty();
        @endphp

        <section class="space-y-10">
            <div class="flex items-end justify-between border-b border-white/5 pb-8">
                <h2 class="text-3xl font-black uppercase italic tracking-tighter text-white">
                    Studio <span class="text-red-600">VIP</span> & <span class="text-red-600">Reguler</span>
                </h2>
                <span class="text-[10px] font-black text-zinc-500 uppercase tracking-widest italic">
                    Cabang: {{ session('branch_name', 'Semua Lokasi') }}
                </span>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 perspective-wrap">
                {{-- REGULER --}}
                <div class="movie-tilt-card relative overflow-hidden rounded-[3rem] border border-white/5 bg-zinc-900/40 backdrop-blur-md p-10">
                    <div class="glow"></div>
                    <div class="info-wrap space-y-6">
                        <div class="flex items-start justify-between gap-6">
                            <div>
                                <h3 class="text-red-600 font-black uppercase tracking-widest italic text-sm">Studio Reguler</h3>
                                <p class="text-[10px] font-black text-zinc-500 uppercase tracking-[0.25em] mt-2">
                                    Nyaman • Terjangkau • Cocok untuk semua penonton
                                </p>
                            </div>
                            <span class="px-4 py-2 rounded-full text-[9px] font-black uppercase tracking-widest italic border bg-white/5 text-zinc-200 border-white/10">
                                REGULER
                            </span>
                        </div>

                        <p class="text-zinc-400 leading-relaxed text-sm">
                            Kursi standar nyaman dengan pengalaman menonton yang solid untuk semua kalangan.
                        </p>

                        <ul class="text-[11px] text-zinc-400 space-y-2 font-semibold">
                            <li>• Kursi standar ergonomis</li>
                            <li>• Cocok untuk nonton rame-rame</li>
                            <li>• Harga lebih terjangkau</li>
                        </ul>

                        <div class="flex flex-wrap items-center gap-4 pt-2">
                            <span class="text-[10px] font-black uppercase tracking-widest {{ $hasReg ? 'text-zinc-300' : 'text-zinc-600' }}">
                                Status: {{ $hasReg ? 'Tersedia' : 'Belum tersedia di lokasi ini' }}
                            </span>
                            @if($regMinPrice !== null)
                                <span class="text-[10px] font-black uppercase tracking-widest text-zinc-300">
                                    Mulai Rp {{ number_format($regMinPrice, 0, ',', '.') }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- VIP --}}
                <div class="movie-tilt-card relative overflow-hidden rounded-[3rem] border border-red-600/20 bg-zinc-900/40 backdrop-blur-md p-10">
                    <div class="glow"></div>
                    <div class="info-wrap space-y-6">
                        <div class="flex items-start justify-between gap-6">
                            <div>
                                <h3 class="text-red-600 font-black uppercase tracking-widest italic text-sm">Studio VIP</h3>
                                <p class="text-[10px] font-black text-zinc-500 uppercase tracking-[0.25em] mt-2">
                                    Premium • Recliner • Lebih eksklusif
                                </p>
                            </div>
                            <span class="px-4 py-2 rounded-full text-[9px] font-black uppercase tracking-widest italic border bg-red-600/20 text-red-300 border-red-600/30">
                                VIP
                            </span>
                        </div>

                        <p class="text-zinc-400 leading-relaxed text-sm">
                            Pengalaman menonton lebih premium dengan kenyamanan ekstra dan suasana lebih eksklusif.
                        </p>

                        <ul class="text-[11px] text-zinc-400 space-y-2 font-semibold">
                            <li>• Kursi recliner premium</li>
                            <li>• Ruang kaki lebih luas</li>
                            <li>• Suasana lebih eksklusif</li>
                        </ul>

                        <div class="flex flex-wrap items-center gap-4 pt-2">
                            <span class="text-[10px] font-black uppercase tracking-widest {{ $hasVip ? 'text-zinc-300' : 'text-zinc-600' }}">
                                Status: {{ $hasVip ? 'Tersedia' : 'Belum tersedia di lokasi ini' }}
                            </span>
                            @if($vipMinPrice !== null)
                                <span class="text-[10px] font-black uppercase tracking-widest text-zinc-300">
                                    Mulai Rp {{ number_format($vipMinPrice, 0, ',', '.') }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>

    {{-- ✅ MODAL TRAILER (Popup seperti TIX ID) --}}
    <div id="trailerModal" class="hidden fixed inset-0 z-[9999] items-center justify-center p-4">
        {{-- overlay --}}
        <div id="trailerOverlay" class="absolute inset-0 bg-black/80 backdrop-blur-sm"></div>

        {{-- modal --}}
        <div class="relative w-full max-w-5xl overflow-hidden rounded-[2.5rem] border border-white/10 bg-zinc-950/80 shadow-2xl">
            <div class="flex items-center justify-between px-6 py-5 border-b border-white/10">
                <div class="text-[10px] font-black uppercase tracking-[0.35em] text-zinc-400 italic">
                    Trailer Player
                </div>
                <button type="button" id="btnCloseTrailer"
                    class="px-4 py-2 rounded-full bg-white/5 border border-white/10 hover:bg-white/10 text-[10px] font-black uppercase tracking-widest italic">
                    Tutup
                </button>
            </div>

            <div class="relative w-full aspect-video bg-black">
                <iframe
                    id="trailerFrame"
                    class="absolute inset-0 w-full h-full"
                    src=""
                    title="Trailer"
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                    allowfullscreen
                ></iframe>
            </div>
        </div>
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

    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(16px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .fadeUp { animation: fadeUp .35s ease-out; }
</style>

<script>
    function toEmbed(url) {
        if (!url) return '';

        // sudah embed
        if (url.includes('youtube.com/embed/')) {
            return url.includes('?')
                ? url + '&autoplay=1&rel=0&modestbranding=1'
                : url + '?autoplay=1&rel=0&modestbranding=1';
        }

        let id = null;

        try {
            const u = new URL(url);

            // youtu.be/<id>
            if (u.hostname.includes('youtu.be')) {
                id = u.pathname.split('/').filter(Boolean)[0] || null;
            }

            // youtube.com/watch?v=<id>
            if (!id && (u.hostname.includes('youtube.com') || u.hostname.includes('m.youtube.com'))) {
                id = u.searchParams.get('v') || null;

                // youtube.com/shorts/<id>
                if (!id && u.pathname.startsWith('/shorts/')) {
                    id = u.pathname.split('/')[2] || null;
                }

                // youtube.com/embed/<id>
                if (!id && u.pathname.startsWith('/embed/')) {
                    id = u.pathname.split('/')[2] || null;
                }
            }
        } catch (e) {
            const m = String(url).match(/(?:youtu\.be\/|v=|embed\/|shorts\/)([A-Za-z0-9_-]{6,})/);
            if (m) id = m[1];
        }

        if (id) return `https://www.youtube.com/embed/${id}?autoplay=1&rel=0&modestbranding=1`;

        // fallback non-youtube (kalau kamu pakai mp4 direct)
        return url;
    }

    function initMovieDetailPage() {
        // ==== Synopsis Toggle ====
        const synopsisBtn = document.getElementById('btnSynopsis');
        const synopsisSection = document.getElementById('synopsisSection');
        const closeSynopsisBtn = document.getElementById('btnCloseSynopsis');

        if (synopsisBtn && !synopsisBtn.dataset.bound) {
            synopsisBtn.dataset.bound = "1";
            synopsisBtn.addEventListener('click', () => {
                if (!synopsisSection) return;
                synopsisSection.classList.remove('hidden');
                synopsisSection.classList.add('fadeUp');
                setTimeout(() => synopsisSection.classList.remove('fadeUp'), 400);

                const target = document.getElementById('synopsis-card');
                if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
        }

        if (closeSynopsisBtn && !closeSynopsisBtn.dataset.bound) {
            closeSynopsisBtn.dataset.bound = "1";
            closeSynopsisBtn.addEventListener('click', () => {
                if (!synopsisSection) return;
                synopsisSection.classList.add('hidden');
            });
        }

        // ==== Trailer Modal ====
        const trailerModal = document.getElementById('trailerModal');
        const trailerFrame = document.getElementById('trailerFrame');
        const trailerOverlay = document.getElementById('trailerOverlay');
        const closeTrailerBtn = document.getElementById('btnCloseTrailer');

        function openTrailer(url) {
            if (!trailerModal || !trailerFrame) return;
            const src = toEmbed(url);
            if (!src) return;

            trailerFrame.src = src;
            trailerModal.classList.remove('hidden');
            trailerModal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeTrailer() {
            if (!trailerModal || !trailerFrame) return;
            trailerFrame.src = '';
            trailerModal.classList.add('hidden');
            trailerModal.classList.remove('flex');
            document.body.style.overflow = '';
        }

        const trailerBtn = document.getElementById('btnTrailer');
        if (trailerBtn && !trailerBtn.dataset.bound) {
            trailerBtn.dataset.bound = "1";
            trailerBtn.addEventListener('click', () => openTrailer(trailerBtn.dataset.trailerUrl));
        }

        const trailerInside = document.getElementById('btnTrailerInside');
        if (trailerInside && !trailerInside.dataset.bound) {
            trailerInside.dataset.bound = "1";
            trailerInside.addEventListener('click', () => openTrailer(trailerInside.dataset.trailerUrl));
        }

        if (trailerOverlay && !trailerOverlay.dataset.bound) {
            trailerOverlay.dataset.bound = "1";
            trailerOverlay.addEventListener('click', closeTrailer);
        }

        if (closeTrailerBtn && !closeTrailerBtn.dataset.bound) {
            closeTrailerBtn.dataset.bound = "1";
            closeTrailerBtn.addEventListener('click', closeTrailer);
        }

        // ESC close (bind sekali saja)
        if (!window.__movieTrailerEscBound) {
            window.__movieTrailerEscBound = true;
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') closeTrailer();
            });
        }

        // ==== Tilt 3D ====
        document.querySelectorAll('.movie-tilt-card').forEach(card => {
            if (card.dataset.tiltBound) return;
            card.dataset.tiltBound = "1";

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
    }

    document.addEventListener('DOMContentLoaded', initMovieDetailPage);
    document.addEventListener('turbo:load', initMovieDetailPage);
</script>
@endsection
