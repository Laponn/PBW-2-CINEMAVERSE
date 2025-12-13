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
   {{-- NAVBAR --}}
    <header class="fixed top-0 left-0 right-0 z-40 bg-black/80 backdrop-blur border-b border-red-900/40">
        <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">
            
            {{-- LOGO (Tetap sama) --}}
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-red-600 flex items-center justify-center shadow-lg shadow-red-600/40">
                    <span class="text-xs font-extrabold tracking-[0.2em]">CV</span>
                </div>
                <div>
                    <div class="text-xs tracking-[0.4em] text-red-500">CINEMA</div>
                    <div class="-mt-1 text-xl font-semibold tracking-[0.35em]">VERSE</div>
                </div>
            </div>

            {{-- MENU TENGAH (Tetap sama) --}}
            <nav class="hidden md:flex items-center gap-8 text-sm font-medium">
                <a href="{{ route('home') }}" class="text-red-500">Home</a>
                <a href="#now-showing" class="text-gray-300 hover:text-white transition">Tiket</a>
                <a href="#" class="text-gray-300 hover:text-white transition">Trending</a>
            </nav>

            {{-- BAGIAN KANAN (SEARCH & AUTH) --}}
            <div class="flex items-center gap-4">
                {{-- Search Form --}}
                <form action="{{ route('movie.search') }}" method="GET" class="hidden sm:flex items-center bg-zinc-900 rounded-full px-3 py-1.5 text-xs border border-zinc-700 focus-within:border-red-500 transition">
                    <input type="text" name="q" placeholder="Cari film..." class="bg-transparent border-0 focus:ring-0 text-xs placeholder:text-zinc-500 w-24 md:w-32 text-white">
                    <button type="submit" class="ml-1 text-red-500 hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </button>
                </form>
                
                {{-- LOGIC AUTH (INI YANG DIUBAH AGAR ADA LOGOUT) --}}
                @if (Route::has('login'))
                    <div class="flex items-center gap-3">
                        @auth
                            {{-- Jika Sudah Login: Tampilkan Nama, Dashboard, & Logout --}}
                            
                            {{-- 1. Nama User --}}
                            <span class="hidden lg:block text-xs font-bold text-zinc-400">
                                Hi, {{ Auth::user()->name }}
                            </span>

                            {{-- 2. Tombol Dashboard --}}
                            <a href="{{ url('/dashboard') }}" class="text-xs font-semibold border border-zinc-600 rounded-full px-4 py-1.5 hover:border-red-500 hover:text-red-400 transition">
                                Dashboard
                            </a>

                            {{-- 3. Tombol Logout (Form Wajib) --}}
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="text-xs font-semibold bg-red-600 text-white rounded-full px-4 py-1.5 hover:bg-red-500 transition shadow-lg shadow-red-900/50">
                                    Log Out
                                </button>
                            </form>

                        @else
                            {{-- Jika Belum Login --}}
                            <a href="{{ route('login') }}" class="text-xs font-semibold text-zinc-300 hover:text-white transition">
                                Log in
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="hidden sm:inline-flex text-xs font-semibold bg-white text-black rounded-full px-4 py-1.5 hover:bg-gray-200 transition">
                                    Register
                                </a>
                            @endif
                        @endauth
                    </div>
                @endif
            </div>
        </div>
    </header>

    <main class="pt-20 pb-16">
        <div class="max-w-6xl mx-auto px-6 space-y-16">
            {{-- HERO PROMO --}}
            @php
                // Gunakan $featuredMovie dari controller jika ada, atau fallback
                $featured = $featuredMovie ?? ($movies->first() ?? null);
            @endphp

            @if($featured)
            <section class="relative overflow-hidden rounded-3xl border border-red-900/60 bg-gradient-to-r from-black via-zinc-900 to-black shadow-2xl shadow-red-900/30">
                {{-- Background Effects --}}
                <div class="absolute inset-0 opacity-30 pointer-events-none">
                    <div class="w-full h-full bg-[radial-gradient(circle_at_top,_#f97316_0,_transparent_55%),_radial-gradient(circle_at_bottom,_#ef4444_0,_transparent_55%)]"></div>
                </div>

                <div class="relative flex flex-col md:flex-row items-center p-8 md:p-12 gap-8">
                    {{-- Left: Text --}}
                    <div class="flex-1 space-y-6">
                        <div>
                            <span class="inline-flex items-center text-xs font-semibold tracking-[0.3em] text-red-400 uppercase">
                                Promo Spesial
                                <span class="ml-2 h-px w-10 bg-red-500/60"></span>
                            </span>

                            <h1 class="mt-4 text-3xl md:text-4xl lg:text-5xl font-extrabold leading-tight">
                                {{ $featured->title }}
                            </h1>
                            
                            <p class="mt-4 text-sm md:text-base text-zinc-300 max-w-xl">
                                {{ Str::limit($featured->description ?? 'Saksikan film-film terbaik pilihan CinemaVerse dengan kualitas audio visual yang memukau.', 150) }}
                            </p>
                        </div>

                        <div class="flex flex-wrap items-center gap-4 text-xs text-zinc-300">
                            <div class="px-4 py-2 rounded-full border border-red-500/70 bg-red-600/10 uppercase tracking-[0.2em] font-semibold">
                                Now Showing
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
                                <span>Tiket Tersedia</span>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-4 pt-2">
                            <a href="{{ route('movies.show', $featured->id) }}" class="inline-flex items-center justify-center px-8 py-3 rounded-full bg-red-600 hover:bg-red-500 text-sm font-bold shadow-lg shadow-red-700/40 transition hover:scale-105">
                                Beli Tiket
                            </a>
                            <a href="#now-showing" class="text-sm font-medium text-zinc-300 hover:text-white underline underline-offset-4 decoration-zinc-600 hover:decoration-red-500 transition">
                                Lihat Jadwal Lainnya
                            </a>
                        </div>
                    </div>

                    {{-- Right: Poster --}}
                    <div class="flex-1 flex justify-center md:justify-end">
                        <div class="relative w-64 md:w-72 lg:w-80 group">
                            <div class="absolute -inset-1 bg-gradient-to-tr from-red-600 to-orange-500 rounded-2xl blur opacity-30 group-hover:opacity-60 transition duration-500"></div>
                            <img src="{{ $featured->poster_url }}" 
                                 alt="{{ $featured->title }}" 
                                 class="relative w-full rounded-2xl border border-zinc-800 shadow-2xl transform group-hover:scale-[1.02] transition duration-500 object-cover aspect-[2/3]">
                        </div>
                    </div>
                </div>
            </section>
            @endif

            {{-- NOW SHOWING --}}
            <section id="now-showing">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h2 class="text-2xl md:text-3xl font-extrabold tracking-wide">
                            NOW <span class="text-red-500">SHOWING</span>
                        </h2>
                        <p class="text-xs md:text-sm text-zinc-400 mt-1">
                            Film-film terbaru yang sedang tayang di bioskop.
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
                    @forelse($movies as $movie)
                        <div class="group bg-zinc-900/50 border border-zinc-800/50 rounded-2xl overflow-hidden hover:border-red-600/50 hover:bg-zinc-900 transition duration-300">
                            {{-- Poster --}}
                            <div class="relative aspect-[2/3] overflow-hidden">
                                <img src="{{ $movie->poster_url }}" alt="{{ $movie->title }}"
                                     class="w-full h-full object-cover group-hover:scale-110 transition duration-700 ease-in-out">
                                
                                <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-60"></div>
                                
                                <div class="absolute top-3 right-3">
                                    <span class="px-2 py-1 bg-black/60 backdrop-blur border border-white/10 rounded-md text-[10px] font-bold uppercase tracking-wider">
                                        {{ $movie->duration_minutes }} Min
                                    </span>
                                </div>
                            </div>

                            {{-- Info --}}
                            <div class="p-4">
                                <h3 class="font-bold text-base leading-tight line-clamp-1 group-hover:text-red-500 transition">
                                    {{ $movie->title }}
                                </h3>
                                <p class="text-xs text-zinc-500 mt-1 mb-4">
                                    {{ $movie->genre ?? 'Action, Drama' }}
                                </p>
                                
                                <a href="{{ route('movies.show', $movie->id) }}" class="block w-full py-2 rounded-lg bg-zinc-800 hover:bg-red-600 text-center text-xs font-bold uppercase tracking-widest transition-colors duration-300">
                                    Book Ticket
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full flex flex-col items-center justify-center py-12 text-zinc-500">
                            <svg class="w-12 h-12 mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"></path></svg>
                            <p>Belum ada film yang tayang saat ini.</p>
                        </div>
                    @endforelse
                </div>
            </section>

        </div>
    </main>
    
    <footer class="border-t border-white/10 bg-black py-8 mt-12 text-center text-xs text-zinc-600">
        <p>&copy; {{ date('Y') }} CinemaVerse. All rights reserved.</p>
    </footer>

</body>
</html>