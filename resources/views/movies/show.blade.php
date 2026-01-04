@extends('layouts.app')

@section('content')
<style>
    /* Global Scrollbar */
    .nice-scroll::-webkit-scrollbar { height: 3px; width: 4px; }
    .nice-scroll::-webkit-scrollbar-thumb { background: rgba(220, 38, 38, 0.4); border-radius: 10px; }

    /* Perspective System */
    .perspective-wrap { perspective: 1500px; }
    .premium-card { 
        background: rgba(24, 24, 27, 0.4);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    /* Step Buttons Scaling (Zoom Out) */
    .btn-step {
        transition: all 0.4s cubic-bezier(0.23, 1, 0.32, 1);
    }

    /* Date Button Active */
    .date-selected {
        background: #dc2626 !important;
        border-color: #ef4444 !important;
        transform: translateY(-4px) scale(1.03);
        box-shadow: 0 10px 20px rgba(220, 38, 38, 0.3);
    }

    /* Time Button Active */
    .time-selected {
        background: white !important;
        color: black !important;
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(255, 255, 255, 0.1);
    }
    .time-selected span { color: black !important; }

    /* Seat Scaling (Smaller) */
    .seat-base {
        transition: all 0.2s ease;
    }
    .seat-available:hover {
        transform: scale(1.15);
        background: rgba(220, 38, 38, 0.15);
    }
    .seat-selected {
        background: #dc2626 !important;
        border-color: #ff4d4d !important;
        box-shadow: 0 0 12px rgba(220, 38, 38, 0.6);
        transform: scale(1.15);
    }
    .seat-occupied { background: #18181b !important; opacity: 0.25; cursor: not-allowed; }

    /* Screen Decoration */
    .screen-area {
        height: 6px; width: 100%; background: white; border-radius: 50%;
        box-shadow: 0 6px 20px rgba(255, 255, 255, 0.4);
    }
</style>

<div class="bg-[#050509] min-h-screen text-white pt-24 pb-12">
    <div class="max-w-6xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <div class="lg:col-span-8 space-y-8">
            {{-- HEADER --}}
            <div class="space-y-1">
                <span class="px-3 py-1 rounded-full bg-red-600/10 border border-red-600/20 text-[9px] font-black text-red-500 uppercase tracking-widest italic">Reservation</span>
                <h1 class="text-3xl font-black italic uppercase tracking-tighter">Book <span class="text-red-600">Experience</span></h1>
            </div>

            {{-- STEP 1: PILIH TANGGAL --}}
            <div class="premium-card p-6 rounded-[2.5rem]">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-8 h-8 rounded-xl bg-red-600 flex items-center justify-center font-black italic text-xs shadow-lg shadow-red-600/30">01</div>
                    <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-zinc-500">Tanggal Menonton</h3>
                </div>
                
                <div class="flex gap-3 overflow-x-auto pb-2 nice-scroll">
                    @forelse($availableDates as $date)
                        @php $cDate = \Illuminate\Support\Carbon::parse($date); @endphp
                        <button type="button" 
                            class="date-btn btn-step min-w-[85px] p-4 rounded-2xl border border-white/5 bg-white/5" 
                            data-date="{{ $date }}"
                            data-date-display="{{ $cDate->translatedFormat('D, d M') }}">
                            <span class="block text-[9px] uppercase text-zinc-500 font-black mb-1 tracking-widest">{{ $cDate->translatedFormat('D') }}</span>
                            <span class="block text-2xl font-black italic tracking-tighter">{{ $cDate->format('d') }}</span>
                            <span class="block text-[9px] text-zinc-400 font-bold uppercase">{{ $cDate->translatedFormat('M') }}</span>
                        </button>
                    @empty
                        <p class="text-zinc-600 italic font-black uppercase text-[10px]">Jadwal tidak tersedia.</p>
                    @endforelse
                </div>
            </div>

            {{-- STEP 2: PILIH JAM --}}
            <div id="time-section" class="premium-card p-6 rounded-[2.5rem] hidden">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-8 h-8 rounded-xl bg-red-600 flex items-center justify-center font-black italic text-xs">02</div>
                    <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-zinc-500">Jam & Studio</h3>
                </div>
                
                <div id="time-container" class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($showtimes as $st)
                        <button type="button" class="time-btn btn-step p-4 rounded-2xl border border-white/5 bg-white/5 text-left hidden" 
                            data-date-ref="{{ \Illuminate\Support\Carbon::parse($st->start_time)->format('Y-m-d') }}" 
                            data-id="{{ $st->id }}"
                            data-time="{{ \Illuminate\Support\Carbon::parse($st->start_time)->format('H:i') }}">
                            <span class="block font-black text-xl italic tracking-tighter">{{ \Illuminate\Support\Carbon::parse($st->start_time)->format('H:i') }}</span>
                            <span class="block text-[8px] text-zinc-500 font-black uppercase mt-1">{{ $st->studio->name }}</span>
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- STEP 3: PILIH KURSI (ZOOM OUT) --}}
            <div id="seat-section" class="premium-card p-8 rounded-[3rem] hidden">
                <div class="mb-12 px-6">
                    <div class="screen-area"></div>
                    <p class="text-center text-[7px] font-black uppercase tracking-[0.8em] text-zinc-700 mt-4">Screen Area</p>
                </div>

                <div id="seat-grid" class="space-y-3"></div>
            </div>
        </div>

        {{-- SIDEBAR SUMMARY (ZOOM OUT) --}}
        <div class="lg:col-span-4">
            <div class="premium-card p-7 rounded-[3rem] sticky top-28 space-y-8">
                <div>
                    <span class="text-[8px] font-black text-red-600 uppercase tracking-[0.3em] mb-2 block">Booking Details</span>
                    <h2 class="text-xl font-black italic tracking-tighter uppercase leading-none">{{ $movie->title }}</h2>
                    <p class="text-[9px] font-bold text-zinc-500 uppercase mt-2 tracking-widest">{{ session('branch_name', 'Global') }}</p>
                </div>
                
                <div class="space-y-4 text-[10px] font-black border-t border-white/5 pt-6 uppercase tracking-widest">
                    <div class="flex justify-between"><span>Studio</span><span id="sum-studio" class="text-white">-</span></div>
                    <div class="flex justify-between"><span>Time</span><span id="sum-time" class="text-white">-</span></div>
                    <div class="flex justify-between"><span>Seats</span><span id="sum-seats" class="text-red-500 italic">-</span></div>
                </div>

                <div class="pt-6 border-t border-white/5">
                    <div class="flex justify-between items-end mb-6">
                        <span class="text-[9px] font-black uppercase text-zinc-600 tracking-widest">Total</span>
                        <span id="sum-total" class="text-2xl font-black italic text-white tracking-tighter">Rp 0</span>
                    </div>

                    <form action="{{ route('booking.store') }}" method="POST" id="bookingForm">
                        @csrf
                        <input type="hidden" name="showtime_id" id="input-showtime">
                        <div id="seat-inputs"></div>
                        <button type="submit" id="checkoutBtn" disabled class="w-full bg-zinc-800 py-4 rounded-2xl font-black uppercase tracking-[0.2em] text-[10px] text-zinc-600 cursor-not-allowed transition-all shadow-lg active:scale-95">
                            Pilih Kursi Dahulu
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Logic JavaScript tetap sama dengan penyesuaian ukuran grid di renderSeats
let selectedPrice = 0;
let selectedSeats = [];
let activeDateLabel = "";

