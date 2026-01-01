{{-- resources/views/layouts/navigation.blade.php --}}
<nav x-data="{ open: false }" class="fixed top-0 left-0 right-0 z-50 bg-black/90 backdrop-blur-md border-b border-white/10 h-20">
    <div class="w-full h-full px-6 flex items-center justify-between">
        
        {{-- KIRI: Logo & Navigasi Utama --}}
        <div class="flex items-center gap-8 w-1/3">
            <a href="/" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-full bg-red-600 flex items-center justify-center shadow-[0_0_15px_rgba(220,38,38,0.5)] group-hover:scale-110 transition">
                    <span class="text-xs font-black text-white">CV</span>
                </div>
                <div class="hidden lg:flex flex-col leading-none">
                    <span class="text-[8px] tracking-[0.4em] text-red-500 font-bold uppercase">Cinema</span>
                    <span class="text-xl font-black tracking-widest text-white">VERSE</span>
                </div>
            </a>

            <div class="hidden md:flex items-center gap-6 text-xs font-bold uppercase tracking-widest">
                <a href="/" class="text-white hover:text-red-500 transition">Home</a>
                <a href="{{ route('booking.index') }}" class="text-zinc-400 hover:text-white transition">Tiket Saya</a>
            </div>
        </div>

        {{-- TENGAH: Tombol Lokasi (Diperbaiki ke Tengah dengan Oval Merah) --}}
        <div class="flex justify-center w-1/3">
            <div class="bg-red-600/20 p-1 rounded-full border border-red-600/30">
                <button type="button" 
                    onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'branch-modal' }))"
                    class="px-6 py-2 bg-red-600 rounded-full text-[11px] font-black uppercase tracking-[0.2em] text-white hover:bg-red-700 transition flex items-center gap-2 shadow-lg shadow-red-600/40">
                    <span class="animate-pulse">📍</span>
                    <span>{{ session('branch_name', 'Pilih Lokasi') }}</span>
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-width="4"/></svg>
                </button>
            </div>
        </div>

        {{-- KANAN: Search & User Avatar Dropdown --}}
       {{-- KANAN: Search & User Avatar Dropdown --}}
<div class="flex items-center justify-end gap-6 w-1/3">
    {{-- Search Bar --}}
    <form action="{{ route('movie.search') }}" class="relative hidden xl:block">
        <input type="text" name="q" placeholder="Cari film..." 
            class="bg-zinc-900 border border-zinc-800 text-white text-[10px] rounded-full pl-5 pr-10 py-2 w-48 focus:border-red-600 focus:ring-0 transition">
        <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-zinc-500 hover:text-red-500">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-4.35-4.35M19 11a8 8 0 11-16 0 8 8 0 0116 0z" stroke-width="2"/></svg>
        </button>
    </form>

    @auth
        {{-- TEKS SELAMAT DATANG (DITAMBAHKAN) --}}
     <div class="hidden sm:flex flex-col text-right leading-tight mr-2">
        {{-- Ukuran diperbesar ke text-[10px] dan warna lebih terang ke zinc-400 --}}
        <span class="text-[10px] text-zinc-400 uppercase tracking-widest font-medium">Selamat Datang</span>
        {{-- Nama User dibuat lebih menonjol --}}
        <span class="text-sm font-black text-white tracking-wide">{{ Auth::user()->name }}</span>
    </div>

        {{-- User Dropdown --}}
        <x-dropdown align="right" width="48">
            <x-slot name="trigger">
                <button class="flex items-center transition duration-150 ease-in-out">
                    {{-- Avatar Lingkaran Nama --}}
                    <div class="w-10 h-10 rounded-full bg-zinc-800 border border-zinc-700 flex items-center justify-center text-red-500 font-black hover:border-red-500 transition shadow-lg">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                </button>
            </x-slot>
            {{-- ... slot content tetap sama ... --}}
            <x-slot name="content">
                <div class="px-4 py-2 border-b border-zinc-800">
                    <p class="text-[10px] text-zinc-500 uppercase">Selamat Datang</p>
                    <p class="text-sm font-bold text-white">{{ Auth::user()->name }}</p>
                </div>

                <x-dropdown-link :href="route('dashboard')">
                    👤 Dashboard Saya 
                </x-dropdown-link>

                @if(Auth::user()->role === 'admin')
                    <x-dropdown-link :href="route('admin.dashboard')" class="text-red-500 font-bold">
                        🛡️ Admin Panel
                    </x-dropdown-link>
                @endif

                <hr class="border-zinc-800">

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                        🚪 Keluar
                    </x-dropdown-link>
                </form>
            </x-slot>
        </x-dropdown>
    @else
        <div class="flex items-center gap-4">
            <a href="{{ route('login') }}" class="text-[10px] font-black uppercase tracking-widest text-zinc-400 hover:text-white transition">Masuk</a>
            <a href="{{ route('register') }}" class="px-5 py-2 bg-white text-black text-[10px] font-black uppercase tracking-widest rounded-full hover:bg-zinc-200 transition">Daftar</a>
        </div>
    @endauth
</div>
    </div>
</nav>