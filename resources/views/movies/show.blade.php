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

    {{-- ================================================================== --}}
    {{-- NAVBAR (SAMA PERSIS DENGAN HOME, TAPI LINK TIKET KE #JADWAL) --}}
    {{-- ================================================================== --}}
    <header class="fixed top-0 left-0 right-0 z-40 bg-black/80 backdrop-blur border-b border-red-900/40 h-24 transition-all duration-300">
        <div class="max-w-screen-2xl mx-auto px-6 h-full flex items-center justify-between">

            {{-- LOGO --}}
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-red-600 flex items-center justify-center shadow-lg shadow-red-600/40">
                    <span class="text-sm font-extrabold tracking-[0.2em]">CV</span>
                </div>
                <div>
                    <div class="text-xs tracking-[0.4em] text-red-500 font-bold">CINEMA</div>
                    <div class="-mt-1 text-2xl font-bold tracking-[0.35em]">VERSE</div>
                </div>
            </div>

            {{-- MENU TENGAH --}}
            <nav class="hidden md:flex items-center gap-8 text-base font-medium">
                <a href="{{ route('home') }}" class="text-white hover:text-red-500 transition">Home</a>
                
                {{-- Link Tiket mengarah ke section #jadwal di halaman ini --}}
                <a href="#jadwal" class="text-gray-300 hover:text-white transition hover:scale-105">Tiket</a>
                
                {{-- DROPDOWN LOKASI (PENTING: AGAR BISA GANTI CABANG DI HALAMAN DETAIL) --}}
                <div class="relative group ml-4">
                    <button class="flex items-center gap-2 text-red-500 font-bold border border-red-500/30 bg-red-500/10 px-4 py-2 rounded-full hover:bg-red-500 hover:text-white transition-all duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>{{ $currentBranchName ?? 'Pilih Lokasi' }}</span>
                        <svg class="w-3 h-3 transition-transform group-hover:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    
                    <div class="absolute right-0 top-full mt-2 w-64 bg-zinc-900 border border-zinc-700 rounded-xl shadow-2xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform origin-top z-50 overflow-hidden">
                        <div class="px-4 py-3 border-b border-zinc-800 bg-zinc-950">
                            <span class="text-xs font-semibold text-zinc-500 uppercase tracking-wider">Ganti Bioskop</span>
                        </div>
                        <form id="branchForm" action="{{ route('branch.change') }}" method="POST">
                            @csrf
                            <input type="hidden" name="branch_id" id="branchInput">
                            
                            <button type="button" onclick="document.getElementById('branchInput').value=''; document.getElementById('branchForm').submit();" class="block w-full text-left px-4 py-3 text-sm text-zinc-300 hover:bg-red-600 hover:text-white transition border-b border-zinc-800/50">
                                <span class="font-bold">Semua Lokasi</span>
                            </button>

                            @if(isset($globalBranches))
                                @foreach($globalBranches as $branch)
                                    <button type="button" onclick="document.getElementById('branchInput').value='{{ $branch->id }}'; document.getElementById('branchForm').submit();" class="block w-full text-left px-4 py-3 text-sm text-zinc-300 hover:bg-red-600 hover:text-white transition flex items-center justify-between">
                                        <span>{{ $branch->city }} - {{ $branch->name }}</span>
                                        @if(isset($currentBranchId) && $currentBranchId == $branch->id)
                                            <svg class="w-4 h-4 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        @endif
                                    </button>
                                @endforeach
                            @endif
                        </form>
                    </div>
                </div>
            </nav>

            {{-- KANAN (SEARCH & AUTH) --}}
            <div class="flex items-center gap-5">
                <form action="{{ route('movie.search') }}" method="GET" class="hidden sm:flex items-center bg-zinc-900 rounded-full px-4 py-2 text-sm border border-zinc-700 focus-within:border-red-500 transition">
                    <input type="text" name="q" placeholder="Cari film..." class="bg-transparent border-0 focus:ring-0 text-sm placeholder:text-zinc-500 w-32 md:w-48 text-white">
                    <button type="submit" class="ml-2 text-red-500 hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </form>

                @if (Route::has('login'))
                    <div class="flex items-center gap-4">
                        @auth
                            <div class="flex items-center gap-3">
                                <span class="hidden lg:block text-sm font-bold text-zinc-400">Hi, {{ Auth::user()->name }}</span>
                                @if(Auth::user()->role === 'admin')
                                    <a href="{{ route('admin.dashboard') }}" class="text-xs font-bold bg-red-600 text-white border border-red-500 rounded-full px-4 py-2 hover:bg-red-700 transition">Admin Panel</a>
                                @else
                                    <a href="{{ url('/dashboard') }}" class="text-xs font-semibold border border-zinc-600 rounded-full px-4 py-2 hover:border-red-500 hover:text-red-400 transition">Dashboard</a>
                                @endif
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="text-xs font-semibold text-zinc-400 hover:text-white transition px-2">Log Out</button>
                                </form>
                            </div>
                        @else
                            <div class="flex items-center gap-3">
                                <a href="{{ route('login') }}" class="text-sm font-bold text-zinc-300 hover:text-white transition">Log in</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="hidden sm:inline-flex text-sm font-bold bg-white text-black rounded-full px-5 py-2 hover:bg-gray-200 transition">Register</a>
                                @endif
                            </div>
                        @endauth
                    </div>
                @endif
            </div>
        </div>
    </header>

    {{-- CONTENT --}}
    <main class="pt-32 pb-16">
        <div class="max-w-screen-2xl mx-auto px-6 space-y-10">

            {{-- BACK BUTTON --}}
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-sm text-zinc-300 hover:text-white transition group">
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full border border-white/10 bg-white/5 group-hover:bg-red-600 group-hover:border-red-600 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </span>
                <span>Kembali ke Home</span>
            </a>

            @php
                $releaseYear = $movie->release_date ? \Carbon\Carbon::parse($movie->release_date)->format('Y') : null;
                $statusText  = $movie->status ? strtoupper(str_replace('_',' ', $movie->status)) : 'STATUS';
                $genreText   = $movie->genre ?? null;

                // Logic Trailer
                $trailerId = null;
                if (!empty($movie->trailer_url)) {
                    $raw = trim($movie->trailer_url);
                    if (preg_match('~^[a-zA-Z0-9_-]{11}$~', $raw)) {
                        $trailerId = $raw;
                    } else {
                        $parts = parse_url($raw);
                        if ($parts && isset($parts['host'])) {
                            if (str_contains($parts['host'], 'youtu.be')) {
                                $trailerId = ltrim($parts['path'] ?? '', '/');
                            } else {
                                parse_str($parts['query'] ?? '', $q);
                                $trailerId = $q['v'] ?? null;
                            }
                        }
                    }
                }
            @endphp

            {{-- HERO DETAIL --}}
            <section class="relative overflow-hidden rounded-[2rem] border border-red-900/60 bg-gradient-to-r from-black via-zinc-900 to-black shadow-2xl shadow-red-900/30">
                <div class="absolute inset-0 opacity-30 pointer-events-none">
                    <div class="w-full h-full bg-[radial-gradient(circle_at_top,_#f97316_0,_transparent_55%),_radial-gradient(circle_at_bottom,_#ef4444_0,_transparent_55%)]"></div>
                </div>

                <div class="relative p-6 md:p-10 grid md:grid-cols-[280px,1fr] gap-8">
                    {{-- Poster --}}
                    <div class="flex justify-center md:justify-start">
                        <div class="relative w-64 md:w-[280px] group">
                            <div class="absolute -inset-1 bg-gradient-to-tr from-red-600 to-orange-500 rounded-2xl blur opacity-30 group-hover:opacity-60 transition duration-500"></div>
                            <img src="{{ $movie->poster_url }}" alt="{{ $movie->title }}" class="relative w-full rounded-2xl border border-zinc-800 shadow-2xl object-cover aspect-[2/3]">
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

                        <p class="text-zinc-300 leading-relaxed text-sm md:text-base">
                            {{ $movie->description }}
                        </p>

                        {{-- CTA --}}
                        <div class="flex flex-wrap items-center gap-3 pt-2">
                           <a href="{{ route('tickets.show', $movie->id) }}"
                            class="inline-flex items-center justify-center px-8 py-3 rounded-full bg-red-600 hover:bg-red-500 text-sm font-bold shadow-lg shadow-red-700/40 transition hover:scale-[1.02]">
                                Beli Tiket
                            </a>

                            @if($trailerId)
                                <a href="#trailer" class="inline-flex items-center justify-center px-8 py-3 rounded-full border border-white/10 bg-white/5 hover:bg-white/10 text-sm font-semibold transition">
                                    Lihat Trailer
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </section>

            {{-- JADWAL --}}
            <section id="jadwal" class="scroll-mt-32">
                <div id="jadwalSection" class="hidden rounded-3xl border border-white/10 bg-zinc-900/50 overflow-hidden">
                    <div class="px-6 py-5 border-b border-white/10 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-2xl bg-red-600/20 border border-red-500/30">
                                <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </span>
                            <div>
                                <h2 class="text-lg font-extrabold tracking-wide">JADWAL TAYANG</h2>
                                <p class="text-xs text-zinc-400 mt-0.5">
                                    Lokasi: <span class="text-white font-bold">{{ $currentBranchName ?? 'Semua Cabang' }}</span>
                                </p>
                            </div>
                        </div>
                        <button id="btnCloseJadwal" type="button" class="text-xs font-semibold px-4 py-2 rounded-full border border-white/10 bg-white/5 hover:bg-white/10 transition">Tutup</button>
                    </div>

                    <div class="p-6 space-y-8">
                        @if($movie->showtimes->isEmpty())
                            <div class="text-center py-10 bg-white/5 rounded-2xl border border-white/5 border-dashed">
                                <p class="text-zinc-500 font-medium">Belum ada jadwal tayang untuk lokasi ini.</p>
                                <p class="text-xs text-zinc-600 mt-2">Coba ganti lokasi di menu atas.</p>
                            </div>
                        @else
                            {{-- PENGELOMPOKAN BERDASARKAN STUDIO/CABANG --}}
                            @php
                                $groupedShowtimes = $movie->showtimes->groupBy(function($item) {
                                    return $item->studio->branch->name . ' - ' . $item->studio->name . ' (' . ucfirst($item->studio->type) . ')';
                                });
                            @endphp

                            @foreach($groupedShowtimes as $studioName => $showtimes)
                                <div>
                                    <h3 class="text-sm font-bold text-zinc-300 mb-3 border-l-4 border-red-600 pl-3 uppercase tracking-wider">
                                        {{ $studioName }}
                                    </h3>
                                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                                        @foreach($showtimes as $showtime)
                                            <a href="{{ route('booking.seat', $showtime->id) }}" class="group block rounded-xl border border-white/10 bg-black/40 hover:bg-zinc-800 hover:border-red-500 transition p-3 text-center">
                                                <div class="text-[10px] text-zinc-500 uppercase tracking-wider">
                                                    {{ \Carbon\Carbon::parse($showtime->start_time)->format('d M') }}
                                                </div>
                                                <div class="mt-1 text-xl font-bold group-hover:text-red-400 transition">
                                                    {{ \Carbon\Carbon::parse($showtime->start_time)->format('H:i') }}
                                                </div>
                                                <div class="mt-2 text-xs font-semibold text-green-400">
                                                    Rp {{ number_format($showtime->price/1000) }}k
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </section>

            {{-- TRAILER SECTION --}}
            @if($trailerId)
                <section id="trailer" class="rounded-3xl border border-white/10 bg-zinc-900/50 overflow-hidden">
                    <div class="px-6 py-5 border-b border-white/10 flex items-center justify-between">
                        <h2 class="text-lg font-extrabold tracking-wide">TRAILER</h2>
                    </div>
                    <div class="aspect-video bg-black">
                        <iframe class="w-full h-full" src="https://www.youtube.com/embed/{{ $trailerId }}" title="Trailer" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
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

            const params = new URLSearchParams(window.location.search);
            if (params.get('open') === 'jadwal') openSchedule(true);

            if (window.location.hash === '#jadwal') openSchedule(false);
            window.addEventListener('hashchange', () => {
                if (window.location.hash === '#jadwal') openSchedule(false);
            });
        });
    </script>

</body>
</html>