function updateSummary() {
    const btn = document.getElementById('checkoutBtn');
    document.getElementById('sum-seats').textContent = selectedSeats.length ? selectedSeats.map(s => s.name).join(', ') : '-';
    document.getElementById('sum-total').textContent = 'Rp ' + (selectedSeats.length * selectedPrice).toLocaleString('id-ID');
    
    const wrap = document.getElementById('seat-inputs');
    wrap.innerHTML = '';
    selectedSeats.forEach(s => {
        const input = document.createElement('input');
        input.type = 'hidden'; input.name = 'seat_ids[]'; input.value = s.id;
        wrap.appendChild(input);
    });

    if(selectedSeats.length > 0) {
        btn.disabled = false;
        btn.textContent = 'Konfirmasi Tiket';
        btn.className = "w-full bg-red-600 py-4 rounded-2xl font-black uppercase tracking-[0.2em] text-[10px] text-white hover:bg-red-700 shadow-lg cursor-pointer transition-all";
    } else {
        btn.disabled = true;
        btn.textContent = 'Pilih Kursi Dahulu';
        btn.className = "w-full bg-zinc-800 py-4 rounded-2xl font-black uppercase tracking-[0.2em] text-[10px] text-zinc-600 cursor-not-allowed shadow-lg";
    }
}

