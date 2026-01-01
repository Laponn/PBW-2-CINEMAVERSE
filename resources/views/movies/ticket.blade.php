{{-- resources/views/movies/ticket.blade.php --}}
@extends('layouts.app')

@section('content')
<main class="pt-24 pb-12 min-h-screen bg-[#050509]">
    <div class="max-w-screen-2xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-3 gap-10">
        
        <div class="lg:col-span-2 space-y-8">
            {{-- STEP 1: PILIH TANGGAL --}}
            <div class="space-y-4">
                <div class="flex items-center gap-4 text-white">
                    <span class="w-8 h-8 rounded-full bg-red-600 flex items-center justify-center text-xs font-black">1</span>
                    <h3 class="text-sm font-black uppercase tracking-widest">Pilih Tanggal</h3>
                </div>
                <div class="flex gap-4 overflow-x-auto pb-4 nice-scroll">
                    @foreach($availableDates as $date)
                        @php $cDate = \Carbon\Carbon::parse($date); @endphp
                        <button type="button" class="date-btn flex-shrink-0 w-24 p-4 rounded-2xl border border-white/5 bg-zinc-900/50 transition-all hover:border-red-600 text-center" data-date="{{ $date }}">
                            <span class="block text-[10px] uppercase font-bold text-zinc-500 mb-1">{{ $cDate->translatedFormat('D') }}</span>
                            <span class="block text-2xl font-black text-white">{{ $cDate->format('d') }}</span>
                            <span class="block text-[10px] font-bold text-zinc-400 uppercase tracking-tighter">{{ $cDate->translatedFormat('M') }}</span>
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- STEP 2: PILIH JAM --}}
            <div id="time-section" class="hidden space-y-4">
                <div class="flex items-center gap-4 text-white">
                    <span class="w-8 h-8 rounded-full bg-red-600 flex items-center justify-center text-xs font-black">2</span>
                    <h3 class="text-sm font-black uppercase tracking-widest">Pilih Jam & Studio</h3>
                </div>
                <div id="time-container" class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($showtimes as $st)
                        <button type="button" class="time-btn hidden p-5 rounded-2xl border border-white/5 bg-zinc-900/50 text-left hover:border-red-600 transition" 
                            data-date-ref="{{ \Carbon\Carbon::parse($st->start_time)->format('Y-m-d') }}" 
                            data-id="{{ $st->id }}">
                            <span class="block font-black text-xl text-white">{{ \Carbon\Carbon::parse($st->start_time)->format('H:i') }}</span>
                            <span class="block text-[10px] font-bold text-zinc-500 uppercase mt-1">{{ $st->studio->name }}</span>
                            <span class="block text-[10px] text-green-500 font-black mt-1">Rp {{ number_format($st->price, 0, ',', '.') }}</span>
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- STEP 3: PILIH KURSI --}}
            <div id="seat-section" class="hidden space-y-6 bg-zinc-900/20 p-8 rounded-[3rem] border border-white/5">
                <div class="text-center space-y-4">
                    <p class="text-[10px] font-black text-zinc-500 uppercase tracking-[0.4em]">Layar Bioskop</p>
                    <div class="h-2 w-full bg-gradient-to-r from-transparent via-red-600 to-transparent shadow-[0_0_20px_rgba(220,38,38,0.5)] rounded-full"></div>
                </div>
                
                <div id="seat-grid" class="grid grid-cols-10 gap-3 max-w-xl mx-auto pt-10">
                    {{-- Diisi via JS --}}
                </div>

                <div class="flex justify-center gap-8 pt-8 text-[10px] font-black uppercase tracking-widest">
                    <div class="flex items-center gap-2 text-zinc-600"><span class="w-4 h-4 bg-zinc-800 rounded"></span> Terisi</div>
                    <div class="flex items-center gap-2 text-zinc-400"><span class="w-4 h-4 bg-white/10 rounded"></span> Tersedia</div>
                    <div class="flex items-center gap-2 text-red-500"><span class="w-4 h-4 bg-red-600 rounded"></span> Pilihanmu</div>
                </div>
            </div>
        </div>

        {{-- SIDEBAR: SUMMARY --}}
        <div class="relative h-full">
            <div class="sticky top-28 bg-zinc-900 border border-white/10 p-8 rounded-[2.5rem] shadow-2xl space-y-8 text-white">
                <div>
                    <h2 class="text-3xl font-black italic tracking-tighter uppercase leading-none">{{ $movie->title }}</h2>
                    <p class="text-xs font-bold text-zinc-500 uppercase mt-2">{{ $movie->duration_minutes }} Menit • {{ $movie->genre }}</p>
                </div>

                <div class="space-y-4 border-t border-white/5 pt-6">
                    <div class="flex justify-between text-[10px] font-black uppercase tracking-widest text-zinc-500"><span>Cabang</span><span id="sum-branch" class="text-white">-</span></div>
                    <div class="flex justify-between text-[10px] font-black uppercase tracking-widest text-zinc-500"><span>Studio</span><span id="sum-studio" class="text-white">-</span></div>
                    <div class="flex justify-between text-[10px] font-black uppercase tracking-widest text-zinc-500"><span>Waktu</span><span id="sum-time" class="text-white">-</span></div>
                    <div class="flex justify-between text-[10px] font-black uppercase tracking-widest text-zinc-500"><span>Kursi</span><span id="sum-seats" class="text-red-500 font-black italic">-</span></div>
                </div>

                <div class="pt-6 border-t border-white/5">
                    <div class="flex justify-between items-end mb-8">
                        <span class="text-[10px] font-black uppercase text-zinc-500 tracking-widest">Total Bayar</span>
                        <span id="sum-total" class="text-3xl font-black italic text-red-500">Rp 0</span>
                    </div>

                    <form action="{{ route('booking.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="showtime_id" id="input-showtime">
                        <div id="seat-inputs"></div>
                        <button type="submit" class="w-full bg-red-600 py-5 rounded-2xl font-black uppercase tracking-[0.2em] text-xs hover:bg-red-700 transition transform hover:scale-[1.02] shadow-xl shadow-red-600/30">
                            Konfirmasi Tiket
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection