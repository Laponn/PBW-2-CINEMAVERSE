<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>CinemaVerse - Book Your Ticket</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#050509] text-white font-sans">

    {{-- NAVBAR --}}
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
                <a href="{{ route('home') }}" class="text-red-500">Home</a>
                <a href="#" class="text-gray-300 hover:text-white">Tiket</a>
                <a href="#" class="text-gray-300 hover:text-white">Trending</a>
                <a href="#" class="text-gray-300 hover:text-white">Saved</a>
                <a href="#" class="text-gray-300 hover:text-white">Playlist</a>
            </nav>

            <div class="flex items-center gap-3">
                <form action="#" method="GET" class="hidden sm:flex items-center bg-zinc-900 rounded-full px-3 py-1.5 text-xs border border-zinc-700 focus-within:border-red-500 transition">
                    <input type="text" name="q" placeholder="Cari judul film..." class="bg-transparent border-0 focus:ring-0 text-xs placeholder:text-zinc-500 w-32 md:w-48">
                    <button type="submit" class="ml-2 px-3 py-1 rounded-full bg-red-600 hover:bg-red-500 font-semibold">Cari</button>
                </form>
                <a href="#" class="hidden sm:inline-flex text-xs font-semibold border border-zinc-600 rounded-full px-4 py-1.5 hover:border-red-500 hover:text-red-400">
                    Download App
                </a>
            </div>
        </div>
    </header>

    <main class="pt-20 pb-16">
        <div class="max-w-6xl mx-auto px-6 space-y-16">
            {{-- HERO PROMO --}}
            @php
                $featured = $featuredMovie ?? ($movies->first() ?? null);
            @endphp

            @if($featured)
            <section class="relative overflow-hidden rounded-3xl border border-red-900/60 bg-gradient-to-r from-black via-zinc-900 to-black shadow-2xl shadow-red-900/30">
                <div class="absolute inset-0 opacity-30">
                    <div class="w-full h-full bg-[radial-gradient(circle_at_top,_#f97316_0,_transparent_55%),_radial-gradient(circle_at_bottom,_#ef4444_0,_transparent_55%)]"></div>
=======
            {{-- SIDEBAR KIRI --}}
            <aside class="hidden sm:flex flex-col w-60 bg-black/75 backdrop-blur-sm border-r border-white/10">
                <div class="px-8 pt-8 pb-6 text-2xl font-semibold tracking-[0.25em]">
                    <span class="text-red-500">cinema</span><span>VERSE</span>
>>>>>>> a1525b89a5a09d60c8ccb07211e58b8d25f3ba2f
                </div>

                <div class="relative flex flex-col lg:flex-row">
                    {{-- kiri: teks promo --}}
                    <div class="flex-1 p-8 lg:p-10 flex flex-col justify-between">
                        <div>
                            <span class="inline-flex items-center text-xs font-semibold tracking-[0.3em] text-red-400 uppercase">
                                Promo Spesial
                                <span class="ml-2 h-px w-10 bg-red-500/60"></span>
                            </span>

<<<<<<< HEAD
                            <h1 class="mt-4 text-3xl md:text-4xl lg:text-5xl font-extrabold leading-tight">
                                Nonton Lebih <span class="text-red-500">Seru</span>  
                                <br class="hidden md:block">
                                dengan Cinema<span class="text-red-500">Verse</span>