function renderSeats(seats, occupied) {
    const grid = document.getElementById('seat-grid');
    grid.innerHTML = ''; selectedSeats = []; updateSummary();
    const rows = {};
    seats.forEach(s => {
        if(!rows[s.row_label]) rows[s.row_label] = [];
        rows[s.row_label].push(s);
    });

    Object.keys(rows).sort().forEach(label => {
        const rowDiv = document.createElement('div');
        rowDiv.className = "flex items-center gap-4 justify-center mb-3";
        const labelSpan = document.createElement('span');
        labelSpan.className = "w-4 text-zinc-800 font-black text-[10px] italic";
        labelSpan.textContent = label;
        const seatsWrap = document.createElement('div');
        seatsWrap.className = "flex gap-2";

        rows[label].sort((a,b) => a.seat_number - b.seat_number).forEach(seat => {
            const isTaken = occupied.includes(seat.id);
            const btn = document.createElement('button');
            btn.type = "button";
            btn.className = `seat-base w-8 h-8 rounded-lg text-[9px] font-black border transition-all ${isTaken ? 'seat-occupied' : 'bg-white/5 border-white/10 text-zinc-500 seat-available'}`;
            btn.textContent = seat.seat_number;
            btn.disabled = isTaken;
            
            if(!isTaken) {
                btn.onclick = () => {
                    const idx = selectedSeats.findIndex(s => s.id === seat.id);
                    if(idx > -1) {
                        selectedSeats.splice(idx, 1);
                        btn.classList.remove('seat-selected');
                    } else {
                        if(selectedSeats.length >= 8) return alert('Maksimal 8 kursi');
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

// Handler tombol (tetap sama)
function handleDateClick(btn) {
    document.querySelectorAll('.date-btn').forEach(b => b.classList.remove('date-selected'));
    btn.classList.add('date-selected');
    activeDateLabel = btn.dataset.dateDisplay;
    document.getElementById('time-section').classList.remove('hidden');
    document.querySelectorAll('.time-btn').forEach(t => {
        t.classList.toggle('hidden', t.dataset.dateRef !== btn.dataset.date);
        t.classList.remove('time-selected');
    });
    document.getElementById('seat-section').classList.add('hidden');
    resetSummary();
}

async function handleTimeClick(btn) {
    document.querySelectorAll('.time-btn').forEach(b => b.classList.remove('time-selected'));
    btn.classList.add('time-selected');
    const grid = document.getElementById('seat-grid');
    document.getElementById('seat-section').classList.remove('hidden');
    grid.innerHTML = '<div class="py-12 text-center text-[8px] font-black uppercase tracking-widest animate-pulse text-red-600">Hall Loading...</div>';
    try {
        const res = await fetch(`/api/showtimes/${btn.dataset.id}/details`);
        const data = await res.json();
        selectedPrice = data.price;
        document.getElementById('input-showtime').value = btn.dataset.id;
        document.getElementById('sum-studio').textContent = data.studio_name;
        document.getElementById('sum-time').textContent = `${activeDateLabel} @ ${btn.dataset.time}`;
        renderSeats(data.seats, data.occupied);
    } catch (err) { grid.innerHTML = 'Error'; }
}

function initTicketPage() {
    document.querySelectorAll('.date-btn').forEach(btn => btn.onclick = () => handleDateClick(btn));
    document.querySelectorAll('.time-btn').forEach(btn => btn.onclick = () => handleTimeClick(btn));
}
document.addEventListener('DOMContentLoaded', initTicketPage);
document.addEventListener('turbo:load', initTicketPage);
</script>
@endsection