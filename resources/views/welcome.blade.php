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

            {{-- MENU TENGAH & LOKASI --}}
            <nav class="hidden md:flex items-center gap-8 text-base font-medium">
                <a href="{{ route('home') }}" class="text-white hover:text-red-500 transition">Home</a>
                <a href="#now-showing" class="text-gray-300 hover:text-white transition hover:scale-105">Tiket</a>
                
                {{-- DROPDOWN LOKASI (LOGIC SAMA SEPERTI SEBELUMNYA) --}}
                <div class="relative group ml-4">
                    <button class="flex items-center gap-2 text-red-500 font-bold border border-red-500/30 bg-red-500/10 px-4 py-2 rounded-full hover:bg-red-500 hover:text-white transition-all duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        {{-- Menampilkan Nama Cabang Terpilih --}}
                        <span>{{ $currentBranchName ?? 'Pilih Lokasi' }}</span>
                        <svg class="w-3 h-3 transition-transform group-hover:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    
                    {{-- List Dropdown --}}
                    <div class="absolute right-0 top-full mt-2 w-64 bg-zinc-900 border border-zinc-700 rounded-xl shadow-2xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform origin-top z-50 overflow-hidden">
                        <div class="px-4 py-3 border-b border-zinc-800 bg-zinc-950">
                            <span class="text-xs font-semibold text-zinc-500 uppercase tracking-wider">Pilih Bioskop Terdekat</span>
                        </div>
                        <form id="branchForm" action="{{ route('branch.change') }}" method="POST">
                            @csrf
                            <input type="hidden" name="branch_id" id="branchInput">
                            
                            {{-- Opsi "Semua Lokasi" (Reset) --}}
                            <button type="button" 
                                onclick="document.getElementById('branchInput').value=''; document.getElementById('branchForm').submit();" 
                                class="block w-full text-left px-4 py-3 text-sm text-zinc-300 hover:bg-red-600 hover:text-white transition border-b border-zinc-800/50">
                                <span class="font-bold">Semua Lokasi</span>
                            </button>

                            @if(isset($globalBranches) && $globalBranches->count() > 0)
                                @foreach($globalBranches as $branch)
                                    <button type="button" 
                                        onclick="document.getElementById('branchInput').value='{{ $branch->id }}'; document.getElementById('branchForm').submit();" 
                                        class="block w-full text-left px-4 py-3 text-sm text-zinc-300 hover:bg-red-600 hover:text-white transition flex items-center justify-between">
                                        
                                        <div>
                                            <div class="font-semibold">{{ $branch->city }}</div>
                                            <div class="text-xs text-zinc-500 group-hover:text-white/80">{{ $branch->name }}</div>
                                        </div>

                                        @if(isset($currentBranchId) && $currentBranchId == $branch->id)
                                            <svg class="w-4 h-4 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        @endif
                                    </button>
                                @endforeach
                            @else
                                <div class="px-4 py-3 text-xs text-zinc-500 italic">Belum ada data cabang</div>
                            @endif
                        </form>
                    </div>
                </div>
            </nav>

            {{-- BAGIAN KANAN (SEARCH & AUTH) --}}
            <div class="flex items-center gap-5">
                {{-- Search Form --}}
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
                                <span class="hidden lg:block text-sm font-bold text-zinc-400">
                                    Hi, {{ Auth::user()->name }}
                                </span>

                                @if(Auth::user()->role === 'admin')
                                    <a href="{{ route('admin.dashboard') }}" class="text-xs font-bold bg-red-600 text-white border border-red-500 rounded-full px-4 py-2 hover:bg-red-700 transition shadow-[0_0_15px_rgba(220,38,38,0.5)]">
                                        Admin Panel
                                    </a>
                                @else
                                    <a href="{{ url('/dashboard') }}" class="text-xs font-semibold border border-zinc-600 rounded-full px-4 py-2 hover:border-red-500 hover:text-red-400 transition">
                                        Dashboard
                                    </a>
                                @endif

                                {{-- ✅ TOMBOL LIHAT PETA (TAMBAHAN) --}}
                                <button
                                    type="button"
                                    onclick="document.getElementById('mapModal').classList.remove('hidden')"
                                    class="text-xs font-semibold border border-zinc-600 rounded-full px-4 py-2 hover:border-red-500 hover:text-red-400 transition"
                                >
                                    🗺 Lihat Peta
                                </button>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="text-xs font-semibold text-zinc-400 hover:text-white transition px-2">
                                        Log Out
                                    </button>
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

    {{-- MAIN CONTENT --}}
    <main class="pt-32 pb-16">
        <div class="max-w-screen-2xl mx-auto px-6 space-y-16">

            {{-- HERO PROMO (Hanya Muncul Jika Ada Film Featured) --}}
            @php
                // Kita buat variabel $featured dari data $featuredMovie yang dikirim controller
                $featured = $featuredMovie ?? ($movies->first() ?? null);
            @endphp
            @if($featured)
                <section class="relative overflow-hidden rounded-[2rem] border border-red-900/60 bg-gradient-to-r from-black via-zinc-900 to-black shadow-2xl shadow-red-900/20">
                    <div class="absolute inset-0 opacity-30 pointer-events-none">
                        <div class="w-full h-full bg-[radial-gradient(circle_at_top,_#f97316_0,_transparent_55%),_radial-gradient(circle_at_bottom,_#ef4444_0,_transparent_55%)]"></div>
                    </div>

                    <div class="relative flex flex-col md:flex-row items-center p-8 md:p-16 gap-12">
                        <div class="flex-1 space-y-8">
                            <div>
                                <span class="inline-flex items-center text-xs font-bold tracking-[0.3em] text-red-400 uppercase bg-red-900/20 px-3 py-1 rounded-full border border-red-900/50">
                                    Promo Spesial
                                </span>
                                <h1 class="mt-6 text-4xl md:text-5xl lg:text-7xl font-extrabold leading-tight tracking-tight">
                                    {{ $featured->title }}
                                </h1>
                                <p class="mt-6 text-base md:text-lg text-zinc-300 max-w-2xl leading-relaxed">
                                    {{ Str::limit($featured->description ?? 'Saksikan film-film terbaik pilihan CinemaVerse dengan kualitas audio visual yang memukau.', 200) }}
                                </p>
                            </div>

                            <div class="flex flex-wrap items-center gap-5 text-sm text-zinc-300 font-medium">
                                <div class="px-5 py-2 rounded-full border border-red-500/70 bg-red-600/10 uppercase tracking-[0.2em] font-semibold text-red-400">
                                    Now Showing
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full bg-green-500 animate-pulse shadow-[0_0_10px_#22c55e]"></span>
                                    <span>Tiket Tersedia</span>
                                </div>
                            </div>

                            <div class="flex flex-wrap items-center gap-5 pt-4">
                                <a href="{{ route('movies.show', $featured->id) }}" class="inline-flex items-center justify-center px-10 py-4 rounded-full bg-red-600 hover:bg-red-500 text-base font-bold shadow-[0_0_20px_rgba(220,38,38,0.5)] transition hover:scale-105 hover:-translate-y-1">
                                    Beli Tiket
                                </a>
                                <a href="#now-showing" class="text-base font-medium text-zinc-300 hover:text-white underline underline-offset-8 decoration-zinc-600 hover:decoration-red-500 transition">
                                    Lihat Jadwal Lainnya
                                </a>
                            </div>
                        </div>

                        <div class="flex-1 flex justify-center md:justify-end">
                            <div class="relative w-72 md:w-80 lg:w-96 group perspective">
                                <div class="absolute -inset-2 bg-gradient-to-tr from-red-600 to-orange-500 rounded-2xl blur-xl opacity-40 group-hover:opacity-70 transition duration-700"></div>
                                <img src="{{ $featured->poster_url }}" alt="{{ $featured->title }}" class="relative w-full rounded-2xl border border-zinc-700 shadow-2xl transform group-hover:scale-[1.03] group-hover:rotate-1 transition duration-500 object-cover aspect-[2/3]">
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
                            NOW <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-500 to-orange-500">SHOWING</span>
                        </h2>
                        
                        {{-- INFO LOKASI YANG SEDANG DIPILIH --}}
                        <p class="text-sm md:text-base text-zinc-400 mt-2">
                            Menampilkan film yang sedang tayang di: <br class="md:hidden">
                            <span class="text-white font-bold bg-white/10 px-2 py-1 rounded ml-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="inline w-4 h-4 text-red-500 mr-1 -mt-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                </svg>
                                {{ $currentBranchName ?? 'Semua Lokasi' }}
                            </span>
                        </p>
                    </div>
                    
                    {{-- TOMBOL FILTER CEPAT (OPSIONAL) --}}
                    @if(isset($globalBranches))
                    <div class="hidden md:flex gap-2">
                        @foreach($globalBranches->take(3) as $branch)
                           {{-- Logic untuk pindah cabang via tombol kecil --}}
                           <form action="{{ route('branch.change') }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="branch_id" value="{{ $branch->id }}">
                                <button type="submit" class="text-xs px-3 py-1 rounded-full border border-zinc-700 hover:border-red-500 hover:text-red-500 transition {{ isset($currentBranchId) && $currentBranchId == $branch->id ? 'bg-red-600 border-red-600 text-white hover:text-white' : 'text-zinc-500' }}">
                                    {{ $branch->city }}
                                </button>
                           </form>
                        @endforeach
                    </div>
                    @endif
                </div>

                {{-- GRID FILM --}}
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-8">
                    @forelse($movies as $movie)
                        <div class="group bg-zinc-900 rounded-2xl overflow-hidden border border-zinc-800 hover:border-red-600/50 hover:shadow-[0_0_30px_rgba(220,38,38,0.15)] transition duration-500 relative">
                            {{-- Poster --}}
                            <div class="relative aspect-[2/3] overflow-hidden">
                                <img src="{{ $movie->poster_url }}" alt="{{ $movie->title }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700 ease-in-out">
                                <div class="absolute inset-0 bg-gradient-to-t from-black via-black/20 to-transparent opacity-80"></div>
                                
                                <div class="absolute top-3 right-3 z-10">
                                    <span class="px-2 py-1 bg-black/80 backdrop-blur border border-white/20 rounded text-[10px] font-bold uppercase tracking-wider text-white">
                                        {{ $movie->duration_minutes }} Min
                                    </span>
                                </div>

                                {{-- Hover Overlay --}}
                                <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300 bg-black/40 backdrop-blur-[2px]">
                                    <a href="{{ route('movies.show', $movie->id) }}" class="bg-red-600 text-white rounded-full p-4 shadow-lg transform scale-0 group-hover:scale-100 transition duration-300 hover:bg-red-500">
                                        <span class="sr-only">Book Ticket</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                                        </svg>
                                    </a>
                                </div>
                            </div>

                            {{-- Info --}}
                            <div class="p-5 relative z-20 -mt-12 bg-gradient-to-t from-zinc-900 via-zinc-900 to-transparent">
                                <h3 class="font-bold text-lg leading-tight line-clamp-1 group-hover:text-red-500 transition drop-shadow-md">
                                    {{ $movie->title }}
                                </h3>
                                <p class="text-xs text-zinc-400 mt-1 mb-4 line-clamp-1">
                                    {{ $movie->genre ?? 'Action, Drama' }}
                                </p>
                                <a href="{{ route('movies.show', $movie->id) }}" class="block w-full py-2.5 rounded-lg bg-zinc-800 hover:bg-red-600 text-center text-xs font-bold uppercase tracking-widest transition-all duration-300 border border-zinc-700 hover:border-red-500">
                                    Book Ticket
                                </a>
                            </div>
                        </div>
                    @empty
                        {{-- EMPTY STATE JIKA TIDAK ADA FILM DI CABANG TERSEBUT --}}
                        <div class="col-span-full flex flex-col items-center justify-center py-24 text-zinc-500 bg-zinc-900/30 rounded-3xl border border-zinc-800 border-dashed">
                            <div class="w-20 h-20 bg-zinc-800 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-10 h-10 text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-white mb-2">Belum Ada Jadwal</h3>
                            <p class="text-sm text-center max-w-md">
                                Sayang sekali, belum ada film yang tayang di <br> 
                                <span class="text-red-500 font-bold text-lg">{{ $currentBranchName ?? 'Lokasi Ini' }}</span> saat ini.
                            </p>
                            
                            <div class="mt-6 flex gap-4">
                                <button onclick="document.querySelector('nav button').click()" class="px-6 py-2 bg-red-600 text-white rounded-full hover:bg-red-700 transition font-semibold text-sm">
                                    Ganti Lokasi
                                </button>
                            </div>
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

    {{-- ================= MAP MODAL (WELCOME) ================= --}}
    <div id="mapModal" class="hidden fixed inset-0 z-[9999]">
        <div
            class="absolute inset-0 bg-black/70"
            onclick="document.getElementById('mapModal').classList.add('hidden')"
        ></div>

        <div class="relative mx-auto mt-20 w-[900px] max-w-[95vw] rounded-2xl bg-zinc-900 text-white border border-white/10 overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-white/10">
                <div class="font-semibold">Peta Lokasi Bioskop</div>
                <button
                    class="text-white/70 hover:text-white"
                    onclick="document.getElementById('mapModal').classList.add('hidden')"
                >✕</button>
            </div>

            <div class="p-4">
                <iframe
                    src="https://www.openstreetmap.org/export/embed.html?layer=mapnik&marker={{ session('branch_lat', -6.186) }}%2C{{ session('branch_lng', 106.822) }}"
                    style="width:100%; height:520px; border:0"
                    loading="lazy"></iframe>

                <div class="mt-3 text-sm text-white/70">
                    Lokasi saat ini:
                    <b class="text-white">{{ $currentBranchName ?? 'Semua Lokasi' }}</b>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
