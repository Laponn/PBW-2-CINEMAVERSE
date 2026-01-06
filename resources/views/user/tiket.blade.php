@extends('layouts.app')

@section('content')
<style>
    /* Global Scrollbar */
    .nice-scroll::-webkit-scrollbar { height: 4px; width: 6px; }
    .nice-scroll::-webkit-scrollbar-thumb { background: rgba(220, 38, 38, 0.5); border-radius: 10px; }

    /* 3D Perspective System */
    .perspective-wrap { perspective: 2000px; }
    .premium-card {
        transform-style: preserve-3d;
        transition: transform 0.3s ease-out;
        background: rgba(24, 24, 27, 0.4);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    /* Step Buttons Styling */
    .btn-step {
        transition: all 0.4s cubic-bezier(0.23, 1, 0.32, 1);
        position: relative;
        overflow: hidden;
    }

    /* Date Button Active */
    .date-selected {
        background: #dc2626 !important;
        border-color: #ef4444 !important;
        transform: translateY(-8px) scale(1.05);
        box-shadow: 0 15px 30px rgba(220, 38, 38, 0.4);
    }

    /* Time Button Active */
    .time-selected {
        background: white !important;
        color: black !important;
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(255, 255, 255, 0.2);
    }
    .time-selected span { color: black !important; }

    /* Seat Styling */
    .seat-base {
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .seat-available:hover {
        transform: scale(1.2);
        background: rgba(220, 38, 38, 0.2);
        border-color: #dc2626;
    }
    .seat-selected {
        background: #dc2626 !important;
        border-color: #ff4d4d !important;
        box-shadow: 0 0 15px rgba(220, 38, 38, 0.8);
        transform: scale(1.2) translateY(-2px);
    }
    .seat-occupied {
        background: #18181b !important;
        opacity: 0.3;
        cursor: not-allowed;
        border: 1px solid transparent;
    }

    /* Screen Decoration */
    .screen-area {
        height: 8px;
        width: 100%;
        background: white;
        border-radius: 50%;
        filter: blur(2px);
        box-shadow: 0 10px 30px rgba(255, 255, 255, 0.5);
    }

    /* Summary Sidebar Glassmorphism */
    .summary-sidebar {
        background: linear-gradient(135deg, rgba(24, 24, 27, 0.8), rgba(9, 9, 11, 0.9));
        border: 1px solid rgba(255, 255, 255, 0.05);
    }
</style>

<div class="bg-[#050509] min-h-screen text-white pt-28 pb-16">
    <div class="max-w-screen-2xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-12 gap-10">

        <div class="lg:col-span-8 space-y-10">
            {{-- HEADER --}}
            <div class="space-y-2">
                <span class="px-4 py-1.5 rounded-full bg-red-600/10 border border-red-600/20 text-[10px] font-black text-red-500 uppercase tracking-widest italic">Reservation System</span>
                <h1 class="text-4xl md:text-5xl font-black italic uppercase tracking-tighter">Book Your <span class="text-red-600">Experience</span></h1>
            </div>

            {{-- STEP 1: PILIH TANGGAL --}}
            <div class="premium-card p-8 rounded-[3rem]">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-10 h-10 rounded-2xl bg-red-600 flex items-center justify-center font-black italic text-sm shadow-[0_0_15px_rgba(220,38,38,0.5)]">01</div>
                    <h3 class="text-xs font-black uppercase tracking-[0.3em] text-zinc-400">Pilih Tanggal Menonton</h3>
                </div>

                <div class="flex gap-4 overflow-x-auto pb-4 nice-scroll">
                    @forelse($availableDates as $date)
                        @php $cDate = \Illuminate\Support\Carbon::parse($date); @endphp
                        <button type="button"
                            class="date-btn btn-step min-w-[110px] p-6 rounded-[2rem] border border-white/5 bg-white/5 group"
                            data-date="{{ $date }}"
                            data-date-display="{{ $cDate->translatedFormat('D, d M') }}">
                            <span class="block text-[10px] uppercase text-zinc-500 font-black mb-2 tracking-widest group-hover:text-zinc-300">{{ $cDate->translatedFormat('D') }}</span>
                            <span class="block text-3xl font-black italic tracking-tighter">{{ $cDate->format('d') }}</span>
                            <span class="block text-[10px] text-zinc-400 font-bold uppercase mt-1">{{ $cDate->translatedFormat('M') }}</span>
                        </button>
                    @empty
                        <div class="p-10 w-full border-2 border-dashed border-white/5 rounded-[2.5rem] text-center">
                            <p class="text-zinc-600 italic font-black uppercase tracking-widest text-xs">Jadwal belum tersedia.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- STEP 2: PILIH JADWAL (JAM + DESKRIPSI STUDIO VIP/REGULER) --}}
            <div id="time-section" class="premium-card p-8 rounded-[3rem] hidden">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-10 h-10 rounded-2xl bg-red-600 flex items-center justify-center font-black italic text-sm shadow-[0_0_15px_rgba(220,38,38,0.5)]">02</div>
                    {{-- ✅ DIUBAH: Heading jadi Pilih Jadwal + deskripsi studio --}}
                    <h3 class="text-xs font-black uppercase tracking-[0.3em] text-zinc-400">Pilih Jadwal & Jenis Studio (VIP / Reguler)</h3>
                </div>

                <div id="time-container" class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    @foreach($showtimes as $st)
                        @php
                            $studioTypeRaw  = $st->studio->type ?? '';
                            $studioTypeUp   = strtoupper($studioTypeRaw);
                            $isVip          = str_contains($studioTypeUp, 'VIP');

                            $studioLabel = $isVip ? 'VIP' : 'REGULER';
                            $studioDesc  = $isVip
                                ? 'Kursi recliner premium • Ruang kaki luas • Suasana lebih eksklusif'
                                : 'Kursi standar nyaman • Cocok untuk semua penonton';
                        @endphp

                        <button type="button"
                            class="time-btn btn-step p-6 rounded-[2.5rem] border border-white/5 bg-white/5 text-left hidden group"
                            data-date-ref="{{ \Illuminate\Support\Carbon::parse($st->start_time)->format('Y-m-d') }}"
                            data-id="{{ $st->id }}"
                            data-time="{{ \Illuminate\Support\Carbon::parse($st->start_time)->format('H:i') }}"
                            {{-- ✅ tambahan dataset supaya summary bisa pakai info VIP/Reguler --}}
                            data-studio-name="{{ $st->studio->name }}"
                            data-studio-type="{{ $studioLabel }}"
                            data-studio-desc="{{ $studioDesc }}"
                        >
                            <span class="block font-black text-3xl italic tracking-tighter">{{ \Illuminate\Support\Carbon::parse($st->start_time)->format('H:i') }}</span>

                            {{-- ✅ DIUBAH: studio jadi deskripsi VIP/Reguler --}}
                            <div class="mt-4 space-y-2">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest italic border
                                            {{ $isVip ? 'bg-red-600/20 text-red-400 border-red-600/30' : 'bg-white/5 text-zinc-300 border-white/10' }}">
                                            {{ $studioLabel }}
                                        </span>
                                        <span class="text-[9px] text-zinc-500 font-black uppercase tracking-widest">{{ $st->studio->name }}</span>
                                    </div>

                                    <span class="text-[10px] text-red-500 font-black italic">Rp {{ number_format($st->price, 0, ',', '.') }}</span>
                                </div>

                                <p class="text-[10px] text-zinc-500 font-semibold leading-snug">
                                    {{ $studioDesc }}
                                </p>
                            </div>
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- STEP 3: PILIH KURSI --}}
            <div id="seat-section" class="premium-card p-10 rounded-[4rem] hidden">
                <div class="screen-container mb-16 px-10">
                    <div class="screen-area"></div>
                    <p class="text-center text-[8px] font-black uppercase tracking-[1em] text-zinc-700 mt-6">Cinema Screen Area</p>
                </div>

                {{-- Legend --}}
                <div class="flex flex-wrap justify-center gap-8 mb-12">
                    <div class="flex items-center gap-3">
                        <div class="w-4 h-4 rounded-md border border-white/10 bg-white/5"></div>
                        <span class="text-[9px] font-black text-zinc-500 uppercase tracking-widest">Available</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-4 h-4 rounded-md bg-red-600 shadow-[0_0_10px_rgba(220,38,38,0.5)]"></div>
                        <span class="text-[9px] font-black text-zinc-500 uppercase tracking-widest">Your Choice</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-4 h-4 rounded-md bg-zinc-800"></div>
                        <span class="text-[9px] font-black text-zinc-500 uppercase tracking-widest">Sold Out</span>
                    </div>
                </div>

                <div id="seat-grid" class="space-y-4 overflow-x-auto nice-scroll pb-6"></div>
            </div>
        </div>

        {{-- SIDEBAR SUMMARY --}}
        <div class="lg:col-span-4">
            <div class="summary-sidebar p-10 rounded-[3.5rem] sticky top-28 shadow-2xl space-y-10 overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-red-600/10 blur-3xl -mr-16 -mt-16"></div>

                <div class="relative z-10">
                    <span class="text-[9px] font-black text-red-600 uppercase tracking-[0.4em] mb-3 block">Booking Summary</span>
                    <h2 class="text-3xl font-black italic tracking-tighter uppercase leading-none">{{ $movie->title }}</h2>
                    <div class="flex items-center gap-2 mt-4">
                        <svg class="w-3 h-3 text-zinc-500" fill="currentColor" viewBox="0 0 20 20"><path d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"/></svg>
                        <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">{{ session('branch_name', 'CinemaVerse City') }}</p>
                    </div>
                </div>

                <div class="space-y-5 text-[11px] font-black border-t border-white/5 pt-8 uppercase tracking-[0.2em] relative z-10">
                    <div class="flex justify-between items-center"><span class="text-zinc-500">Studio</span><span id="sum-studio" class="text-white">-</span></div>
                    <div class="flex justify-between items-center"><span class="text-zinc-500">Waktu</span><span id="sum-time" class="text-white">-</span></div>
                    <div class="flex justify-between items-center"><span class="text-zinc-500">Kursi</span><span id="sum-seats" class="text-red-500 italic">-</span></div>
                </div>

                <div class="pt-8 border-t border-white/5 relative z-10">
                    <div class="flex justify-between items-end mb-10">
                        <div>
                            <span class="text-[9px] font-black uppercase text-zinc-600 tracking-widest block mb-1">Total Payment</span>
                            <span id="sum-total" class="text-4xl font-black italic text-white tracking-tighter">Rp 0</span>
                        </div>
                    </div>

                    <form action="{{ route('booking.store') }}" method="POST" id="bookingForm">
                        @csrf
                        <input type="hidden" name="showtime_id" id="input-showtime">
                        <div id="seat-inputs"></div>
                        <button type="submit" id="checkoutBtn" disabled class="w-full bg-zinc-800 py-6 rounded-3xl font-black uppercase tracking-[0.3em] text-[11px] text-zinc-600 cursor-not-allowed transition-all shadow-xl group">
                            <span class="group-hover:tracking-[0.4em] transition-all italic">Pilih Kursi Dahulu</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let selectedPrice = 0;
