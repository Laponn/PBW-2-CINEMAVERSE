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

        {{-- Filter Status --}}
        <div class="flex p-1 bg-zinc-900/50 border border-white/5 rounded-2xl overflow-x-auto">
            <a href="{{ route('booking.index') }}"
               class="px-6 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all whitespace-nowrap {{ !$status || $status === 'all' ? 'bg-red-600 text-white shadow-lg shadow-red-600/20' : 'text-zinc-500 hover:text-white' }}">
               Semua
            </a>
            <a href="{{ route('booking.index', ['status' => 'pending']) }}"
               class="px-6 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all whitespace-nowrap {{ $status==='pending' ? 'bg-yellow-500/20 text-yellow-500 border border-yellow-500/20' : 'text-zinc-500 hover:text-white' }}">
               Pending
            </a>
            <a href="{{ route('booking.index', ['status' => 'paid']) }}"
               class="px-6 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all whitespace-nowrap {{ $status==='paid' ? 'bg-green-500/20 text-green-500 border border-green-500/20' : 'text-zinc-500 hover:text-white' }}">
               Paid
            </a>
        </div>
    </div>

    @if($bookings->isEmpty())
        <div class="bg-zinc-900/50 border border-dashed border-white/10 rounded-[2.5rem] p-20 text-center">
            <span class="text-5xl block mb-6 animate-bounce">🎟️</span>
            <p class="text-zinc-500 font-bold uppercase tracking-widest text-sm">Belum ada aktivitas booking</p>
            <a href="{{ route('home') }}" class="text-red-500 text-[10px] font-black mt-6 inline-block hover:text-red-400 uppercase tracking-widest transition-all">MULAI CARI FILM →</a>
        </div>
    @else
        <div class="grid gap-6">
            @foreach($bookings as $b)
                @php
                    $seatList = $b->tickets->map(fn($t) => $t->seat->row_label.$t->seat->seat_number)->join(', ');
                    $start = \Carbon\Carbon::parse($b->showtime->start_time);
                @endphp

                <div class="group relative bg-zinc-900/40 border border-white/5 rounded-[2.5rem] p-6 hover:border-red-600/30 transition-all duration-500">
                    <div class="flex flex-col lg:flex-row gap-8 items-start lg:items-center">
                        {{-- Movie Poster --}}
                        <div class="w-24 h-36 rounded-2xl overflow-hidden flex-shrink-0 shadow-2xl border border-white/10 relative">
                            <img src="{{ $b->showtime->movie->poster_url }}" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-700">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent"></div>
                        </div>

                        <div class="flex-1 space-y-4">
                            <div class="flex flex-wrap items-center gap-4">
                                <span class="bg-white/5 border border-white/10 px-3 py-1 rounded-lg text-[9px] font-mono text-zinc-500 tracking-tighter">{{ $b->booking_code }}</span>
                                
                                {{-- Status Badges --}}
                                @if($b->payment_status === 'paid')
                                    <span class="text-[9px] font-black px-3 py-1 rounded-full bg-emerald-500/10 text-emerald-500 border border-emerald-500/20 uppercase tracking-widest italic">● Payment Success</span>
                                @elseif($b->payment_status === 'pending')
                                    <span class="text-[9px] font-black px-3 py-1 rounded-full bg-yellow-500/10 text-yellow-500 border border-yellow-500/20 uppercase tracking-widest animate-pulse italic">● Waiting Payment</span>
                                @else
                                    <span class="text-[9px] font-black px-3 py-1 rounded-full bg-zinc-500/10 text-zinc-500 border border-zinc-500/20 uppercase tracking-widest italic">● {{ $b->payment_status }}</span>
                                @endif
                            </div>

                            <h3 class="text-2xl font-black text-white group-hover:text-red-600 transition-colors uppercase italic tracking-tighter">{{ $b->showtime->movie->title }}</h3>
                            
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-[10px] font-black text-zinc-500 uppercase tracking-widest">
                                <div class="flex items-center gap-2 text-zinc-400"><span class="text-red-600">📅</span> {{ $start->format('d M Y') }}</div>
                                <div class="flex items-center gap-2 text-zinc-400"><span class="text-red-600">⏰</span> {{ $start->format('H:i') }}</div>
                                <div class="flex items-center gap-2 text-zinc-400"><span class="text-red-600">🎬</span> {{ $b->showtime->studio->name }}</div>
                                <div class="flex items-center gap-2 text-red-500"><span class="text-red-600">💺</span> {{ $seatList }}</div>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="w-full lg:w-auto flex flex-col items-end gap-4">
                            <div class="text-right">
                                <p class="text-[9px] font-black text-zinc-600 uppercase tracking-[0.3em] mb-1">Total Tagihan</p>
                                <p class="text-2xl font-black text-white italic tracking-tighter">Rp{{ number_format($b->total_price,0,',','.') }}</p>
                            </div>

                            <div class="flex flex-wrap lg:flex-nowrap gap-3 w-full lg:w-auto">
                                @if($b->payment_status === 'pending')
                                    <a href="{{ route('booking.payment.page', $b->id) }}"
                                       class="w-full lg:w-auto px-10 py-4 rounded-2xl bg-red-600 hover:bg-red-700 text-white font-black text-[10px] uppercase tracking-[0.2em] transition-all transform hover:scale-105 shadow-xl shadow-red-600/20 text-center italic">
                                        Bayar Sekarang
                                    </a>
                                @elseif($b->payment_status === 'paid')
                                    <a href="{{ route('booking.ticket', $b->id) }}"
                                       class="px-6 py-4 rounded-2xl bg-white/5 border border-white/10 text-white font-black text-[10px] uppercase tracking-widest hover:bg-white hover:text-black transition-all italic">
                                        E-Ticket
                                    </a>
                                    <a href="{{ route('booking.ticket.download', $b->id) }}"
                                       class="px-6 py-4 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-black text-[10px] uppercase tracking-widest transition-all shadow-lg shadow-emerald-600/20 italic">
                                        Download
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-12">{{ $bookings->links() }}</div>
    @endif
</div>

{{-- SCRIPT: AUTO-REFRESH STATUS --}}
<script>
    // Jika ada booking yang berstatus 'pending', refresh halaman setiap 15 detik
    // untuk memeriksa apakah callback Midtrans sudah mengubah status ke 'paid'
    @if($bookings->where('payment_status', 'pending')->count() > 0)
        setTimeout(function() {
            window.location.reload();
        }, 15000); 
    @endif
</script>
@endsection