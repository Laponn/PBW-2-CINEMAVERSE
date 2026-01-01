{{-- resources/views/booking/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-6 py-10 space-y-8">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-4xl font-black tracking-tighter uppercase italic text-white">
                BOOKING <span class="text-red-600">SAYA</span>
            </h1>
            <p class="text-zinc-500 text-sm mt-2 font-medium uppercase tracking-widest">Pantau status transaksi tiketmu</p>
        </div>

        <div class="flex p-1 bg-zinc-900/50 border border-white/5 rounded-2xl">
            <a href="{{ route('booking.index') }}"
               class="px-6 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all {{ !$status ? 'bg-red-600 text-white shadow-lg shadow-red-600/20' : 'text-zinc-500 hover:text-white' }}">
               Semua
            </a>
            <a href="{{ route('booking.index', ['status' => 'pending']) }}"
               class="px-6 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all {{ $status==='pending' ? 'bg-yellow-500/20 text-yellow-500 border border-yellow-500/20' : 'text-zinc-500 hover:text-white' }}">
               Pending
            </a>
            <a href="{{ route('booking.index', ['status' => 'paid']) }}"
               class="px-6 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all {{ $status==='paid' ? 'bg-green-500/20 text-green-500 border border-green-500/20' : 'text-zinc-500 hover:text-white' }}">
               Paid
            </a>
        </div>
    </div>

    @if($bookings->isEmpty())
        <div class="bg-zinc-900/50 border border-dashed border-white/10 rounded-[2rem] p-20 text-center">
            <span class="text-4xl block mb-4">🎟️</span>
            <p class="text-zinc-500 font-bold uppercase tracking-widest">Belum ada aktivitas booking</p>
            <a href="{{ route('home') }}" class="text-red-500 text-xs font-black mt-4 inline-block hover:underline">MULAI CARI FILM →</a>
        </div>
    @else
        <div class="grid gap-6">
            @foreach($bookings as $b)
                @php
                    $seatList = $b->tickets->map(fn($t) => $t->seat->row_label.$t->seat->seat_number)->join(', ');
                    $start = \Carbon\Carbon::parse($b->showtime->start_time);
                @endphp

                <div class="group relative bg-zinc-900/40 border border-white/5 rounded-[2rem] p-6 hover:border-red-600/50 transition-all duration-500">
                    <div class="flex flex-col lg:flex-row gap-8 items-start lg:items-center">
                        {{-- Movie Poster (Mini) --}}
                        <div class="w-24 h-36 rounded-2xl overflow-hidden flex-shrink-0 shadow-2xl border border-white/10">
                            <img src="{{ $b->showtime->movie->poster_url }}" class="w-full h-full object-cover">
                        </div>

                        <div class="flex-1 space-y-3">
                            <div class="flex items-center gap-4">
                                <span class="bg-white/5 border border-white/10 px-3 py-1 rounded-lg text-[10px] font-black text-zinc-400 tracking-widest">{{ $b->booking_code }}</span>
                                @if($b->payment_status === 'paid')
                                    <span class="text-[10px] font-black px-3 py-1 rounded-full bg-green-500/10 text-green-500 border border-green-500/20 uppercase tracking-widest">● Success</span>
                                @else
                                    <span class="text-[10px] font-black px-3 py-1 rounded-full bg-yellow-500/10 text-yellow-500 border border-yellow-500/20 uppercase tracking-widest animate-pulse">● Waiting Payment</span>
                                @endif
                            </div>

                            <h3 class="text-2xl font-black text-white group-hover:text-red-500 transition-colors uppercase italic tracking-tighter">{{ $b->showtime->movie->title }}</h3>
                            
                            <div class="flex flex-wrap gap-y-2 gap-x-6 text-xs font-bold text-zinc-400 uppercase tracking-widest">
                                <div class="flex items-center gap-2"><span>📅</span> {{ $start->format('d M Y') }}</div>
                                <div class="flex items-center gap-2"><span>⏰</span> {{ $start->format('H:i') }}</div>
                                <div class="flex items-center gap-2"><span>🎬</span> {{ $b->showtime->studio->name }}</div>
                            </div>

                            <div class="text-xs font-black text-red-500 uppercase tracking-widest">
                                Kursi: {{ $seatList }}
                            </div>
                        </div>

                        <div class="w-full lg:w-auto flex flex-col items-end gap-3">
                            <div class="text-right">
                                <p class="text-[10px] font-black text-zinc-500 uppercase tracking-widest mb-1">Total Bayar</p>
                                <p class="text-xl font-black text-white italic">Rp{{ number_format($b->total_price,0,',','.') }}</p>
                            </div>

                            @if($b->payment_status === 'pending')
                                <a href="{{ route('booking.payment', $b->id) }}"
                                   class="w-full lg:w-auto px-8 py-3 rounded-full bg-red-600 hover:bg-red-700 text-white font-black text-[10px] uppercase tracking-[0.2em] transition-all transform hover:scale-105 shadow-lg shadow-red-600/30">
                                   Bayar Sekarang
                                </a>
                            @else
                                <a href="{{ route('booking.ticket', $b->id) }}"
                                   class="w-full lg:w-auto px-8 py-3 rounded-full bg-zinc-800 hover:bg-white hover:text-black text-white font-black text-[10px] uppercase tracking-[0.2em] transition-all border border-white/10">
                                   Lihat Tiket
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-10">{{ $bookings->links() }}</div>
    @endif
</div>
@endsection