=======
                    {{-- Menu aktif: Tiket --}}
                    <div
                        class="flex items-center gap-2 bg-white/10 text-white px-4 py-2 rounded-full shadow-lg shadow-red-500/30">
                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                        <span class="uppercase tracking-[0.25em] text-[11px]">Tiket</span>
                    </div>

                    <button class="block text-left w-full text-gray-300/80 hover:text-white transition">
                        Home
                    </button>
                    <button class="block text-left w-full text-gray-300/80 hover:text-white transition">
                        Trending
                    </button>
                    <button class="block text-left w-full text-gray-300/80 hover:text-white transition">
                        Movie List
                    </button>

                    <div class="pt-6 text-xs text-gray-500 uppercase tracking-[0.2em]">
                        Account
                    </div>
                    <button class="block text-left w-full text-gray-300/80 hover:text-white transition text-sm">
                        Setting
                    </button>
                    <button class="block text-left w-full text-gray-300/80 hover:text-white transition text-sm">
                        My profile
                    </button>
                </nav>

                <div class="px-8 pb-6 text-[11px] text-gray-500">
                    © {{ date('Y') }} CinemaVerse
                </div>
            </aside>

            {{-- KONTEN KANAN --}}
            <main class="flex-1 px-5 sm:px-8 md:px-10 py-7 md:py-10 flex flex-col gap-8">

                {{-- BAR ATAS: KEMBALI + SEARCH --}}
                <div class="flex items-center justify-between mb-1 gap-4">
                    <button type="button"
                            onclick="history.back()"
                            class="inline-flex items-center gap-2 text-xs text-gray-300 hover:text-white">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M15 19l-7-7 7-7"/>
                        </svg>
                        <span>Kembali</span>
                    </button>

                    {{-- Search (opsional) --}}
                    <form action="{{ route('movie.search') }}" method="GET"
                          class="hidden md:flex items-center bg-black/60 border border-white/15 rounded-full px-4 py-1.5 text-xs">
                        <input type="text" name="q" placeholder="Cari judul film..."
                               class="bg-transparent focus:outline-none placeholder:text-gray-500 text-gray-100 w-44 lg:w-64">
                        <button type="submit"
                                class="ml-3 px-3 py-1 rounded-full bg-red-600 hover:bg-red-700 transition">
                            Cari
                        </button>
                    </form>
                </div>

                {{-- HEADER FILM + STEP --}}
                <section class="space-y-4">
                    <div class="flex flex-col md:flex-row md:items-center gap-5">
                        @if($featuredMovie)
                            <div class="w-full md:w-40 lg:w-48 rounded-2xl overflow-hidden border border-white/15 bg-black/60">
                                @if($featuredMovie->poster_url)
                                    <img src="{{ $featuredMovie->poster_url }}" alt="{{ $featuredMovie->title }}"
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-slate-800 to-slate-900"></div>
                                @endif
                            </div>
                        @endif

                        <div class="space-y-2 md:flex-1">
                            <p class="text-[11px] tracking-[0.35em] uppercase text-red-400">
                                Pemesanan tiket
                            </p>

                            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold leading-tight">
                                {{ $featuredMovie?->title ?? 'Pilih Film' }}