let selectedSeats = [];
let activeDateLabel = "";

function resetSummary() {
    // ✅ dibenerin sekalian biar bener-bener reset
    selectedPrice = 0;
    selectedSeats = [];
    document.getElementById('input-showtime').value = '';
    document.getElementById('seat-inputs').innerHTML = '';

    document.getElementById('sum-studio').textContent = '-';
    document.getElementById('sum-time').textContent = '-';
    document.getElementById('sum-seats').textContent = '-';
    document.getElementById('sum-total').textContent = 'Rp 0';
    updateSummary();
}

function handleDateClick(btn) {
    document.querySelectorAll('.date-btn').forEach(b => b.classList.remove('date-selected'));
    btn.classList.add('date-selected');

    activeDateLabel = btn.dataset.dateDisplay;
    const selectedDate = btn.dataset.date;

    document.getElementById('time-section').classList.remove('hidden');
    document.querySelectorAll('.time-btn').forEach(t => {
        t.classList.toggle('hidden', t.dataset.dateRef !== selectedDate);
        t.classList.remove('time-selected');
    });

    document.getElementById('seat-section').classList.add('hidden');
    document.getElementById('seat-grid').innerHTML = '';
    resetSummary();

    // Smooth scroll ke jam
    document.getElementById('time-section').scrollIntoView({ behavior: 'smooth', block: 'center' });
}

