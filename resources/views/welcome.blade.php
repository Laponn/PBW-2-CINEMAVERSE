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
    {{-- UBAHAN: h-16 jadi h-24 (lebih tinggi) --}}
    <header
        class="fixed top-0 left-0 right-0 z-40 bg-black/80 backdrop-blur border-b border-red-900/40 h-24 transition-all duration-300">
        {{-- UBAHAN: max-w-6xl jadi max-w-screen-2xl (lebih lebar/full screen) & h-full untuk centering --}}
        <div class="max-w-screen-2xl mx-auto px-6 h-full flex items-center justify-between">

            {{-- LOGO --}}
            <div class="flex items-center gap-4"> {{-- gap-3 jadi gap-4 --}}
                {{-- Logo Circle diperbesar (w-9 jadi w-12) --}}
                <div
                    class="w-12 h-12 rounded-full bg-red-600 flex items-center justify-center shadow-lg shadow-red-600/40">
                    <span class="text-sm font-extrabold tracking-[0.2em]">CV</span> {{-- text-xs jadi text-sm --}}
                </div>
                <div>
                    <div class="text-xs tracking-[0.4em] text-red-500 font-bold">CINEMA</div>
                    {{-- Text utama diperbesar (text-xl jadi text-2xl) --}}
                    <div class="-mt-1 text-2xl font-bold tracking-[0.35em]">VERSE</div>
                </div>
            </div>

            {{-- MENU TENGAH --}}
            <nav class="hidden md:flex items-center gap-10 text-base font-medium"> {{-- text-sm jadi text-base, gap-8
                jadi gap-10 --}}
                <a href="{{ route('home') }}" class="text-red-500 font-bold">Home</a>
                <a href="#now-showing" class="text-gray-300 hover:text-white transition hover:scale-105">Tiket</a>
                <a href="#" class="text-gray-300 hover:text-white transition hover:scale-105">Trending</a>
            </nav>

            {{-- BAGIAN KANAN (SEARCH & AUTH) --}}
            <div class="flex items-center gap-5">
                {{-- Search Form --}}
                <form action="{{ route('movie.search') }}" method="GET"
                    class="hidden sm:flex items-center bg-zinc-900 rounded-full px-4 py-2 text-sm border border-zinc-700 focus-within:border-red-500 transition">
                    <input type="text" name="q" placeholder="Cari film..."
                        class="bg-transparent border-0 focus:ring-0 text-sm placeholder:text-zinc-500 w-32 md:w-48 text-white">
                    <button type="submit" class="ml-2 text-red-500 hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </form>

                {{-- LOGIC AUTH --}}
                @if (Route::has('login'))
                    <div class="flex items-center gap-4">
                        {{-- BAGIAN AUTH DI NAVBAR --}}
                        @auth
                            <div class="flex items-center gap-3">
                                {{-- 1. Nama User --}}
                                <span class="hidden lg:block text-sm font-bold text-zinc-400">
                                    Hi, {{ Auth::user()->name }}
                                </span>

                                {{-- 2. LOGIKA TOMBOL KHUSUS ADMIN --}}
                                @if(Auth::user()->role === 'admin')
                                    {{-- Tombol Merah Menyala Khusus Admin --}}
                                    <a href="{{ route('admin.dashboard') }}"
                                        class="text-xs font-bold bg-red-600 text-white border border-red-500 rounded-full px-4 py-2 hover:bg-red-700 transition shadow-[0_0_15px_rgba(220,38,38,0.5)]">
                                        Admin Panel
                                    </a>
                                @else
                                    {{-- Tombol Dashboard Biasa (Opsional untuk user biasa) --}}
                                    <a href="{{ url('/dashboard') }}"
                                        class="text-xs font-semibold border border-zinc-600 rounded-full px-4 py-2 hover:border-red-500 hover:text-red-400 transition">
                                        Dashboard
                                    </a>
                                @endif

                                {{-- 3. Tombol Logout --}}
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="text-xs font-semibold text-zinc-400 hover:text-white transition px-2">
                                        Log Out
                                    </button>
                                </form>
                            </div>
                        @else
                            {{-- Jika Belum Login --}}
                            <div class="flex items-center gap-3">
                                <a href="{{ route('login') }}"
                                    class="text-sm font-bold text-zinc-300 hover:text-white transition">
                                    Log in
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}"
                                        class="hidden sm:inline-flex text-sm font-bold bg-white text-black rounded-full px-5 py-2 hover:bg-gray-200 transition">
                                        Register
                                    </a>
                                @endif
                            </div>
                        @endauth
                    </div>
                @endif
            </div>
        </div>
    </header>

    {{-- MAIN CONTENT --}}
    {{-- UBAHAN: pt-20 jadi pt-32 (agar konten turun kebawah karena header makin tinggi) --}}
    <main class="pt-32 pb-16">
        {{-- UBAHAN: max-w-6xl jadi max-w-screen-2xl --}}
        <div class="max-w-screen-2xl mx-auto px-6 space-y-16">

            {{-- HERO PROMO --}}
            @php
                $featured = $featuredMovie ?? ($movies->first() ?? null);
            @endphp

            @if($featured)
                <section
                    class="relative overflow-hidden rounded-[2rem] border border-red-900/60 bg-gradient-to-r from-black via-zinc-900 to-black shadow-2xl shadow-red-900/20">
                    {{-- Background Effects --}}
                    <div class="absolute inset-0 opacity-30 pointer-events-none">
                        <div
                            class="w-full h-full bg-[radial-gradient(circle_at_top,_#f97316_0,_transparent_55%),_radial-gradient(circle_at_bottom,_#ef4444_0,_transparent_55%)]">
                        </div>
                    </div>

                    <div class="relative flex flex-col md:flex-row items-center p-8 md:p-16 gap-12">
                        {{-- Left: Text --}}
                        <div class="flex-1 space-y-8">
                            <div>
                                <span
                                    class="inline-flex items-center text-xs font-bold tracking-[0.3em] text-red-400 uppercase bg-red-900/20 px-3 py-1 rounded-full border border-red-900/50">
                                    Promo Spesial
                                </span>

                                <h1
                                    class="mt-6 text-4xl md:text-5xl lg:text-7xl font-extrabold leading-tight tracking-tight">
                                    {{ $featured->title }}
                                </h1>

                                <p class="mt-6 text-base md:text-lg text-zinc-300 max-w-2xl leading-relaxed">
                                    {{ Str::limit($featured->description ?? 'Saksikan film-film terbaik pilihan CinemaVerse dengan kualitas audio visual yang memukau.', 200) }}
                                </p>
                            </div>

                            <div class="flex flex-wrap items-center gap-5 text-sm text-zinc-300 font-medium">
                                <div
                                    class="px-5 py-2 rounded-full border border-red-500/70 bg-red-600/10 uppercase tracking-[0.2em] font-semibold text-red-400">
                                    Now Showing
                                </div>
                                <div class="flex items-center gap-2">
                                    <span
                                        class="w-2.5 h-2.5 rounded-full bg-green-500 animate-pulse shadow-[0_0_10px_#22c55e]"></span>
                                    <span>Tiket Tersedia</span>
                                </div>
                            </div>

                            <div class="flex flex-wrap items-center gap-5 pt-4">
                                <a href="{{ route('movies.show', $featured->id) }}"
                                    class="inline-flex items-center justify-center px-10 py-4 rounded-full bg-red-600 hover:bg-red-500 text-base font-bold shadow-[0_0_20px_rgba(220,38,38,0.5)] transition hover:scale-105 hover:-translate-y-1">
                                    Beli Tiket
                                </a>
                                <a href="#now-showing"
                                    class="text-base font-medium text-zinc-300 hover:text-white underline underline-offset-8 decoration-zinc-600 hover:decoration-red-500 transition">
                                    Lihat Jadwal Lainnya
                                </a>
                            </div>
                        </div>

                        {{-- Right: Poster --}}
                        <div class="flex-1 flex justify-center md:justify-end">
                            <div class="relative w-72 md:w-80 lg:w-96 group perspective">
                                <div
                                    class="absolute -inset-2 bg-gradient-to-tr from-red-600 to-orange-500 rounded-2xl blur-xl opacity-40 group-hover:opacity-70 transition duration-700">
                                </div>
                                <img src="{{ $featured->poster_url }}" alt="{{ $featured->title }}"
                                    class="relative w-full rounded-2xl border border-zinc-700 shadow-2xl transform group-hover:scale-[1.03] group-hover:rotate-1 transition duration-500 object-cover aspect-[2/3]">
                            </div>
                        </div>
                    </div>
                </section>
            @endif

            {{-- NOW SHOWING --}}
            <section id="now-showing">
                <div class="flex items-end justify-between mb-10 border-b border-zinc-800 pb-4">
                    <div>
                        <h2 class="text-3xl md:text-4xl font-extrabold tracking-wide">
                            NOW <span
                                class="text-transparent bg-clip-text bg-gradient-to-r from-red-500 to-orange-500">SHOWING</span>
                        </h2>
                        <p class="text-sm md:text-base text-zinc-400 mt-2">
                            Film-film terbaru yang sedang tayang di bioskop.
                        </p>
                    </div>
                    <div class="hidden md:block">
                        <a href="#" class="text-sm font-semibold text-red-500 hover:text-red-400">View All Movies
                            &rarr;</a>
                    </div>
                </div>

                {{-- Grid diperbesar gap dan kolomnya --}}
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-8">
                    @forelse($movies as $movie)
                        <div
                            class="group bg-zinc-900 rounded-2xl overflow-hidden border border-zinc-800 hover:border-red-600/50 hover:shadow-[0_0_30px_rgba(220,38,38,0.15)] transition duration-500 relative">
                            {{-- Poster --}}
                            <div class="relative aspect-[2/3] overflow-hidden">
                                <img src="{{ $movie->poster_url }}" alt="{{ $movie->title }}"
                                    class="w-full h-full object-cover group-hover:scale-110 transition duration-700 ease-in-out">

                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-black via-black/20 to-transparent opacity-80">
                                </div>

                                <div class="absolute top-3 right-3 z-10">
                                    <span
                                        class="px-2 py-1 bg-black/80 backdrop-blur border border-white/20 rounded text-[10px] font-bold uppercase tracking-wider text-white">
                                        {{ $movie->duration_minutes }} Min
                                    </span>
                                </div>

                                {{-- Hover Button (Desktop) --}}
                                <div
                                    class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300">
                                    <a href="{{ route('movies.show', $movie->id) }}"
                                        class="bg-red-600 text-white rounded-full p-4 shadow-lg transform scale-0 group-hover:scale-100 transition duration-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                                        </svg>
                                    </a>
                                </div>
                            </div>

                            {{-- Info --}}
                            <div class="p-5 relative z-20 -mt-12">
                                <h3
                                    class="font-bold text-lg leading-tight line-clamp-1 group-hover:text-red-500 transition drop-shadow-md">
                                    {{ $movie->title }}
                                </h3>
                                <p class="text-xs text-zinc-400 mt-1 mb-4 line-clamp-1">
                                    {{ $movie->genre ?? 'Action, Drama' }}
                                </p>

                                <a href="{{ route('movies.show', $movie->id) }}"
                                    class="block w-full py-2.5 rounded-lg bg-zinc-800 hover:bg-red-600 text-center text-xs font-bold uppercase tracking-widest transition-all duration-300 border border-zinc-700 hover:border-red-500">
                                    Book Ticket
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full flex flex-col items-center justify-center py-20 text-zinc-500">
                            <svg class="w-16 h-16 mb-4 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z">
                                </path>
                            </svg>
                            <p class="text-lg font-medium">Belum ada film yang tayang saat ini.</p>
                        </div>
                    @endforelse
                </div>
            </section>

        </div>
    </main>

    <footer class="border-t border-zinc-800 bg-black py-10 mt-20 text-center text-sm text-zinc-500">
        <div class="max-w-screen-2xl mx-auto px-6">
            <p>&copy; {{ date('Y') }} CinemaVerse. All rights reserved.</p>
        </div>
    </footer>

</body>

</html>