>>>>>>> a1525b89a5a09d60c8ccb07211e58b8d25f3ba2f
                            </h1>

                            <p class="mt-4 text-sm md:text-base text-zinc-300 max-w-xl">
                                Pesan tiket bioskop favoritmu dengan cepat, pilih kursi terbaik,
                                dan nikmati pengalaman menonton ala <span class="text-red-400 font-semibold">CINEMAVERSE</span>.
                            </p>

                            <div class="mt-6 flex flex-wrap items-center gap-4 text-xs text-zinc-300">
                                <div class="px-4 py-2 rounded-full border border-red-500/70 bg-red-600/10 uppercase tracking-[0.2em] font-semibold">
                                    Buy 1 Get 1 • Limited
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-green-400"></span>
                                    <span>Hari ini di bioskop pilihan</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 flex flex-wrap items-center gap-4">
                            <a href="{{ route('movies.show', $featured->id) }}" class="inline-flex items-center justify-center px-6 py-2.5 rounded-full bg-red-600 hover:bg-red-500 text-sm font-semibold shadow-lg shadow-red-700/40">
                                Beli Tiket Sekarang
                            </a>
                            <a href="#now-showing" class="text-sm text-zinc-300 hover:text-white underline underline-offset-4 decoration-zinc-600 hover:decoration-red-500">
                                Lihat film yang sedang tayang
                            </a>
                        </div>
                    </div>

                    {{-- kanan: poster --}}
                    <div class="flex-1 lg:max-w-md xl:max-w-lg p-6 lg:p-8 flex items-center justify-center">
                        <div class="relative w-full max-w-xs">
                            <div class="absolute -inset-4 bg-red-600/40 blur-3xl opacity-30"></div>
                            <img src="{{ $featured->poster_url ?? 'https://via.placeholder.com/400x600?text=CinemaVerse' }}"
                                 alt="{{ $featured->title }}"
                                 class="relative w-full rounded-3xl border border-zinc-700 shadow-2xl shadow-black/80 object-cover">
                            <div class="absolute -bottom-4 left-4 bg-black/80 backdrop-blur px-3 py-2 rounded-2xl text-xs border border-zinc-700 flex items-center gap-2">
                                <span class="px-2 py-0.5 rounded-full bg-red-600/80 text-[10px] font-semibold tracking-wide uppercase">
                                    Now Showing
                                </span>
                                <span class="font-semibold truncate max-w-[140px]">{{ $featured->title }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            @endif

            {{-- NOW SHOWING --}}
            <section id="now-showing">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-2xl md:text-3xl font-extrabold tracking-wide">
                            NOW <span class="text-red-500">SHOWING</span> IN CINEMAS
                        </h2>
                        <p class="text-xs md:text-sm text-zinc-400 mt-1">
                            Pilih film favoritmu dan pesan kursi terbaik hanya dalam beberapa klik.
                        </p>
                    </div>

                    <a href="#" class="hidden sm:inline-flex text-xs md:text-sm font-semibold px-4 py-2 rounded-full border border-zinc-600 hover:border-red-500 hover:text-red-400">
                        Lihat Semua Jadwal
                    </a>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
                    @forelse($movies as $movie)
                        <div class="group bg-zinc-950/70 border border-zinc-800 rounded-3xl overflow-hidden hover:border-red-600/80 hover:-translate-y-1.5 hover:shadow-2xl hover:shadow-red-900/40 transition transform">
                            <div class="relative aspect-[2/3] overflow-hidden">
                                <img src="{{ $movie->poster_url }}" alt="{{ $movie->title }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                <div class="absolute inset-0 bg-gradient-to-t from-black via-black/20 to-transparent opacity-0 group-hover:opacity-70 transition"></div>
                                <div class="absolute bottom-3 left-3 right-3 flex items-center justify-between text-[10px]">
                                    <span class="px-2 py-1 rounded-full bg-red-600/90 font-semibold uppercase tracking-wide">Book Now</span>
                                    <span class="px-2 py-1 rounded-full bg-black/80 border border-zinc-600 text-zinc-200">
                                        {{ $movie->duration_minutes ?? '--' }} Min
                                    </span>
                                </div>
                            </div>
                            <div class="p-3.5">
                                <h3 class="text-sm md:text-base font-semibold line-clamp-2 min-h-[2.5rem]">
                                    {{ $movie->title }}
                                </h3>
                                <p class="mt-1 text-[11px] text-zinc-400">
                                    Rilis: {{ \Carbon\Carbon::parse($movie->release_date)->format('d M Y') }}
                                </p>
                                <a href="{{ route('movies.show', $movie->id) }}"
                                   class="mt-3 inline-flex w-full items-center justify-center text-xs font-semibold px-3 py-2 rounded-full bg-red-600/90 hover:bg-red-500">
                                    Lihat Detail &amp; Jadwal
                                </a>
                            </div>
                        </div>
                    @empty
                        <p class="col-span-4 text-center text-zinc-500 py-8">
                            Belum ada film yang tayang saat ini.
                        </p>
                    @endforelse
                </div>
            </section>

            {{-- OPSIONAL: COMING SOON / EVENT PROMO BISA DITAMBAH DI SINI --}}

        </div>
    </main>

</body>
</html>
