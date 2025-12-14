<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CinemaVerse - Pesan Tiket</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        /* Scroll bar halus untuk list film di bawah */
        .nice-scroll::-webkit-scrollbar {
            height: 6px;
        }
        .nice-scroll::-webkit-scrollbar-track {
            background: transparent;
        }
        .nice-scroll::-webkit-scrollbar-thumb {
            background: rgba(148, 163, 184, 0.6);
            border-radius: 999px;
        }
    </style>
</head>
<body class="bg-[#05070b] text-white antialiased">

@php
    $featuredMovie = $movies->first();
    $otherMovies   = $movies->skip(1);

    // Jadwal contoh (5 hari ke depan & beberapa jam tayang)
    $dates = [];
    for ($i = 0; $i < 5; $i++) {
        $dates[] = \Carbon\Carbon::now()->addDays($i);
    }

    $times = ['10:30', '13:00', '15:30', '18:00', '20:45'];
    $seatRows = ['A','B','C','D','E','F'];
    $seatCols = [1,2,3,4,5,6,7,8,9,10];
@endphp

{{-- KARTU BESAR FULLSCREEN --}}
<div class="min-h-screen w-full">

    <div class="relative w-full min-h-screen overflow-hidden border border-white/10 bg-black/80 rounded-none">
        {{-- isi yang lain tetap sama --}}

        {{-- Background poster film --}}
        <div class="absolute inset-0">
            @if($featuredMovie && $featuredMovie->poster_url)
                <img src="{{ $featuredMovie->poster_url }}"
                     alt="{{ $featuredMovie->title }}"
                     class="w-full h-full object-cover opacity-60">
            @else
                <div class="w-full h-full bg-gradient-to-tr from-slate-900 via-slate-800 to-slate-900"></div>
            @endif
            <div class="absolute inset-0 bg-gradient-to-r from-black via-black/85 to-black/30"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-black/60"></div>
        </div>

        <div class="relative flex h-full">

            {{-- SIDEBAR KIRI --}}
            <aside class="hidden sm:flex flex-col w-60 bg-black/75 backdrop-blur-sm border-r border-white/10">
                <div class="px-8 pt-8 pb-6 text-2xl font-semibold tracking-[0.25em]">
                    <span class="text-red-500">cinema</span><span>VERSE</span>
                </div>

                <nav class="flex-1 px-8 space-y-4 text-sm">
                    <a href="{{ route('home') }}" class="block text-gray-300 hover:text-white transition">
                        Home
                    </a>

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
                            </h1>

                            <p class="text-sm sm:text-base text-gray-200/90 leading-relaxed line-clamp-3">
                                {{ $featuredMovie?->description ?? 'Pilih film yang ingin kamu tonton dan lanjutkan proses pemesanan tiket bioskop di CinemaVerse.' }}
                            </p>

                            @if($featuredMovie)
                                @php
                                    $rating10      = (float) ($featuredMovie->rating ?? 0);
                                    $rating10      = max(0, min(10, $rating10));
                                    $filledStars   = (int) round($rating10 / 2);   // 0–10 -> 0–5
                                    $ratingOnFive  = $rating10 / 2;
                                @endphp

                                <div class="flex flex-wrap items-center gap-3 text-[11px] sm:text-xs text-gray-200/90 pt-1">
                                    <div class="flex items-center gap-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-4 h-4 {{ $i <= $filledStars ? 'text-yellow-400' : 'text-gray-600/70' }}"
                                                 fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1
                                                     1 0 00.95.69h3.462c.969 0 1.371 1.24.588
                                                     1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07
                                                     3.292c.3.921-.755 1.688-1.54
                                                     1.118l-2.8-2.034a1 1 0 00-1.175
                                                     0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1
                                                     1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1
                                                     1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        @endfor
                                        <span class="ml-1 text-gray-200">
                                            {{ number_format($ratingOnFive, 1) }}/5 stars rating
                                        </span>
                                    </div>

                                    <span class="w-1 h-1 rounded-full bg-gray-500/70"></span>

                                    @if($featuredMovie->duration_minutes)
                                        <span class="px-3 py-1 rounded-full bg-black/40 border border-white/15">
                                            {{ $featuredMovie->duration_minutes }} menit
                                        </span>
                                    @endif

                                    @if($featuredMovie->release_date)
                                        <span class="w-1 h-1 rounded-full bg-gray-500/70"></span>

                                        <span class="px-3 py-1 rounded-full bg-black/40 border border-white/15">
                                            Rilis {{ \Carbon\Carbon::parse($featuredMovie->release_date)->format('d M Y') }}
                                        </span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Indikator langkah --}}
                    <div class="flex items-center gap-3 text-[11px] uppercase tracking-[0.25em] text-gray-400">
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-red-600 text-[10px] flex items-center justify-center font-semibold">1</span>
                            <span class="text-white">Pilih jadwal & kursi</span>
                        </div>
                        <div class="h-px flex-1 bg-gradient-to-r from-red-500/70 via-gray-600/40 to-transparent"></div>
                        <div class="flex items-center gap-2 opacity-60">
                            <span class="w-6 h-6 rounded-full border border-gray-500 text-[10px] flex items-center justify-center font-semibold">2</span>
                            <span>Pembayaran</span>
                        </div>
                    </div>
                </section>

                {{-- GRID: JADWAL + KURSI + RINGKASAN --}}
                <section class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

                    {{-- KIRI: JADWAL & KURSI --}}
                    <div class="lg:col-span-2 space-y-5">

                        {{-- PILIH TANGGAL & JAM --}}
                        <div class="bg-black/75 border border-white/12 rounded-3xl p-4 sm:p-6 space-y-4 backdrop-blur-sm">
                            <div class="flex items-center justify-between">
                                <h2 class="text-sm font-semibold tracking-[0.15em] uppercase text-gray-200">
                                    Pilih jadwal
                                </h2>
                                <p class="text-[11px] text-gray-400">
                                    {{ $featuredMovie?->title ? 'Pilih hari & jam tayang' : 'Pilih jadwal penayangan' }}
                                </p>
                            </div>

                            {{-- TANGGAL --}}
                            <div class="space-y-2">
                                <p class="text-[11px] text-gray-400 uppercase tracking-[0.18em]">
                                    Tanggal
                                </p>
                                <div class="nice-scroll flex gap-3 overflow-x-auto pb-2 pt-1">
                                    @foreach($dates as $index => $date)
                                        @php
                                            $dayName = $date->translatedFormat('D');
                                            $dayNum  = $date->format('d');
                                            $month   = $date->translatedFormat('M');
                                        @endphp
                                        <button type="button"
                                                class="date-btn flex flex-col justify-between min-w-[76px] px-3 py-2 rounded-2xl border border-white/15 bg-white/5 text-gray-200 text-xs hover:bg-white/10 transition"
                                                data-date="{{ $date->format('Y-m-d') }}"
                                                data-date-display="{{ $dayName }}, {{ $dayNum }} {{ $month }}">
                                            <span class="text-[11px] uppercase tracking-[0.18em] text-gray-400">
                                                {{ $dayName }}
                                            </span>
                                            <span class="text-base font-semibold leading-snug">
                                                {{ $dayNum }}
                                            </span>
                                            <span class="text-[11px] text-gray-300">
                                                {{ $month }}
                                            </span>
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            {{-- JAM --}}
                            <div class="space-y-2">
                                <p class="text-[11px] text-gray-400 uppercase tracking-[0.18em]">
                                    Jam tayang
                                </p>
                                <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-5 gap-3">
                                    @foreach($times as $time)
                                        <button type="button"
                                                class="time-btn px-3 py-2 rounded-2xl border border-white/15 bg-white/5 text-xs text-gray-200 hover:bg-white/10 transition"
                                                data-time="{{ $time }}">
                                            <span class="block font-semibold">{{ $time }}</span>
                                            <span class="block text-[10px] text-gray-400">Studio 1</span>
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- PILIH KURSI --}}
                        <div class="bg-black/75 border border-white/12 rounded-3xl p-4 sm:p-6 space-y-4 backdrop-blur-sm">
                            <div class="flex items-center justify-between">
                                <h2 class="text-sm font-semibold tracking-[0.15em] uppercase text-gray-200">
                                    Pilih kursi
                                </h2>
                                <div class="flex items-center gap-3 text-[11px] text-gray-400">
                                    <div class="flex items-center gap-1">
                                        <span class="w-3 h-3 rounded-sm border border-white/20 bg-white/5"></span>
                                        <span>Tersedia</span>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <span class="w-3 h-3 rounded-sm bg-red-500 border border-red-500"></span>
                                        <span>Dipilih</span>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <span class="w-3 h-3 rounded-sm bg-gray-500/60 border border-gray-500/70"></span>
                                        <span>Terisi</span>
                                    </div>
                                </div>
                            </div>

                            <div class="max-w-md mx-auto mt-2">
                                {{-- LAYAR --}}
                                <div class="mb-6">
                                    <div class="h-1.5 w-full rounded-full bg-gradient-to-r from-white/5 via-white/60 to-white/5 shadow-lg shadow-red-500/50"></div>
                                    <p class="mt-2 text-[10px] text-center uppercase tracking-[0.35em] text-gray-400">
                                        Layar
                                    </p>
                                </div>

                                {{-- DENAH KURSI --}}
                                <div class="space-y-2 text-[11px]">
                                    @foreach($seatRows as $row)
                                        <div class="flex items-center justify-center gap-2">
                                            <span class="w-4 text-right text-gray-500">{{ $row }}</span>
                                            <div class="flex gap-2">
                                                @foreach($seatCols as $col)
                                                    {{-- Sedikit jarak di tengah untuk lorong --}}
                                                    @if($col == 6)
                                                        <span class="w-4 sm:w-5"></span>
                                                    @endif

                                                    @php
                                                        // Contoh beberapa kursi yang dianggap sudah terisi
                                                        $occupied = in_array($row.$col, ['C4','C5','D7','D8']);
                                                    @endphp

                                                    @if($occupied)
                                                        <div
                                                            class="w-7 h-7 sm:w-8 sm:h-8 rounded-md border border-gray-600 bg-gray-500/70 text-[10px] sm:text-xs flex items-center justify-center text-gray-900/80">
                                                            {{ $col }}
                                                        </div>
                                                    @else
                                                        <button type="button"
                                                                class="seat-btn w-7 h-7 sm:w-8 sm:h-8 rounded-md border border-white/15 bg-white/5 text-[10px] sm:text-xs flex items-center justify-center text-gray-100 hover:bg-red-500 hover:border-red-500 transition"
                                                                data-seat="{{ $row }}{{ $col }}">
                                                            {{ $col }}
                                                        </button>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- KANAN: RINGKASAN PESANAN --}}
                    <div class="bg-black/80 border border-white/15 rounded-3xl p-5 sm:p-6 space-y-5 backdrop-blur-md">
                        <p class="text-[11px] uppercase tracking-[0.25em] text-gray-400">
                            Ringkasan Pesanan
                        </p>

                        <div class="space-y-1">
                            <h2 class="text-lg font-semibold">
                                {{ $featuredMovie?->title ?? 'Belum ada film' }}
                            </h2>
                            <p class="text-xs text-gray-400">
                                {{ $featuredMovie?->duration_minutes ? $featuredMovie->duration_minutes.' menit' : '' }}
                                @if($featuredMovie && $featuredMovie->genre)
                                    • {{ $featuredMovie->genre }}
                                @endif
                            </p>
                        </div>

                        {{-- INFO JADWAL --}}
                        <div class="space-y-2 text-sm">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-400">Tanggal</span>
                                <span id="summaryDate" class="text-gray-100 text-right">
                                    Belum dipilih
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-400">Jam</span>
                                <span id="summaryTime" class="text-gray-100 text-right">
                                    Belum dipilih
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-400">Studio</span>
                                <span id="summaryStudio" class="text-gray-100 text-right">
                                    Studio 1 • Regular
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-400">Kursi</span>
                                <span id="summarySeats" class="text-gray-100 text-right">
                                    Belum dipilih
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-400">Tipe tiket</span>
                                <span id="summaryTicketType" class="text-gray-100 text-right">
                                    Regular
                                </span>
                            </div>

                            <input type="hidden" id="selectedSeatsInput" name="seats">
                        </div>

                        {{-- PILIH TIPE TIKET & JUMLAH --}}
                        <div class="pt-4 border-t border-white/10 space-y-4 text-sm">
                            <div>
                                <p class="text-[11px] text-gray-400 uppercase tracking-[0.18em] mb-2">
                                    Tipe tiket
                                </p>
                                <div class="grid grid-cols-2 gap-3 text-xs">
                                    <button type="button"
                                            class="ticket-type-btn flex flex-col items-start gap-0.5 px-3 py-3 rounded-2xl border border-white/15 bg-white/5 text-gray-100 hover:bg-white/10 transition"
                                            data-type="Regular"
                                            data-price="45000">
                                        <span class="font-semibold text-sm">Regular</span>
                                        <span class="text-[11px] text-gray-400">Baris tengah & belakang</span>
                                        <span class="text-[11px] mt-1">Rp45.000</span>
                                    </button>

                                    <button type="button"
                                            class="ticket-type-btn flex flex-col items-start gap-0.5 px-3 py-3 rounded-2xl border border-white/15 bg-white/5 text-gray-100 hover:bg-white/10 transition"
                                            data-type="VIP Sofa"
                                            data-price="65000">
                                        <span class="font-semibold text-sm">VIP Sofa</span>
                                        <span class="text-[11px] text-gray-400">Baris depan & sofa</span>
                                        <span class="text-[11px] mt-1">Rp65.000</span>
                                    </button>
                                </div>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-gray-400">Jumlah tiket</span>
                                <div class="flex items-center gap-2">
                                    <button type="button" id="qtyMinus"
                                            class="w-7 h-7 rounded-full border border-white/20 bg-white/5 flex items-center justify-center text-sm hover:bg-white/10">
                                        −
                                    </button>
                                    <span id="qtyDisplay" class="w-6 text-center">
                                        1
                                    </span>
                                    <button type="button" id="qtyPlus"
                                            class="w-7 h-7 rounded-full border border-white/20 bg-white/5 flex items-center justify-center text-sm hover:bg-white/10">
                                        +
                                    </button>
                                </div>
                            </div>

                            <div class="space-y-1 text-sm">
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-400">Harga per tiket</span>
                                    <span id="pricePerTicket" class="font-semibold">
                                        Rp45.000
                                    </span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-400">Biaya layanan</span>
                                    <span id="serviceFee" class="text-gray-200">
                                        Rp5.000
                                    </span>
                                </div>
                            </div>

                            <div class="pt-2 flex items-center justify-between text-base font-semibold">
                                <span>Total bayar</span>
                                <span id="totalPrice">
                                    Rp50.000
                                </span>
                            </div>

                            <button
                                class="w-full mt-2 inline-flex items-center justify-center gap-2 bg-red-600 hover:bg-red-700 text-sm font-semibold py-3 rounded-full shadow-lg shadow-red-600/40">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>Lanjut ke pembayaran</span>
                            </button>

                            <p class="text-[11px] text-gray-500 text-center">
                                Dengan melanjutkan, kamu menyetujui ketentuan & kebijakan CinemaVerse.
                            </p>
                        </div>
                    </div>
                </section>

                {{-- FILM LAINNYA --}}
                @if($otherMovies->count())
                    <section class="mt-2 space-y-3">
                        <div class="flex items-center justify-between mb-1">
                            <h2 class="text-[11px] uppercase tracking-[0.25em] text-gray-400">
                                Film lainnya
                            </h2>

                            <a href="{{ route('home') }}"
                               class="flex items-center gap-2 text-[11px] text-gray-300 hover:text-white">
                                Lihat semua
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="1.8"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>

                        <div class="nice-scroll flex gap-4 overflow-x-auto pb-4 pt-1">
                            @foreach($otherMovies as $movie)
                                <a href="{{ route('movies.show', $movie->id) }}"
                                   class="group relative min-w-[220px] bg-black/70 border border-white/15 rounded-2xl overflow-hidden backdrop-blur-sm hover:border-red-500/70 transition">
                                    {{-- Thumbnail --}}
                                    <div class="h-28 overflow-hidden">
                                        <img src="{{ $movie->poster_url }}" alt="{{ $movie->title }}"
                                             class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                                        <div class="absolute inset-x-0 bottom-0 h-16 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>
                                    </div>

                                    <div class="relative px-4 pb-4 pt-2 space-y-1">
                                        <div class="text-xs text-gray-400 uppercase tracking-[0.18em]">
                                            Rekomendasi
                                        </div>
                                        <div class="text-sm font-semibold group-hover:text-red-400 transition line-clamp-2">
                                            {{ $movie->title }}
                                        </div>
                                        <div class="flex justify-between items-center text-[11px] text-gray-400">
                                            <span>⭐ {{ number_format($movie->rating ?? 0, 1) }}/10</span>
                                            <span>{{ $movie->duration_minutes }} menit</span>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </section>
                @endif

            </main>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dateButtons = document.querySelectorAll('.date-btn');
        const timeButtons = document.querySelectorAll('.time-btn');
        const seatButtons = document.querySelectorAll('.seat-btn');
        const ticketTypeButtons = document.querySelectorAll('.ticket-type-btn');

        const summaryDate = document.getElementById('summaryDate');
        const summaryTime = document.getElementById('summaryTime');
        const summarySeats = document.getElementById('summarySeats');
        const summaryTicketType = document.getElementById('summaryTicketType');

        const qtyMinus = document.getElementById('qtyMinus');
        const qtyPlus = document.getElementById('qtyPlus');
        const qtyDisplay = document.getElementById('qtyDisplay');
        const pricePerTicket = document.getElementById('pricePerTicket');
        const totalPrice = document.getElementById('totalPrice');
        const serviceFeeEl = document.getElementById('serviceFee');
        const selectedSeatsInput = document.getElementById('selectedSeatsInput');

        const SERVICE_FEE = 5000;
        let currentPrice = 45000;
        let qty = 1;
        const selectedSeats = new Set();

        function formatRupiah(value) {
            return 'Rp' + Number(value).toLocaleString('id-ID');
        }

        function updateTotal() {
            const total = currentPrice * qty + SERVICE_FEE;
            pricePerTicket.textContent = formatRupiah(currentPrice);
            serviceFeeEl.textContent = formatRupiah(SERVICE_FEE);
            totalPrice.textContent = formatRupiah(total);
            qtyDisplay.textContent = qty;
        }

        // Tanggal
        dateButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                dateButtons.forEach(b => {
                    b.classList.remove('bg-red-600', 'border-red-500', 'text-white');
                    b.classList.add('bg-white/5', 'border-white/15', 'text-gray-200');
                });

                btn.classList.remove('bg-white/5', 'border-white/15', 'text-gray-200');
                btn.classList.add('bg-red-600', 'border-red-500', 'text-white');

                if (summaryDate) {
                    summaryDate.textContent = btn.dataset.dateDisplay || 'Dipilih';
                }
            });
        });

        // Jam
        timeButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                timeButtons.forEach(b => {
                    b.classList.remove('bg-red-600', 'border-red-500', 'text-white');
                    b.classList.add('bg-white/5', 'border-white/15', 'text-gray-200');
                });

                btn.classList.remove('bg-white/5', 'border-white/15', 'text-gray-200');
                btn.classList.add('bg-red-600', 'border-red-500', 'text-white');

                if (summaryTime) {
                    summaryTime.textContent = btn.dataset.time || 'Dipilih';
                }
            });
        });

        // Kursi
        seatButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                const code = btn.dataset.seat;
                if (!code) return;

                if (selectedSeats.has(code)) {
                    selectedSeats.delete(code);
                    btn.classList.remove('bg-red-500', 'border-red-500', 'text-white');
                    btn.classList.add('bg-white/5', 'border-white/15', 'text-gray-100');
                } else {
                    selectedSeats.add(code);
                    btn.classList.remove('bg-white/5', 'border-white/15', 'text-gray-100');
                    btn.classList.add('bg-red-500', 'border-red-500', 'text-white');
                }

                const seatsArray = Array.from(selectedSeats).sort();
                if (summarySeats) {
                    summarySeats.textContent = seatsArray.length ? seatsArray.join(', ') : 'Belum dipilih';
                }
                if (selectedSeatsInput) {
                    selectedSeatsInput.value = seatsArray.join(',');
                }

                // Sesuaikan jumlah tiket minimal dengan jumlah kursi
                if (seatsArray.length > 0) {
                    qty = Math.max(qty, seatsArray.length);
                    updateTotal();
                }
            });
        });

        // Tipe tiket
        ticketTypeButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                ticketTypeButtons.forEach(b => {
                    b.classList.remove('bg-red-600', 'border-red-500', 'text-white');
                    b.classList.add('bg-white/5', 'border-white/15', 'text-gray-100');
                });

                btn.classList.remove('bg-white/5', 'border-white/15', 'text-gray-100');
                btn.classList.add('bg-red-600', 'border-red-500', 'text-white');

                const price = Number(btn.dataset.price || 0);
                const type  = btn.dataset.type || 'Regular';

                if (!isNaN(price) && price > 0) {
                    currentPrice = price;
                }
                if (summaryTicketType) {
                    summaryTicketType.textContent = type;
                }
                updateTotal();
            });
        });

        // Jumlah tiket (+ / -)
        if (qtyMinus && qtyPlus) {
            qtyMinus.addEventListener('click', () => {
                if (qty > 1) {
                    qty--;
                    updateTotal();
                }
            });

            qtyPlus.addEventListener('click', () => {
                qty++;
                updateTotal();
            });
        }

        // Set nilai awal
        if (dateButtons.length) {
            dateButtons[0].click();
        }
        if (timeButtons.length) {
            timeButtons[0].click();
        }
        if (ticketTypeButtons.length) {
            ticketTypeButtons[0].click();
        } else {
            updateTotal();
        } 
    });
</script>

</body>
</html>