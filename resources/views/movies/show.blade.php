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
        html { scroll-behavior: smooth; }
    </style>
</head>

<body class="bg-[#050509] text-white font-sans">

    {{-- NAVBAR --}}
    <header class="fixed top-0 left-0 right-0 z-40 bg-black/80 backdrop-blur border-b border-red-900/40 h-24 transition-all duration-300">
        <div class="max-w-screen-2xl mx-auto px-6 h-full flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-red-600 flex items-center justify-center shadow-lg shadow-red-600/40">
                    <span class="text-sm font-extrabold tracking-[0.2em]">CV</span>
                </div>
                <div>
                    <div class="text-xs tracking-[0.4em] text-red-500 font-bold">CINEMA</div>
                    <div class="-mt-1 text-2xl font-bold tracking-[0.35em]">VERSE</div>
                </div>
            </div>

            <nav class="hidden md:flex items-center gap-8 text-base font-medium">
                <a href="{{ route('home') }}" class="text-white hover:text-red-500 transition">Home</a>
                <a href="#jadwal" class="text-gray-300 hover:text-white transition">Tiket</a>
                
                {{-- DROPDOWN CABANG --}}
                <div class="relative group ml-4">
                    <button class="flex items-center gap-2 text-red-500 font-bold border border-red-500/30 bg-red-500/10 px-4 py-2 rounded-full hover:bg-red-500 hover:text-white transition">
                        <span>{{ session('selected_branch_name') ?? 'Pilih Lokasi' }}</span>
                        <svg class="w-3 h-3 transition-transform group-hover:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M19 9l-7 7-7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </button>
                    <div class="absolute right-0 top-full mt-2 w-64 bg-zinc-900 border border-zinc-700 rounded-xl shadow-2xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all z-50">
                        <form action="{{ route('branch.change') }}" method="POST">
                            @csrf
                            <input type="hidden" name="branch_id" id="branchInput">
                            @foreach($globalBranches as $branch)
                                <button type="button" onclick="document.getElementById('branchInput').value='{{ $branch->id }}'; this.form.submit();" class="block w-full text-left px-4 py-3 text-sm text-zinc-300 hover:bg-red-600 hover:text-white transition border-b border-zinc-800/50">
                                    {{ $branch->city }} - {{ $branch->name }}
                                </button>
                            @endforeach
                        </form>
                    </div>
                </div>
            </nav>

            <div class="flex items-center gap-4">
                @auth
                    <span class="text-sm text-zinc-400">Hi, {{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="text-xs text-zinc-500 hover:text-white">Log Out</button></form>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-bold">Log in</a>
                @endauth
            </div>
        </div>
    </header>

    <main class="pt-32 pb-16">
        <div class="max-w-screen-2xl mx-auto px-6 space-y-10">

            {{-- BACK BUTTON --}}
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-sm text-zinc-300 hover:text-white transition group">
                <span class="w-8 h-8 rounded-full border border-white/10 bg-white/5 flex items-center justify-center group-hover:bg-red-600 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 19l-7-7m0 0l7-7m-7 7h18" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </span>
                <span>Kembali ke Home</span>
            </a>

            @php
                $trailerId = null;
                if ($movie->trailer_url) {
                    $urlParts = parse_url($movie->trailer_url);
                    if (isset($urlParts['query'])) {
                        parse_str($urlParts['query'], $ytParams);
                        $trailerId = $ytParams['v'] ?? null;
                    }
                }
            @endphp

            {{-- HERO SECTION --}}
            <section class="relative overflow-hidden rounded-[2rem] border border-red-900/60 bg-gradient-to-r from-black via-zinc-900 to-black shadow-2xl">
                <div class="relative p-6 md:p-10 grid md:grid-cols-[280px,1fr] gap-8">
                    {{-- Poster dari database --}}
                    <img src="{{ $movie->poster_url }}" alt="{{ $movie->title }}" class="w-64 md:w-[280px] rounded-2xl shadow-2xl object-cover aspect-[2/3] border border-white/10">
                    
                    <div class="space-y-6">
                        <div class="space-y-3">
                            <span class="text-xs font-semibold tracking-[0.3em] text-red-400 uppercase">Movie Details</span>
                            <h1 class="text-3xl md:text-5xl font-extrabold leading-tight">{{ $movie->title }}</h1>
                            <div class="flex flex-wrap gap-2 text-xs">
                                <span class="px-4 py-2 rounded-full border border-white/10 bg-white/5 uppercase tracking-wider font-bold">{{ $movie->duration_minutes }} Min</span>
                                <span class="px-4 py-2 rounded-full border border-green-500/40 bg-green-500/10 text-green-300 uppercase tracking-wider font-bold">{{ strtoupper($movie->status) }}</span>
                                <span class="px-4 py-2 rounded-full border border-white/10 bg-white/5 uppercase tracking-wider font-bold">{{ $movie->genre ?? 'General' }}</span>
                            </div>
                        </div>

                        <p class="text-zinc-300 leading-relaxed max-w-3xl text-sm md:text-base italic">"{{ $movie->description }}"</p>

                        <div class="flex flex-wrap items-center gap-3">
                            {{-- ALUR: Mengarah ke route movies.ticket sesuai instruksi terbaru --}}
                            <a href="{{ route('movies.ticket', $movie->id) }}" 
                               class="px-8 py-3 rounded-full bg-red-600 hover:bg-red-500 text-sm font-bold shadow-lg shadow-red-700/40 transition transform hover:scale-105 active:scale-95">
                                Beli Tiket Sekarang
                            </a>

                            @if($trailerId)
                                <a href="#trailer" class="px-8 py-3 rounded-full border border-white/10 bg-white/5 hover:bg-white/10 text-sm font-semibold transition">
                                    Lihat Trailer
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </section>

            {{-- JADWAL TAYANG --}}
            <section id="jadwal" class="scroll-mt-32">
                <div class="rounded-3xl border border-white/10 bg-zinc-900/50 overflow-hidden shadow-xl">
                    <div class="px-6 py-5 border-b border-white/10 flex items-center justify-between bg-zinc-950/50">
                        <h2 class="text-lg font-extrabold tracking-wide uppercase">Jadwal Tayang: {{ session('selected_branch_name') ?? 'Semua Cabang' }}</h2>
                    </div>

                    <div class="p-6">
                        @if($movie->showtimes->isEmpty())
                            <div class="text-center py-10">
                                <p class="text-zinc-500">Maaf, belum ada jadwal tayang untuk lokasi ini.</p>
                                <p class="text-xs text-red-400 mt-2">Coba ganti lokasi bioskop pada menu di atas.</p>
                            </div>
                        @else
                            {{-- Grouping berdasarkan Studio sesuai database --}}
                            @foreach($movie->showtimes->groupBy('studio.name') as $studioName => $showtimes)
                                <div class="mb-8 last:mb-0">
                                    <h3 class="text-sm font-bold text-red-500 mb-4 uppercase tracking-widest border-l-4 border-red-600 pl-3">
                                        {{ $studioName }} <span class="text-zinc-500 lowercase font-normal ml-2">({{ $showtimes->first()->studio->branch->name }})</span>
                                    </h3>
                                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-4">
                                        @foreach($showtimes as $showtime)
                                            {{-- Link ke pemilihan kursi dengan parameter jam --}}
                                            <a href="{{ route('movies.ticket', $movie->id) }}?time={{ $showtime->id }}" 
                                               class="group p-3 rounded-xl border border-white/10 bg-black/40 hover:border-red-500 hover:bg-zinc-800 transition text-center shadow-md">
                                                <div class="text-[10px] text-zinc-500 uppercase font-bold">{{ \Carbon\Carbon::parse($showtime->start_time)->translatedFormat('d M') }}</div>
                                                <div class="text-xl font-black group-hover:text-red-400 transition">{{ \Carbon\Carbon::parse($showtime->start_time)->format('H:i') }}</div>
                                                <div class="text-xs text-green-400 mt-1 font-bold">Rp {{ number_format($showtime->price, 0, ',', '.') }}</div>
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
                <section id="trailer" class="rounded-3xl border border-white/10 bg-zinc-900/50 overflow-hidden shadow-xl">
                    <div class="px-6 py-5 border-b border-white/10 bg-zinc-950/50">
                        <h2 class="text-lg font-extrabold tracking-wide uppercase">Official Trailer</h2>
                    </div>
                    <div class="aspect-video bg-black">
                        <iframe class="w-full h-full" src="https://www.youtube.com/embed/{{ $trailerId }}?rel=0" title="Trailer" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                </section>
            @endif

        </div>
    </main>

    <footer class="border-t border-white/10 py-10 text-center text-xs text-zinc-600">
        <p>&copy; {{ date('Y') }} CinemaVerse. Pengalaman Menonton Bioskop Terbaik.</p>
    </footer>

</body>
</html>