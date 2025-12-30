<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CinemaVerse - Pesan Tiket</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        .nice-scroll::-webkit-scrollbar { height: 6px; }
        .nice-scroll::-webkit-scrollbar-track { background: transparent; }
        .nice-scroll::-webkit-scrollbar-thumb { background: rgba(148, 163, 184, 0.6); border-radius: 999px; }
    </style>
</head>
<body class="bg-[#05070b] text-white antialiased">

@php
    use Carbon\Carbon;

    $featuredMovie = $movie;
    $otherMovies   = $movies->where('id', '!=', $movie->id);

    $groupedDates = $showtimes->groupBy(fn($st) => Carbon::parse($st->start_time)->format('Y-m-d'));
@endphp

<div class="min-h-screen w-full">
    <div class="relative w-full min-h-screen overflow-hidden border border-white/10 bg-black/80 rounded-none">

        {{-- Background --}}
        <div class="absolute inset-0">
            @if($featuredMovie && $featuredMovie->poster_url)
                <img src="{{ $featuredMovie->poster_url }}" alt="{{ $featuredMovie->title }}" class="w-full h-full object-cover opacity-60">
            @else
                <div class="w-full h-full bg-gradient-to-tr from-slate-900 via-slate-800 to-slate-900"></div>
            @endif
            <div class="absolute inset-0 bg-gradient-to-r from-black via-black/85 to-black/30"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-black/60"></div>
        </div>

        <div class="relative flex h-full">

            {{-- SIDEBAR --}}
            <aside class="hidden sm:flex flex-col w-60 bg-black/75 backdrop-blur-sm border-r border-white/10">
                <div class="px-8 pt-8 pb-6 text-2xl font-semibold tracking-[0.25em]">
                    <span class="text-red-500">cinema</span><span>VERSE</span>
                </div>

                <nav class="flex-1 px-8 space-y-4 text-sm">
                    <a href="{{ route('home') }}" class="block text-gray-300 hover:text-white transition">Home</a>
                    <a href="{{ route('booking.index') }}" class="block text-gray-300 hover:text-white transition">Booking Saya</a>

                    <div class="flex items-center gap-2 bg-white/10 text-white px-4 py-2 rounded-full shadow-lg shadow-red-500/30">
                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                        <span class="uppercase tracking-[0.25em] text-[11px]">Tiket</span>
                    </div>

                    <div class="pt-6 text-xs text-gray-500 uppercase tracking-[0.2em]">Account</div>
                    <button class="block text-left w-full text-gray-300/80 hover:text-white transition text-sm">Setting</button>
                    <button class="block text-left w-full text-gray-300/80 hover:text-white transition text-sm">My profile</button>
                </nav>

                <div class="px-8 pb-6 text-[11px] text-gray-500">© {{ date('Y') }} CinemaVerse</div>
            </aside>

            {{-- CONTENT --}}
            <main class="flex-1 px-5 sm:px-8 md:px-10 py-7 md:py-10 flex flex-col gap-8">

                {{-- TOP BAR --}}
                <div class="flex items-center justify-between mb-1 gap-4">
                    <button type="button" onclick="history.back()"
                            class="inline-flex items-center gap-2 text-xs text-gray-300 hover:text-white">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                        </svg>
                        <span>Kembali</span>
                    </button>

                    <form action="{{ route('movie.search') }}" method="GET"
                          class="hidden md:flex items-center bg-black/60 border border-white/15 rounded-full px-4 py-1.5 text-xs">
                        <input type="text" name="q" placeholder="Cari judul film..."
                               class="bg-transparent focus:outline-none placeholder:text-gray-500 text-gray-100 w-44 lg:w-64">
                        <button type="submit" class="ml-3 px-3 py-1 rounded-full bg-red-600 hover:bg-red-700 transition">Cari</button>
                    </form>
                </div>

                {{-- HEADER --}}
                <section class="space-y-4">
                    <div class="flex flex-col md:flex-row md:items-center gap-5">
                        <div class="w-full md:w-40 lg:w-48 rounded-2xl overflow-hidden border border-white/15 bg-black/60">
                            @if($featuredMovie->poster_url)
                                <img src="{{ $featuredMovie->poster_url }}" alt="{{ $featuredMovie->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-slate-800 to-slate-900"></div>
                            @endif
                        </div>

                        <div class="space-y-2 md:flex-1">
                            <p class="text-[11px] tracking-[0.35em] uppercase text-red-400">Pemesanan tiket</p>

                            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold leading-tight">
                                {{ $featuredMovie->title }}
                            </h1>

                            <p class="text-sm sm:text-base text-gray-200/90 leading-relaxed line-clamp-3">
                                {{ $featuredMovie->description }}
                            </p>
                        </div>
                    </div>

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

                {{-- GRID --}}
                <section class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

                    {{-- LEFT --}}
                    <div class="lg:col-span-2 space-y-5">

                        {{-- JADWAL --}}
                        <div class="bg-black/75 border border-white/12 rounded-3xl p-4 sm:p-6 space-y-4 backdrop-blur-sm">
                            <div class="flex items-center justify-between">
                                <h2 class="text-sm font-semibold tracking-[0.15em] uppercase text-gray-200">Pilih jadwal</h2>
                                <p class="text-[11px] text-gray-400">Pilih hari & jam tayang</p>
                            </div>

                            @if($showtimes->isEmpty())
                                <div class="text-sm text-gray-300">Belum ada jadwal tayang untuk film ini.</div>
                            @else
                                {{-- TANGGAL --}}
                                <div class="space-y-2">
                                    <p class="text-[11px] text-gray-400 uppercase tracking-[0.18em]">Tanggal</p>
                                    <div class="nice-scroll flex gap-3 overflow-x-auto pb-2 pt-1">
                                        @foreach($groupedDates->keys() as $date)
                                            @php
                                                $c = Carbon::parse($date);
                                                $dayName = $c->translatedFormat('D');
                                                $dayNum  = $c->format('d');
                                                $month   = $c->translatedFormat('M');
                                            @endphp
                                            <button type="button"
                                                class="date-btn flex flex-col justify-between min-w-[76px] px-3 py-2 rounded-2xl border border-white/15 bg-white/5 text-gray-200 text-xs hover:bg-white/10 transition"
                                                data-date="{{ $date }}"
                                                data-date-display="{{ $dayName }}, {{ $dayNum }} {{ $month }}">
                                                <span class="text-[11px] uppercase tracking-[0.18em] text-gray-400">{{ $dayName }}</span>
                                                <span class="text-base font-semibold leading-snug">{{ $dayNum }}</span>
                                                <span class="text-[11px] text-gray-300">{{ $month }}</span>
                                            </button>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- JAM (SHOWTIME) --}}
                                <div class="space-y-2">
                                    <p class="text-[11px] text-gray-400 uppercase tracking-[0.18em]">Jam tayang</p>
                                    <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-5 gap-3">
                                        @foreach($showtimes as $st)
                                            @php
                                                $d = Carbon::parse($st->start_time)->format('Y-m-d');
                                                $t = Carbon::parse($st->start_time)->format('H:i');
                                            @endphp
                                            <button type="button"
                                                class="time-btn hidden px-3 py-2 rounded-2xl border border-white/15 bg-white/5 text-xs text-gray-200 hover:bg-white/10 transition"
                                                data-date="{{ $d }}"
                                                data-showtime-id="{{ $st->id }}"
                                                data-time="{{ $t }}">
                                                <span class="block font-semibold">{{ $t }}</span>
                                                <span class="block text-[10px] text-gray-400">{{ $st->studio->name }}</span>
                                                <span class="block text-[10px] text-green-400 font-semibold">
                                                    Rp{{ number_format($st->price, 0, ',', '.') }}
                                                </span>
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- KURSI --}}
                        <div class="bg-black/75 border border-white/12 rounded-3xl p-4 sm:p-6 space-y-4 backdrop-blur-sm">
                            <div class="flex items-center justify-between">
                                <h2 class="text-sm font-semibold tracking-[0.15em] uppercase text-gray-200">Pilih kursi</h2>
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
                                <div class="mb-6">
                                    <div class="h-1.5 w-full rounded-full bg-gradient-to-r from-white/5 via-white/60 to-white/5 shadow-lg shadow-red-500/50"></div>
                                    <p class="mt-2 text-[10px] text-center uppercase tracking-[0.35em] text-gray-400">Layar</p>
                                </div>

                                <div id="seat-grid" class="space-y-2 text-[11px]">
                                    <div class="text-center text-xs text-gray-400">
                                        Pilih jam tayang dulu untuk menampilkan kursi.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- RIGHT SUMMARY --}}
                    <div class="bg-black/80 border border-white/15 rounded-3xl p-5 sm:p-6 space-y-5 backdrop-blur-md">
                        <p class="text-[11px] uppercase tracking-[0.25em] text-gray-400">Ringkasan Pesanan</p>

                        <div class="space-y-1">
                            <h2 class="text-lg font-semibold">{{ $featuredMovie->title }}</h2>
                            <p class="text-xs text-gray-400">
                                {{ $featuredMovie->duration_minutes ? $featuredMovie->duration_minutes.' menit' : '' }}
                                @if($featuredMovie->genre) • {{ $featuredMovie->genre }} @endif
                            </p>
                        </div>

                        <div class="space-y-2 text-sm">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-400">Tanggal</span>
                                <span id="summaryDate" class="text-gray-100 text-right">Belum dipilih</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-400">Jam</span>
                                <span id="summaryTime" class="text-gray-100 text-right">Belum dipilih</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-400">Studio</span>
                                <span id="summaryStudio" class="text-gray-100 text-right">Belum dipilih</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-400">Kursi</span>
                                <span id="summarySeats" class="text-gray-100 text-right">Belum dipilih</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-400">Tipe tiket</span>
                                <span id="summaryTicketType" class="text-gray-100 text-right">-</span>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-white/10 space-y-3 text-sm">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-400">Harga / kursi</span>
                                <span id="pricePerSeat" class="font-semibold">Rp0</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-400">Jumlah kursi</span>
                                <span id="qtySeat" class="font-semibold">0</span>
                            </div>
                            <div class="pt-2 flex items-center justify-between text-base font-semibold">
                                <span>Total bayar</span>
                                <span id="totalPrice">Rp0</span>
                            </div>

                            <form id="bookingForm" action="{{ route('booking.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="showtime_id" id="showtimeId">
                                <div id="seatInputs"></div>

                                <button type="button" id="payBtn"
                                    class="w-full mt-2 inline-flex items-center justify-center gap-2 bg-red-600 hover:bg-red-700 text-sm font-semibold py-3 rounded-full shadow-lg shadow-red-600/40">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span>Lanjut ke pembayaran</span>
                                </button>
                            </form>

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
                            <h2 class="text-[11px] uppercase tracking-[0.25em] text-gray-400">Film lainnya</h2>
                            <a href="{{ route('home') }}" class="flex items-center gap-2 text-[11px] text-gray-300 hover:text-white">
                                Lihat semua
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>

                        <div class="nice-scroll flex gap-4 overflow-x-auto pb-4 pt-1">
                            @foreach($otherMovies as $m)
                                <a href="{{ route('movies.show', $m->id) }}"
                                   class="group relative min-w-[220px] bg-black/70 border border-white/15 rounded-2xl overflow-hidden backdrop-blur-sm hover:border-red-500/70 transition">
                                    <div class="h-28 overflow-hidden">
                                        <img src="{{ $m->poster_url }}" alt="{{ $m->title }}"
                                             class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                                        <div class="absolute inset-x-0 bottom-0 h-16 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>
                                    </div>

                                    <div class="relative px-4 pb-4 pt-2 space-y-1">
                                        <div class="text-xs text-gray-400 uppercase tracking-[0.18em]">Rekomendasi</div>
                                        <div class="text-sm font-semibold group-hover:text-red-400 transition line-clamp-2">
                                            {{ $m->title }}
                                        </div>
                                        <div class="flex justify-between items-center text-[11px] text-gray-400">
                                            <span>⭐ {{ number_format($m->rating ?? 0, 1) }}/10</span>
                                            <span>{{ $m->duration_minutes }} menit</span>
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
document.addEventListener('DOMContentLoaded', () => {
  const dateBtns = document.querySelectorAll('.date-btn');
  const timeBtns = document.querySelectorAll('.time-btn');

  const summaryDate = document.getElementById('summaryDate');
  const summaryTime = document.getElementById('summaryTime');
  const summaryStudio = document.getElementById('summaryStudio');
  const summarySeats = document.getElementById('summarySeats');
  const summaryTicketType = document.getElementById('summaryTicketType');

  const pricePerSeat = document.getElementById('pricePerSeat');
  const qtySeat = document.getElementById('qtySeat');
  const totalPrice = document.getElementById('totalPrice');

  const seatGrid = document.getElementById('seat-grid');
  const showtimeIdInput = document.getElementById('showtimeId');
  const seatInputs = document.getElementById('seatInputs');

  let selectedShowtimeId = null;
  let selectedSeatObjs = [];
  let selectedPrice = 0;

  function rupiah(n) {
    return 'Rp' + Number(n || 0).toLocaleString('id-ID');
  }

  function setActive(btn, group) {
    group.forEach(b => {
      b.classList.remove('bg-red-600','border-red-500','text-white');
      b.classList.add('bg-white/5','border-white/15','text-gray-200');
    });
    btn.classList.remove('bg-white/5','border-white/15','text-gray-200');
    btn.classList.add('bg-red-600','border-red-500','text-white');
  }

  function updateSummary() {
    summarySeats.textContent = selectedSeatObjs.length
      ? selectedSeatObjs.map(s => s.label).join(', ')
      : 'Belum dipilih';

    qtySeat.textContent = String(selectedSeatObjs.length);
    pricePerSeat.textContent = rupiah(selectedPrice);
    totalPrice.textContent = rupiah(selectedSeatObjs.length * selectedPrice);

    // build seat_ids[]
    seatInputs.innerHTML = '';
    selectedSeatObjs.forEach(s => {
      const input = document.createElement('input');
      input.type = 'hidden';
      input.name = 'seat_ids[]';
      input.value = s.id;
      seatInputs.appendChild(input);
    });
  }

  function renderSeats(seats, occupiedIds) {
    seatGrid.innerHTML = '';
    selectedSeatObjs = [];

    // group by row_label
    const rows = {};
    seats.forEach(s => {
      if (!rows[s.row_label]) rows[s.row_label] = [];
      rows[s.row_label].push(s);
    });

    Object.keys(rows).sort().forEach(rowLabel => {
      const rowWrap = document.createElement('div');
      rowWrap.className = 'flex items-center justify-center gap-2';

      const rowText = document.createElement('span');
      rowText.className = 'w-4 text-right text-gray-500';
      rowText.textContent = rowLabel;

      const seatsWrap = document.createElement('div');
      seatsWrap.className = 'flex flex-wrap gap-2 justify-center max-w-md';

      rows[rowLabel].sort((a,b) => a.seat_number - b.seat_number).forEach(seat => {
        const taken = occupiedIds.includes(seat.id);
        const usable = !!seat.is_usable;

        const b = document.createElement('button');
        b.type = 'button';
        b.textContent = seat.seat_number;

        const base = 'w-7 h-7 sm:w-8 sm:h-8 rounded-md border text-[10px] sm:text-xs flex items-center justify-center transition';
        const disabled = 'bg-gray-500/60 border-gray-500/70 text-gray-900/80 cursor-not-allowed';
        const active = 'bg-white/5 border-white/15 text-gray-100 hover:bg-red-500 hover:border-red-500';

        b.className = `${base} ${(!usable || taken) ? disabled : active}`;
        b.disabled = (!usable || taken);

        b.addEventListener('click', () => {
          const idx = selectedSeatObjs.findIndex(x => x.id === seat.id);
          if (idx >= 0) {
            selectedSeatObjs.splice(idx, 1);
            b.classList.remove('bg-red-500','border-red-500');
          } else {
            selectedSeatObjs.push({ id: seat.id, label: seat.row_label + seat.seat_number });
            b.classList.add('bg-red-500','border-red-500');
          }
          updateSummary();
        });

        seatsWrap.appendChild(b);
      });

      rowWrap.appendChild(rowText);
      rowWrap.appendChild(seatsWrap);
      seatGrid.appendChild(rowWrap);
    });

    updateSummary();
  }

  // DATE CLICK -> filter times
  dateBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      setActive(btn, dateBtns);
      summaryDate.textContent = btn.dataset.dateDisplay || 'Dipilih';

      // show only matching date
      timeBtns.forEach(t => t.classList.toggle('hidden', t.dataset.date !== btn.dataset.date));

      // reset showtime and seats display
      selectedShowtimeId = null;
      selectedPrice = 0;
      showtimeIdInput.value = '';
      summaryTime.textContent = 'Belum dipilih';
      summaryStudio.textContent = 'Belum dipilih';
      summaryTicketType.textContent = '-';
      seatGrid.innerHTML = '<div class="text-center text-xs text-gray-400">Pilih jam tayang dulu untuk menampilkan kursi.</div>';
      selectedSeatObjs = [];
      updateSummary();

      // auto click first time on that date (optional)
      const firstTime = Array.from(timeBtns).find(x => x.dataset.date === btn.dataset.date);
      if (firstTime) firstTime.click();
    });
  });

  // TIME CLICK -> fetch showtime details and render seats
  timeBtns.forEach(btn => {
    btn.addEventListener('click', async () => {
      setActive(btn, timeBtns);
      summaryTime.textContent = btn.dataset.time || 'Dipilih';

      selectedShowtimeId = btn.dataset.showtimeId;
      showtimeIdInput.value = selectedShowtimeId;

      const res = await fetch(`/api/showtimes/${selectedShowtimeId}/details`);
      const data = await res.json();

      selectedPrice = data.price;
      summaryStudio.textContent = data.studio_name + ' • ' + data.branch_name;
      summaryTicketType.textContent = (data.studio_type === 'vip') ? 'VIP' : 'Regular';

      renderSeats(data.seats, data.occupied);
    });
  });

  // SUBMIT BOOKING
  document.getElementById('payBtn').addEventListener('click', () => {
    if (!showtimeIdInput.value) return alert('Pilih tanggal & jam tayang dulu.');
    if (selectedSeatObjs.length < 1) return alert('Pilih minimal 1 kursi.');
    document.getElementById('bookingForm').submit();
  });

  // init: click first date
  if (dateBtns.length) dateBtns[0].click();
});
</script>

</body>
</html>
