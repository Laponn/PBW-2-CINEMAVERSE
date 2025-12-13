<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $movie->title }} - CinemaVerse</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        @keyframes cvFadeInUp {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .cv-animate-in { animation: cvFadeInUp .22s ease-out; }
    </style>
</head>

<body class="bg-[#050509] text-white font-sans">

    {{-- NAVBAR (tema sama kayak Home) --}}
    <header class="fixed top-0 left-0 right-0 z-40 bg-black/80 backdrop-blur border-b border-red-900/40">
        <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-red-600 flex items-center justify-center shadow-lg shadow-red-600/40">
                    <span class="text-xs font-extrabold tracking-[0.2em]">CV</span>
                </div>
                <div>
                    <div class="text-xs tracking-[0.4em] text-red-500">CINEMA</div>
                    <div class="-mt-1 text-xl font-semibold tracking-[0.35em]">VERSE</div>
                </div>
            </div>

            <nav class="hidden md:flex items-center gap-8 text-sm font-medium">
                <a href="{{ route('home') }}" class="text-gray-300 hover:text-white">Home</a>
                <a href="#jadwal" class="text-red-400 hover:text-red-300">Tiket</a>
                <a href="#" class="text-gray-300 hover:text-white">Trending</a>
                <a href="#" class="text-gray-300 hover:text-white">Saved</a>
            </nav>

            <div class="flex items-center gap-3">
                <form action="{{ route('movie.search') }}" method="GET"
                      class="hidden sm:flex items-center bg-zinc-900 rounded-full px-3 py-1.5 text-xs border border-zinc-700 focus-within:border-red-500 transition">
                    <input type="text" name="q" placeholder="Cari judul film..."
                           class="bg-transparent border-0 focus:ring-0 text-xs placeholder:text-zinc-500 w-32 md:w-48">
                    <button type="submit"
                            class="ml-2 px-3 py-1 rounded-full bg-red-600 hover:bg-red-500 font-semibold">
                        Cari
                    </button>
                </form>

                @if (Route::has('login'))
                    <div class="flex items-center gap-2">
                        @auth
                            <a href="{{ url('/dashboard') }}"
                               class="hidden sm:inline-flex text-xs font-semibold border border-zinc-600 rounded-full px-4 py-1.5 hover:border-red-500 hover:text-red-400">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                               class="hidden sm:inline-flex text-xs font-semibold border border-zinc-600 rounded-full px-4 py-1.5 hover:border-red-500 hover:text-red-400">
                                Log in
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                   class="hidden sm:inline-flex text-xs font-semibold bg-red-600 rounded-full px-4 py-1.5 hover:bg-red-500 border border-transparent">
                                    Register
                                </a>
                            @endif
                        @endauth
                    </div>
                @endif
            </div>
        </div>
    </header>

    <main class="pt-24 pb-16">
        <div class="max-w-6xl mx-auto px-6 space-y-10">

            {{-- BACK --}}
            <a href="{{ route('home') }}"
               class="inline-flex items-center gap-2 text-sm text-zinc-300 hover:text-white transition">
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full border border-white/10 bg-white/5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </span>
                <span>Kembali ke Home</span>
            </a>

            @php
                $releaseYear = $movie->release_date ? \Carbon\Carbon::parse($movie->release_date)->format('Y') : null;
                $statusText  = $movie->status ? strtoupper(str_replace('_',' ', $movie->status)) : 'STATUS';
                $genreText   = $movie->genre ?? null;

                // Trailer: support ID atau full URL
                $trailerId = null;
                if (!empty($movie->trailer_url)) {
                    $raw = trim($movie->trailer_url);

                    // Kalau sudah ID youtube 11 char
                    if (preg_match('~^[a-zA-Z0-9_-]{11}$~', $raw)) {
                        $trailerId = $raw;
                    } else {
                        $parts = parse_url($raw);
                        if ($parts && isset($parts['host'])) {
                            // youtu.be/ID
                            if (str_contains($parts['host'], 'youtu.be')) {
                                $trailerId = ltrim($parts['path'] ?? '', '/');
                            } else {
                                // youtube.com/watch?v=ID
                                parse_str($parts['query'] ?? '', $q);
                                $trailerId = $q['v'] ?? null;
                            }
                        }
                    }
                }
            @endphp

            {{-- HERO DETAIL --}}
            <section class="relative overflow-hidden rounded-3xl border border-red-900/60 bg-gradient-to-r from-black via-zinc-900 to-black shadow-2xl shadow-red-900/30">
                {{-- glow background --}}
                <div class="absolute inset-0 opacity-30 pointer-events-none">
                    <div class="w-full h-full bg-[radial-gradient(circle_at_top,_#f97316_0,_transparent_55%),_radial-gradient(circle_at_bottom,_#ef4444_0,_transparent_55%)]"></div>
                </div>

                <div class="relative p-6 md:p-10 grid md:grid-cols-[280px,1fr] gap-8">
                    {{-- Poster --}}
                    <div class="flex justify-center md:justify-start">
                        <div class="relative w-64 md:w-[280px] group">
                            <div class="absolute -inset-1 bg-gradient-to-tr from-red-600 to-orange-500 rounded-2xl blur opacity-30 group-hover:opacity-60 transition duration-500"></div>
                            <img src="{{ $movie->poster_url }}"
                                 alt="{{ $movie->title }}"
                                 class="relative w-full rounded-2xl border border-zinc-800 shadow-2xl object-cover aspect-[2/3]">
                        </div>
                    </div>

                    {{-- Info --}}
                    <div class="space-y-6">
                        <div class="space-y-3">
                            <span class="inline-flex items-center text-xs font-semibold tracking-[0.3em] text-red-400 uppercase">
                                Now Showing
                                <span class="ml-2 h-px w-10 bg-red-500/60"></span>
                            </span>

                            <h1 class="text-3xl md:text-5xl font-extrabold leading-tight">
                                {{ $movie->title }}
                            </h1>

                            <div class="flex flex-wrap items-center gap-2 text-xs text-zinc-200">
                                <span class="px-4 py-2 rounded-full border border-white/10 bg-white/5 uppercase tracking-[0.2em] font-semibold">
                                    {{ $movie->duration_minutes }} Menit
                                </span>

                                <span class="px-4 py-2 rounded-full border border-green-500/40 bg-green-500/10 text-green-300 uppercase tracking-[0.2em] font-semibold">
                                    {{ $statusText }}
                                </span>

                                @if($releaseYear)
                                    <span class="px-4 py-2 rounded-full border border-white/10 bg-white/5 uppercase tracking-[0.2em] font-semibold">
                                        {{ $releaseYear }}
                                    </span>
                                @endif

                                @if($genreText)
                                    <span class="px-4 py-2 rounded-full border border-white/10 bg-white/5 uppercase tracking-[0.2em] font-semibold">
                                        {{ $genreText }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <p class="text-zinc-300 leading-relaxed">
                            {{ $movie->description }}
                        </p>

                        {{-- CTA --}}
                        <div class="flex flex-wrap items-center gap-3 pt-2">
                            <button id="btnToggleJadwal"
                                    type="button"
                                    aria-expanded="false"
                                    aria-controls="jadwalSection"
                                    class="inline-flex items-center justify-center px-8 py-3 rounded-full bg-red-600 hover:bg-red-500 text-sm font-bold shadow-lg shadow-red-700/40 transition hover:scale-[1.02]">
                                Beli Tiket
                            </button>

                            @if($trailerId)
                                <a href="#trailer"
                                   class="inline-flex items-center justify-center px-8 py-3 rounded-full border border-white/10 bg-white/5 hover:bg-white/10 text-sm font-semibold transition">
                                    Lihat Trailer
                                </a>
                            @endif
                        </div>

                        <div class="grid sm:grid-cols-3 gap-3 text-sm">
                            <div class="rounded-2xl border border-white/10 bg-black/40 p-4">
                                <div class="text-xs text-zinc-500 uppercase tracking-[0.25em]">Langkah</div>
                                <div class="mt-1 font-semibold">Pilih Jadwal</div>
                            </div>
                            <div class="rounded-2xl border border-white/10 bg-black/40 p-4">
                                <div class="text-xs text-zinc-500 uppercase tracking-[0.25em]">Langkah</div>
                                <div class="mt-1 font-semibold">Pilih Kursi</div>
                            </div>
                            <div class="rounded-2xl border border-white/10 bg-black/40 p-4">
                                <div class="text-xs text-zinc-500 uppercase tracking-[0.25em]">Info</div>
                                <div class="mt-1 font-semibold">Studio muncul di jadwal</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- JADWAL (hidden, kebuka setelah klik "Beli Tiket") --}}
            <section id="jadwal" class="scroll-mt-28">
                <div id="jadwalSection"
                     class="hidden rounded-3xl border border-white/10 bg-zinc-900/50 overflow-hidden">
                    <div class="px-6 py-5 border-b border-white/10 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-2xl bg-red-600/20 border border-red-500/30">
                                <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </span>
                            <div>
                                <h2 class="text-lg font-extrabold tracking-wide">JADWAL TAYANG</h2>
                                <p class="text-xs text-zinc-400 mt-0.5">Klik jam untuk lanjut pilih kursi.</p>
                            </div>
                        </div>

                        <button id="btnCloseJadwal"
                                type="button"
                                class="text-xs font-semibold px-4 py-2 rounded-full border border-white/10 bg-white/5 hover:bg-white/10 transition">
                            Tutup
                        </button>
                    </div>

                    <div class="p-6">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @forelse($movie->showtimes as $showtime)
                                <a href="{{ route('booking.seat', $showtime->id) }}"
                                   class="group block rounded-2xl border border-white/10 bg-black/40 hover:bg-black/60 hover:border-red-500/40 transition p-4 text-center">
                                    <div class="text-xs text-zinc-400 uppercase tracking-wider">
                                        {{ \Carbon\Carbon::parse($showtime->start_time)->format('d M') }}
                                    </div>

                                    <div class="mt-2 text-2xl font-extrabold group-hover:text-red-400 transition">
                                        {{ \Carbon\Carbon::parse($showtime->start_time)->format('H:i') }}
                                    </div>

                                    <div class="mt-2 text-[11px] text-zinc-300 uppercase tracking-widest">
                                        {{ $showtime->studio->name }}
                                    </div>

                                    <div class="mt-3 text-sm font-bold text-green-400">
                                        Rp {{ number_format($showtime->price) }}
                                    </div>
                                </a>
                            @empty
                                <div class="col-span-full text-center text-zinc-500 py-8">
                                    Belum ada jadwal tayang tersedia.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </section>

            {{-- TRAILER --}}
            @if($trailerId)
                <section id="trailer" class="rounded-3xl border border-white/10 bg-zinc-900/50 overflow-hidden">
                    <div class="px-6 py-5 border-b border-white/10 flex items-center justify-between">
                        <h2 class="text-lg font-extrabold tracking-wide">TRAILER</h2>
                        <a class="text-xs text-zinc-300 hover:text-white underline underline-offset-4 decoration-zinc-600 hover:decoration-red-500 transition"
                           href="https://www.youtube.com/watch?v={{ $trailerId }}" target="_blank" rel="noopener">
                            Buka di YouTube
                        </a>
                    </div>

                    <div class="aspect-video bg-black">
                        <iframe class="w-full h-full"
                                src="https://www.youtube.com/embed/{{ $trailerId }}"
                                title="Trailer"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen>
                        </iframe>
                    </div>
                </section>
            @endif

        </div>
    </main>

    <footer class="border-t border-white/10 bg-black py-8 mt-12 text-center text-xs text-zinc-600">
        <p>&copy; {{ date('Y') }} CinemaVerse. All rights reserved.</p>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const btnToggle = document.getElementById('btnToggleJadwal');
            const btnClose  = document.getElementById('btnCloseJadwal');
            const section   = document.getElementById('jadwalSection');

            if (!btnToggle || !section) return;

            const openSchedule = (doScroll = true) => {
                section.classList.remove('hidden');
                section.classList.add('cv-animate-in');

                btnToggle.setAttribute('aria-expanded', 'true');
                btnToggle.textContent = 'Tutup Jadwal';

                if (doScroll) {
                    const wrapper = document.getElementById('jadwal');
                    if (wrapper) wrapper.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            };

            const closeSchedule = () => {
                section.classList.add('hidden');
                section.classList.remove('cv-animate-in');

                btnToggle.setAttribute('aria-expanded', 'false');
                btnToggle.textContent = 'Beli Tiket';
            };

            btnToggle.addEventListener('click', () => {
                const isHidden = section.classList.contains('hidden');
                if (isHidden) openSchedule(true);
                else closeSchedule();
            });

            if (btnClose) btnClose.addEventListener('click', closeSchedule);

            // Auto open dari link: /movie/{id}?open=jadwal
            const params = new URLSearchParams(window.location.search);
            if (params.get('open') === 'jadwal') openSchedule(true);

            // Kalau user klik nav "Tiket" (#jadwal) -> buka dulu biar kelihatan
            if (window.location.hash === '#jadwal') openSchedule(false);
            window.addEventListener('hashchange', () => {
                if (window.location.hash === '#jadwal') openSchedule(false);
            });
        });
    </script>

</body>
</html>