async function handleTimeClick(btn) {
    document.querySelectorAll('.time-btn').forEach(b => b.classList.remove('time-selected'));
    btn.classList.add('time-selected');

    const showtimeId = btn.dataset.id;
    const timeLabel = btn.dataset.time;
    const grid = document.getElementById('seat-grid');

    document.getElementById('seat-section').classList.remove('hidden');
    grid.innerHTML = '<div class="py-20 text-center text-[10px] font-black uppercase tracking-[0.5em] animate-pulse text-red-600">Initializing Cinema Hall...</div>';

    // ✅ DIUBAH: summary studio pakai info VIP/Reguler dari tombol
    const studioName = btn.dataset.studioName || '';
    const studioType = btn.dataset.studioType || '';
    const studioText = studioType ? `${studioType} • ${studioName}` : (studioName || '-');
    document.getElementById('sum-studio').textContent = studioText;

    try {
        const res = await fetch(`/api/showtimes/${showtimeId}/details`);
        const data = await res.json();

        selectedPrice = data.price;
        document.getElementById('input-showtime').value = showtimeId;
        document.getElementById('sum-time').textContent = `${activeDateLabel} @ ${timeLabel}`;

        renderSeats(data.seats, data.occupied);

        // Smooth scroll ke kursi
        setTimeout(() => {
            document.getElementById('seat-section').scrollIntoView({ behavior: 'smooth', block: 'start' });
        }, 100);
    } catch (err) {
        grid.innerHTML = '<div class="text-red-500 text-center py-20 font-black text-[10px] uppercase tracking-widest">Connection Error. Please try again.</div>';
    }
}

