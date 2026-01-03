<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - CinemaVerse</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 font-sans min-h-screen">

    <!-- Sidebar -->
    <aside class="w-64 bg-gray-900 text-white fixed inset-y-0 left-0 z-20 flex flex-col">
        <div class="p-6 text-2xl font-bold text-red-500 border-b border-gray-800 tracking-wider">
            CinemaVerse
        </div>

        <nav class="mt-10 px-6 space-y-3">

            {{-- 1. DASHBOARD --}}
            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl transition duration-200 
       {{ request()->routeIs('admin.dashboard') ? 'bg-red-600 text-white shadow-lg shadow-red-500/30' : 'text-gray-400 hover:bg-zinc-800 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
                <span class="font-semibold">Dashboard</span>
            </a>

            {{-- LABEL KATEGORI --}}
            <div class="pt-4 pb-2">
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Master Data</p>
            </div>

            {{-- 2. DATA FILM (YANG SUDAH ADA) --}}
            <a href="{{ route('admin.movies.index') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl transition duration-200 
       {{ request()->routeIs('admin.movies.*') ? 'bg-red-600 text-white shadow-lg shadow-red-500/30' : 'text-gray-400 hover:bg-zinc-800 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" />
                </svg>
                <span class="font-medium">Data Film</span>
            </a>

            {{-- ================================================= --}}
            {{-- 3. DATA CABANG (BARU) --}}
            {{-- ================================================= --}}
            <a href="{{ route('admin.branches.index') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl transition duration-200 
       {{ request()->routeIs('admin.branches.*') ? 'bg-red-600 text-white shadow-lg shadow-red-500/30' : 'text-gray-400 hover:bg-zinc-800 hover:text-white' }}">
                {{-- Icon Map/Lokasi --}}
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <span class="font-medium">Data Cabang</span>
            </a>

            {{-- ================================================= --}}
            {{-- 4. DATA STUDIO (BARU) --}}
            {{-- ================================================= --}}
            <a href="{{ route('admin.studios.index') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl transition duration-200 
       {{ request()->routeIs('admin.studios.*') ? 'bg-red-600 text-white shadow-lg shadow-red-500/30' : 'text-gray-400 hover:bg-zinc-800 hover:text-white' }}">
                {{-- Icon Layar/Projector --}}
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                </svg>
                <span class="font-medium">Data Studio</span>
            </a>
            <a href="{{ route('admin.reports.ticket_sales') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition duration-200
   {{ request()->routeIs('admin.reports.*') ? 'bg-red-600 text-white shadow-lg shadow-red-500/30' : 'text-gray-400 hover:bg-zinc-800 hover:text-white' }}">

        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
     viewBox="0 0 24 24" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M3 3v18h18M7 15l4-4 4 3 5-6" />
</svg>

                <span class="font-medium">Laporan Penjualan Tiket</span>
            </a>



            {{-- LABEL TRANSAKSI --}}
            <div class="pt-4 pb-2">
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Jadwal & Tiket</p>
            </div>

            {{-- ================================================= --}}
            {{-- 5. JADWAL TAYANG (BARU - Persiapan untuk langkah berikutnya) --}}
            {{-- ================================================= --}}
            <a href="{{ route('admin.showtimes.index') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl transition duration-200 
       {{ request()->routeIs('admin.showtimes.*') ? 'bg-red-600 text-white shadow-lg shadow-red-500/30' : 'text-gray-400 hover:bg-zinc-800 hover:text-white' }}">
                {{-- Icon Jam/Calendar --}}
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="font-medium">Jadwal Tayang</span>
            </a>

        </nav>

        <!-- Bottom actions -->
        <div class="p-6 border-t border-gray-800 space-y-2">
            <a href="{{ route('home') }}"
                class="flex items-center gap-2 px-4 py-2 text-gray-400 hover:text-white transition">
               <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 9.75L12 4l9 5.75V20a1 1 0 01-1 1h-5v-6H9v6H4a1 1 0 01-1-1V9.75z" />
                </svg>
                Kembali ke Home
            </a>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                
                <button type="submit" class="w-full text-left px-4 py-2 text-gray-400 hover:text-white transition" > 
                    Logout 
                </button>
                
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="ml-64 p-8 min-h-screen">
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm" role="alert">
                <p class="font-bold">Berhasil!</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @yield('content')
    </main>

</body>

</html>