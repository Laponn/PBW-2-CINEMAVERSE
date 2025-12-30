@extends('layouts.app')

@section('content')
<div class="bg-[#05070b] min-h-screen text-white pt-24 pb-12">
    <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 space-y-6">
            {{-- 1. PILIH TANGGAL --}}
            <div class="bg-zinc-900/50 p-6 rounded-3xl border border-white/10">
                <h3 class="text-xs font-bold uppercase tracking-widest mb-4 text-red-500">1. Pilih Tanggal</h3>
                <div class="flex gap-3 overflow-x-auto pb-2">
                    @foreach($availableDates as $date)
                        @php $cDate = \Carbon\Carbon::parse($date); @endphp
                        <button type="button" class="date-btn min-w-[85px] p-3 rounded-2xl border border-white/10 bg-white/5 transition" data-date="{{ $date }}">
                            <span class="block text-[10px] uppercase text-zinc-500">{{ $cDate->translatedFormat('D') }}</span>
                            <span class="block text-xl font-bold">{{ $cDate->format('d') }}</span>
                            <span class="block text-[10px] text-zinc-400">{{ $cDate->translatedFormat('M') }}</span>
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- 2. PILIH JAM & STUDIO --}}
            <div id="time-section" class="bg-zinc-900/50 p-6 rounded-3xl border border-white/10 hidden">
                <h3 class="text-xs font-bold uppercase tracking-widest mb-4 text-red-500">2. Pilih Jam & Studio</h3>
                <div id="time-container" class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    @foreach($showtimes as $st)
                        <button type="button" class="time-btn p-3 rounded-2xl border border-white/10 bg-white/5 text-left hidden" 
                            data-date-ref="{{ \Carbon\Carbon::parse($st->start_time)->format('Y-m-d') }}" 
                            data-id="{{ $st->id }}">
                            <span class="block font-bold text-lg">{{ \Carbon\Carbon::parse($st->start_time)->format('H:i') }}</span>
                            <span class="block text-[10px] text-zinc-400">{{ $st->studio->name }}</span>
                            <span class="block text-[10px] text-green-500 font-bold">Rp {{ number_format($st->price, 0, ',', '.') }}</span>
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- 3. PILIH KURSI --}}
            <div id="seat-section" class="bg-zinc-900/50 p-6 rounded-3xl border border-white/10 hidden">
                <h3 class="text-xs font-bold uppercase tracking-widest mb-8 text-red-500 text-center">3. Pilih Kursi</h3>
                <div class="max-w-md mx-auto">
                    <div class="h-1.5 w-full bg-gradient-to-r from-transparent via-red-600 to-transparent shadow-[0_0_20px_rgba(220,38,38,0.5)] mb-12"></div>
                    <div id="seat-grid" class="grid grid-cols-10 gap-2 justify-center"></div>
                </div>
            </div>
        </div>

        {{-- RINGKASAN & HARGA --}}
        <div class="bg-zinc-900 border border-white/10 p-6 rounded-3xl h-fit sticky top-28">
            <h2 class="text-2xl font-bold">{{ $movie->title }}</h2>
            <p class="text-sm text-zinc-400 mb-6">{{ $movie->duration_minutes }} Menit</p>
            
            <div class="space-y-4 text-sm border-t border-white/10 pt-6">
                <div class="flex justify-between"><span class="text-zinc-500">Cabang</span><span id="sum-branch">-</span></div>
                <div class="flex justify-between"><span class="text-zinc-500">Studio</span><span id="sum-studio">-</span></div>
                <div class="flex justify-between"><span class="text-zinc-500">Waktu</span><span id="sum-time">-</span></div>
                <div class="flex justify-between"><span class="text-zinc-500">Kursi</span><span id="sum-seats" class="text-red-500 font-bold">-</span></div>
            </div>

            <div class="mt-10 border-t border-white/10 pt-6">
                <div class="flex justify-between text-xl font-bold mb-6">
                    <span>Total</span>
                    <span id="sum-total" class="text-red-500">Rp 0</span>
                </div>
                <form action="{{ route('booking.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="showtime_id" id="input-showtime">
                    <div id="seat-inputs"></div>
                    <button type="submit" class="w-full bg-red-600 py-4 rounded-2xl font-bold hover:bg-red-700 transition">Checkout Sekarang</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let selectedPrice = 0, selectedSeats = [];

    // Filter Jam berdasarkan Tanggal
    document.querySelectorAll('.date-btn').forEach(btn => {
        btn.onclick = () => {
            document.querySelectorAll('.date-btn').forEach(b => b.classList.remove('bg-red-600'));
            btn.classList.add('bg-red-600');
            document.getElementById('time-section').classList.remove('hidden');
            document.querySelectorAll('.time-btn').forEach(t => t.classList.toggle('hidden', t.dataset.dateRef !== btn.dataset.date));
            document.getElementById('seat-section').classList.add('hidden');
        };
    });

    // Ambil Kursi saat Jam diklik
    document.querySelectorAll('.time-btn').forEach(btn => {
        btn.onclick = async () => {
            document.querySelectorAll('.time-btn').forEach(b => b.classList.remove('bg-red-600'));
            btn.classList.add('bg-red-600');
            
            const res = await fetch(`/api/showtimes/${btn.dataset.id}/details`);
            const data = await res.json();
            
            selectedPrice = data.price;
            document.getElementById('input-showtime').value = btn.dataset.id;
            document.getElementById('sum-branch').textContent = data.branch_name;
            document.getElementById('sum-studio').textContent = data.studio_name;
            document.getElementById('sum-time').textContent = btn.querySelector('.font-bold').textContent;
            
            renderSeats(data.seats, data.occupied);
            document.getElementById('seat-section').classList.remove('hidden');
        };
    });

    function renderSeats(seats, occupied) {
        const grid = document.getElementById('seat-grid');
        grid.innerHTML = ''; selectedSeats = []; updateSummary();

        // Urutkan kursi A-Z
        seats.sort((a,b) => (a.row_label + a.seat_number).localeCompare(b.row_label + b.seat_number));

        seats.forEach(seat => {
            const isTaken = occupied.includes(seat.id);
            const isUsable = seat.is_usable === 1;

            const btn = document.createElement('button');
            btn.type = "button";
            btn.className = `w-8 h-8 rounded-lg text-[10px] font-bold transition ${!isUsable || isTaken ? 'bg-zinc-800 text-zinc-600 cursor-not-allowed' : 'bg-white/10 hover:bg-red-500'}`;
            btn.textContent = `${seat.row_label}${seat.seat_number}`;
            btn.disabled = !isUsable || isTaken;

            btn.onclick = () => {
                btn.classList.toggle('bg-red-600');
                const idx = selectedSeats.findIndex(s => s.id === seat.id);
                if (idx > -1) selectedSeats.splice(idx, 1);
                else selectedSeats.push({id: seat.id, name: `${seat.row_label}${seat.seat_number}`});
                updateSummary();
            };
            grid.appendChild(btn);
        });
    }

    function updateSummary() {
        document.getElementById('sum-seats').textContent =
        selectedSeats.length ? selectedSeats.map(s => s.name).join(', ') : '-';

        document.getElementById('sum-total').textContent ='Rp ' + (selectedSeats.length * selectedPrice).toLocaleString('id-ID');

  // bikin seat_ids[] sesuai validasi Laravel
  const wrap = document.getElementById('seat-inputs');
  wrap.innerHTML = '';
  selectedSeats.forEach(s => {
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'seat_ids[]';
    input.value = s.id;
    wrap.appendChild(input);
  });
}


    // Auto-select jika datang dari halaman detail (?time=ID)
    const urlParams = new URLSearchParams(window.location.search);
    const timeId = urlParams.get('time');
    if (timeId) {
        const tBtn = document.querySelector(`.time-btn[data-id="${timeId}"]`);
        if (tBtn) {
            const dBtn = document.querySelector(`.date-btn[data-date="${tBtn.dataset.dateRef}"]`);
            if (dBtn) dBtn.click();
            tBtn.click();
            document.getElementById('seat-section').scrollIntoView({ behavior: 'smooth' });
        }
    }
});
</script>
@endsection