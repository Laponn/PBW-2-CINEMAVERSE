@php
    $currentRoute = Route::currentRouteName();
@endphp

<nav x-data="{ open: false }"
     class="fixed top-0 inset-x-0 z-50 h-20
            bg-gradient-to-b from-[#050509]/95 to-[#050509]/85
            backdrop-blur-xl
            border-b border-white/10
            shadow-[0_15px_60px_rgba(0,0,0,0.75)]
            nav-glow">

    {{-- Cinematic light sweep --}}
    <div class="absolute inset-0 pointer-events-none overflow-hidden">
        <div class="nav-sweep"></div>
    </div>

    <div class="relative w-full h-full px-6 flex items-center justify-between">

        {{-- LEFT --}}
        <div class="flex items-center gap-10 w-1/3">

            {{-- LOGO --}}
            <a href="/" class="flex items-center gap-3 group">
                <div
                    class="relative w-11 h-11 rounded-full bg-red-600
                           flex items-center justify-center
                           shadow-[0_0_30px_rgba(220,38,38,0.8)]
                           transition-all duration-500
                           group-hover:scale-110">

                    <span class="text-xs font-black text-white tracking-widest">CV</span>

                    {{-- inner glow --}}
                    <span class="absolute inset-0 rounded-full
                                 bg-red-600/40 blur-xl opacity-0
                                 group-hover:opacity-100 transition"></span>
                </div>

                <div class="hidden lg:flex flex-col leading-none">
                    <span class="text-[8px] tracking-[0.45em] text-red-500 font-bold uppercase">
                        Cinema
                    </span>
                    <span class="text-xl font-black tracking-widest text-white">
                        VERSE
                    </span>
                </div>
            </a>

            {{-- NAV LINKS --}}
            <div class="hidden md:flex items-center gap-8 text-[10px] font-black uppercase tracking-[0.25em]">
                {{-- HOME --}}
                <a href="/"
                   class="nav-link {{ $currentRoute === 'home' ? 'active' : '' }}">
                    Home
                </a>

                {{-- TIKET --}}
                <a href="{{ route('booking.index') }}"
                   class="nav-link {{ str_starts_with($currentRoute, 'booking') ? 'active' : '' }}">
                    Tiket Saya
                </a>
            </div>
        </div>

        {{-- CENTER --}}
        <div class="flex justify-center w-1/3">
            <div class="relative bg-red-600/20 p-1 rounded-full border border-red-600/30">
                <button type="button"
                        onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'branch-modal' }))"
                        class="relative px-6 py-2 bg-red-600 rounded-full
                               text-[11px] font-black uppercase tracking-[0.2em] text-white
                               hover:bg-red-700 transition
                               flex items-center gap-2
                               shadow-lg shadow-red-600/50">

                    <span class="animate-pulse">📍</span>
                    <span>{{ session('branch_name', 'Pilih Lokasi') }}</span>

                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M19 9l-7 7-7-7" stroke-width="4"/>
                    </svg>

                    {{-- glow --}}
                    <span class="absolute inset-0 rounded-full
                                 bg-red-600/40 blur-xl opacity-40"></span>
                </button>
            </div>
        </div>

        {{-- RIGHT --}}
        <div class="flex items-center justify-end gap-6 w-1/3">

            {{-- SEARCH --}}
            <form action="{{ route('movie.search') }}" class="relative hidden xl:block group">
                <input type="text" name="q" placeholder="Cari film..."
                       class="bg-white/5 border border-white/10
                              text-white text-[10px]
                              rounded-full pl-5 pr-10 py-2 w-48
                              placeholder-zinc-500
                              focus:ring-2 focus:ring-red-600/40
                              focus:border-red-600
                              transition">
                <button type="submit"
                        class="absolute right-3 top-1/2 -translate-y-1/2
                               text-zinc-500 group-hover:text-red-500 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M21 21l-4.35-4.35M19 11a8 8 0 11-16 0 8 8 0 0116 0z"
                              stroke-width="2"/>
                    </svg>
                </button>
            </form>

            @auth
                {{-- WELCOME --}}
                <div class="hidden sm:flex flex-col text-right leading-tight mr-2">
                    <span class="text-[10px] text-zinc-400 uppercase tracking-widest">
                        Selamat Datang
                    </span>
                    <span class="text-sm font-black text-white tracking-wide">
                        {{ Auth::user()->name }}
                    </span>
                </div>

                {{-- AVATAR --}}
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="group">
                            <div
                                class="relative w-11 h-11 rounded-full
                                       bg-zinc-900 border border-white/10
                                       flex items-center justify-center
                                       text-red-500 font-black
                                       shadow-[0_0_25px_rgba(220,38,38,0.35)]
                                       transition-all duration-500
                                       group-hover:border-red-500
                                       group-hover:scale-105">

                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}

                                <span class="absolute inset-0 rounded-full
                                             bg-red-600/30 blur-xl opacity-0
                                             group-hover:opacity-100 transition"></span>
                            </div>
                        </button>
                    </x-slot>

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
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                🚪 Keluar
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            @else
                <div class="flex items-center gap-4">
                    <a href="{{ route('login') }}"
                       class="text-[10px] font-black uppercase tracking-widest
                              text-zinc-400 hover:text-white transition">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}"
                       class="px-5 py-2 bg-white text-black
                              text-[10px] font-black uppercase tracking-widest
                              rounded-full hover:bg-zinc-200 transition">
                        Daftar
                    </a>
                </div>
            @endauth
        </div>
    </div>
</nav>

{{-- ===== EXTRA STYLE ===== --}}
<style>
    body {
        background-color: #050509;
    }

    .nav-glow {
        box-shadow:
            0 20px 60px rgba(0,0,0,.8),
            inset 0 -1px 0 rgba(255,255,255,.05);
    }

    .nav-link {
        position: relative;
        color: #a1a1aa;
        transition: color .3s ease;
    }

    .nav-link:hover {
        color: #fff;
    }

    .nav-link::after {
        content: "";
        position: absolute;
        left: 50%;
        bottom: -6px;
        width: 0;
        height: 2px;
        background: #dc2626;
        transform: translateX(-50%);
        transition: width .3s ease;
        box-shadow: 0 0 12px rgba(220,38,38,.8);
    }

    .nav-link:hover::after,
    .nav-link.active::after {
        width: 100%;
    }

    .nav-link.active {
        color: #fff;
    }

    .nav-sweep {
        position: absolute;
        inset: -100%;
        background: linear-gradient(
            120deg,
            transparent 40%,
            rgba(220,38,38,.15) 50%,
            transparent 60%
        );
        animation: sweep 8s infinite linear;
    }

    @keyframes sweep {
        from { transform: translateX(-50%); }
        to   { transform: translateX(50%); }
    }
</style>

{{-- ===== MICRO INTERACTION ===== --}}
<script>
    const nav = document.querySelector('nav');
    window.addEventListener('scroll', () => {
        nav.classList.toggle('shadow-red-600/10', window.scrollY > 20);
    });
</script>
