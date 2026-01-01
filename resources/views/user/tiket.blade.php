@extends('layouts.app')

@section('content')
<style>
    .nice-scroll::-webkit-scrollbar { height: 4px; }
    .nice-scroll::-webkit-scrollbar-thumb { background: rgba(220, 38, 38, 0.5); border-radius: 10px; }
</style>

<div class="bg-[#05070b] min-h-screen text-white pt-24 pb-12">
    <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <div class="lg:col-span-8 space-y-6">
            {{-- STEP 1: PILIH TANGGAL --}}
            <div class="bg-zinc-900/50 p-6 rounded-[2.5rem] border border-white/10 backdrop-blur-md">
                <div class="flex items-center gap-3 mb-6">
                    <span class="w-8 h-8 rounded-xl bg-red-600 flex items-center justify-center font-black text-xs italic shadow-lg shadow-red-600/20">01</span>
                    <h3 class="text-xs font-black uppercase tracking-[0.2em] text-red-500">Pilih Tanggal</h3>
                </div>
                <div class="flex gap-3 overflow-x-auto pb-4 nice-scroll">
                    @foreach($availableDates as $date)
                        @php $cDate = \Illuminate\Support\Carbon::parse($date); @endphp
                        <button type="button" 
                            class="date-btn min-w-[90px] p-4 rounded-2xl border border-white/5 bg-white/5 transition-all" 
                            data-date="{{ $date }}"
                            data-date-display="{{ $cDate->translatedFormat('D, d M') }}">
                            <span class="block text-[10px] uppercase text-zinc-500 font-bold mb-1">{{ $cDate->translatedFormat('D') }}</span>
                            <span class="block text-2xl font-black">{{ $cDate->format('d') }}</span>
                            <span class="block text-[10px] text-zinc-400 font-bold uppercase">{{ $cDate->translatedFormat('M') }}</span>
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- STEP 2: PILIH JAM --}}
            <div id="time-section" class="bg-zinc-900/50 p-6 rounded-[2.5rem] border border-white/10 hidden backdrop-blur-md">
                <div class="flex items-center gap-3 mb-6">
                    <span class="w-8 h-8 rounded-xl bg-red-600 flex items-center justify-center font-black text-xs italic shadow-lg shadow-red-600/20">02</span>
                    <h3 class="text-xs font-black uppercase tracking-[0.2em] text-red-500">Pilih Jam & Studio</h3>
                </div>
                <div id="time-container" class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    @foreach($showtimes as $st)
                        <button type="button" class="time-btn p-4 rounded-2xl border border-white/5 bg-white/5 text-left hidden hover:border-red-600 transition-all" 
                            data-date-ref="{{ \Illuminate\Support\Carbon::parse($st->start_time)->format('Y-m-d') }}" 
                            data-id="{{ $st->id }}"
                            data-time="{{ \Illuminate\Support\Carbon::parse($st->start_time)->format('H:i') }}">
                            <span class="block font-black text-xl">{{ \Illuminate\Support\Carbon::parse($st->start_time)->format('H:i') }}</span>
                            <span class="block text-[10px] text-zinc-500 font-bold uppercase mt-1">{{ $st->studio->name }}</span>
                            <span class="block text-[11px] text-green-500 font-black mt-1 italic">Rp {{ number_format($st->price, 0, ',', '.') }}</span>
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- STEP 3: PILIH KURSI --}}
            <div id="seat-section" class="bg-zinc-900/50 p-8 rounded-[3rem] border border-white/10 hidden backdrop-blur-md">
                <div class="flex flex-col items-center mb-12">
                    <div class="w-full h-1.5 bg-zinc-800 rounded-full relative overflow-hidden ring-4 ring-white/5 mb-4">
                        <div class="absolute inset-0 bg-gradient-to-r from-red-600/20 via-white/70 to-red-600/20 animate-pulse"></div>
                    </div>
                    <p class="text-[9px] font-black uppercase tracking-[0.5em] text-zinc-500">Layar Bioskop</p>
                </div>
                <div id="seat-grid" class="space-y-3"></div>
            </div>
        </div>

        {{-- SIDEBAR SUMMARY --}}
        <div class="lg:col-span-4 h-fit sticky top-28">
            <div class="bg-zinc-900 border border-white/10 p-8 rounded-[2.5rem] shadow-2xl space-y-8">
                <div>
                    <h2 class="text-2xl font-black italic tracking-tighter uppercase leading-none text-white">{{ $movie->title }}</h2>
                    <p id="sum-branch-title" class="text-[10px] font-bold text-zinc-500 uppercase mt-2">{{ session('branch_name', 'Pilih Lokasi') }}</p>
                </div>
                
                <div class="space-y-4 text-xs font-bold border-t border-white/5 pt-6">
                    <div class="flex justify-between text-zinc-500"><span>Studio</span><span id="sum-studio" class="text-white">-</span></div>
                    <div class="flex justify-between text-zinc-500"><span>Waktu</span><span id="sum-time" class="text-white">-</span></div>
                    <div class="flex justify-between text-zinc-500"><span>Kursi</span><span id="sum-seats" class="text-red-500 font-black italic">-</span></div>
                </div>

                <div class="pt-6 border-t border-white/5">
                    <div class="flex justify-between items-end mb-8">
                        <span class="text-[10px] font-black uppercase text-zinc-500 tracking-[0.2em]">Total</span>
                        <span id="sum-total" class="text-3xl font-black italic text-white tracking-tighter">Rp 0</span>
                    </div>
                    <form action="{{ route('booking.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="showtime_id" id="input-showtime">
                        <div id="seat-inputs"></div>
                        <button type="submit" id="checkoutBtn" class="w-full bg-red-600 py-5 rounded-2xl font-black uppercase tracking-[0.2em] text-[11px] text-white hover:bg-red-700 transition transform hover:scale-[1.02] shadow-xl shadow-red-600/30">
                            Konfirmasi Pesanan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let selectedPrice = 0, selectedSeats = [];
    let activeDateLabel = "";

    document.querySelectorAll('.date-btn').forEach(btn => {
        btn.onclick = () => {
            document.querySelectorAll('.date-btn').forEach(b => b.classList.remove('bg-red-600', 'border-red-600'));
            btn.classList.add('bg-red-600', 'border-red-600');
            activeDateLabel = btn.dataset.dateDisplay;
            
            document.getElementById('time-section').classList.remove('hidden');
            document.querySelectorAll('.time-btn').forEach(t => t.classList.toggle('hidden', t.dataset.dateRef !== btn.dataset.date));
            document.getElementById('seat-section').classList.add('hidden');
        };
    });

    document.querySelectorAll('.time-btn').forEach(btn => {
        btn.onclick = async () => {
            document.querySelectorAll('.time-btn').forEach(b => b.classList.remove('bg-white', 'text-black'));
            btn.classList.add('bg-white', 'text-black');
            
            document.getElementById('seat-section').classList.remove('hidden');
            const grid = document.getElementById('seat-grid');
            grid.innerHTML = '<p class="text-center text-zinc-500 italic py-10">Memuat kursi...</p>';

            const res = await fetch(`/api/showtimes/${btn.dataset.id}/details`);
            const data = await res.json();
            
            selectedPrice = data.price;
            document.getElementById('input-showtime').value = btn.dataset.id;
            document.getElementById('sum-studio').textContent = data.studio_name;
            // PERBAIKAN: Gunakan Assignment (=) agar tidak berulang
            document.getElementById('sum-time').textContent = `${activeDateLabel} @ ${btn.dataset.time}`;
            
            renderSeats(data.seats, data.occupied);
        };
    });

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
            rowDiv.className = "flex items-center gap-2 justify-center mb-2";
            const labelSpan = document.createElement('span');
            labelSpan.className = "w-4 text-zinc-600 font-bold text-[10px]";
            labelSpan.textContent = label;
            const seatsWrap = document.createElement('div');
            seatsWrap.className = "flex gap-2";

            rows[label].sort((a,b) => a.seat_number - b.seat_number).forEach(seat => {
                const isTaken = occupied.includes(seat.id);
                const btn = document.createElement('button');
                btn.type = "button";
                btn.className = `w-8 h-8 rounded-lg text-[9px] font-bold transition-all border ${isTaken ? 'bg-zinc-800 text-zinc-600 border-transparent' : 'bg-white/5 border-white/10 hover:border-red-500'}`;
                btn.textContent = seat.seat_number;
                btn.disabled = isTaken;

                if(!isTaken) {
                    btn.onclick = () => {
                        const idx = selectedSeats.findIndex(s => s.id === seat.id);
                        if(idx > -1) {
                            selectedSeats.splice(idx, 1);
                            btn.classList.remove('bg-red-600', 'border-red-600', 'text-white');
                        } else {
                            selectedSeats.push({id: seat.id, name: label + seat.seat_number});
                            btn.classList.add('bg-red-600', 'border-red-600', 'text-white');
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
        document.getElementById('sum-seats').textContent = selectedSeats.length ? selectedSeats.map(s => s.name).join(', ') : '-';
        document.getElementById('sum-total').textContent = 'Rp ' + (selectedSeats.length * selectedPrice).toLocaleString('id-ID');
        const wrap = document.getElementById('seat-inputs');
        wrap.innerHTML = '';
        selectedSeats.forEach(s => {
            const input = document.createElement('input');
            input.type = 'hidden'; input.name = 'seat_ids[]'; input.value = s.id;
            wrap.appendChild(input);
        });
    }
});
</script>
@endsection