function renderSeats(seats, occupied) {
    const grid = document.getElementById('seat-grid');
    grid.innerHTML = '';
    selectedSeats = [];
    updateSummary();

    const rows = {};
    seats.forEach(s => {
        if(!rows[s.row_label]) rows[s.row_label] = [];
        rows[s.row_label].push(s);
    });

    Object.keys(rows).sort().forEach(label => {
        const rowDiv = document.createElement('div');
        rowDiv.className = "flex items-center gap-6 justify-center mb-4";

        const labelSpan = document.createElement('span');
        labelSpan.className = "w-6 text-zinc-800 font-black text-[11px] italic";
        labelSpan.textContent = label;

        const seatsWrap = document.createElement('div');
        seatsWrap.className = "flex gap-3";

        rows[label].sort((a,b) => a.seat_number - b.seat_number).forEach(seat => {
            const isTaken = occupied.includes(seat.id);
            const btn = document.createElement('button');
            btn.type = "button";
            btn.className = `seat-base w-10 h-10 rounded-xl text-[10px] font-black border transition-all ${isTaken ? 'seat-occupied' : 'bg-white/5 border-white/10 text-zinc-500 seat-available'}`;
            btn.textContent = seat.seat_number;
            btn.disabled = isTaken;

            if(!isTaken) {
                btn.onclick = () => {
                    const idx = selectedSeats.findIndex(s => s.id === seat.id);
                    if(idx > -1) {
                        selectedSeats.splice(idx, 1);
                        btn.classList.remove('seat-selected');
                    } else {
                        if(selectedSeats.length >= 8) return alert('Maksimal pemesanan adalah 8 kursi');
                        selectedSeats.push({id: seat.id, name: label + seat.seat_number});
                        btn.classList.add('seat-selected');
                    }
                    updateSummary();
                };
            }
            seatsWrap.appendChild(btn);
        });

        rowDiv.append(labelSpan, seatsWrap);
        grid.appendChild(rowDiv);
    });
}

function updateSummary() {
    const btn = document.getElementById('checkoutBtn');

    document.getElementById('sum-seats').textContent = selectedSeats.length ? selectedSeats.map(s => s.name).join(', ') : '-';
    document.getElementById('sum-total').textContent = 'Rp ' + (selectedSeats.length * selectedPrice).toLocaleString('id-ID');

    const wrap = document.getElementById('seat-inputs');
    wrap.innerHTML = '';
    selectedSeats.forEach(s => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'seat_ids[]';
        input.value = s.id;
        wrap.appendChild(input);
    });

    if(selectedSeats.length > 0) {
        btn.disabled = false;
        btn.innerHTML = '<span class="italic group-hover:tracking-[0.4em] transition-all">Konfirmasi Pesanan</span>';
        btn.className = "w-full bg-red-600 py-6 rounded-3xl font-black uppercase tracking-[0.3em] text-[11px] text-white hover:bg-red-700 shadow-[0_15px_30px_rgba(220,38,38,0.3)] transition-all transform hover:scale-[1.02] cursor-pointer group";
    } else {
        btn.disabled = true;
        btn.innerHTML = '<span class="italic">Pilih Kursi Dahulu</span>';
        btn.className = "w-full bg-zinc-800 py-6 rounded-3xl font-black uppercase tracking-[0.3em] text-[11px] text-zinc-600 cursor-not-allowed shadow-xl group";
    }
}

function initTicketPage() {
    document.querySelectorAll('.date-btn').forEach(btn => {
        btn.onclick = () => handleDateClick(btn);
    });

    document.querySelectorAll('.time-btn').forEach(btn => {
        btn.onclick = () => handleTimeClick(btn);
    });

    const urlParams = new URLSearchParams(window.location.search);
    const preselectedId = urlParams.get('showtime_id');

    if (preselectedId) {
        const targetTimeBtn = document.querySelector(`.time-btn[data-id="${preselectedId}"]`);
        if (targetTimeBtn) {
            const dateRef = targetTimeBtn.dataset.dateRef;
            const targetDateBtn = document.querySelector(`.date-btn[data-date="${dateRef}"]`);
            if (targetDateBtn) {
                handleDateClick(targetDateBtn);
                handleTimeClick(targetTimeBtn);
            }
        }
    }
}

document.addEventListener('DOMContentLoaded', initTicketPage);
document.addEventListener('turbo:load', initTicketPage);
</script>
@endsection
