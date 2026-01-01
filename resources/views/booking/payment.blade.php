{{-- resources/views/booking/payment.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center p-6 py-20">
    <div class="w-full max-w-lg relative">
        {{-- Glow Effect --}}
        <div class="absolute -inset-4 bg-red-600/20 blur-3xl rounded-full opacity-50"></div>

        <div class="relative bg-zinc-950 border border-white/10 rounded-[2.5rem] p-8 space-y-8 shadow-2xl">
            <div class="text-center space-y-2">
                <div class="text-[10px] text-red-500 font-black uppercase tracking-[0.4em]">Payment Simulation</div>
                <h2 class="text-3xl font-black text-white italic tracking-tighter uppercase">{{ $booking->booking_code }}</h2>
            </div>

            <div class="bg-zinc-900/50 rounded-3xl p-6 border border-white/5 space-y-4 text-center">
                <p class="text-xs text-zinc-500 font-bold uppercase tracking-widest">Silakan Scan QR Berikut</p>
                <div class="bg-white p-4 rounded-2xl inline-block shadow-[0_0_50px_rgba(255,255,255,0.1)]">
                    <img class="w-48 h-48" src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode("PAY:{$booking->booking_code}") }}">
                </div>
                <div class="pt-4 border-t border-white/5">
                    <p class="text-[10px] text-zinc-500 uppercase font-black tracking-[0.2em] mb-1">Total Pembayaran</p>
                    <p class="text-3xl font-black text-white italic">Rp{{ number_format($booking->total_price,0,',','.') }}</p>
                </div>
            </div>

            <div class="space-y-4">
                <div class="flex justify-between text-[10px] font-black uppercase tracking-widest text-zinc-400 px-2">
                    <span>Film</span>
                    <span class="text-white">{{ $booking->showtime->movie->title }}</span>
                </div>
                <div class="flex justify-between text-[10px] font-black uppercase tracking-widest text-zinc-400 px-2">
                    <span>Jadwal</span>
                    <span class="text-white">{{ \Carbon\Carbon::parse($booking->showtime->start_time)->format('d M, H:i') }}</span>
                </div>
                <div class="flex justify-between text-[10px] font-black uppercase tracking-widest text-zinc-400 px-2">
                    <span>Kursi</span>
                    <span class="text-red-500 font-black italic">{{ $booking->tickets->map(fn($t) => $t->seat->label)->join(', ') }}</span>
                </div>
            </div>

            <form method="POST" action="{{ route('booking.pay', $booking->id) }}">
                @csrf
                <button class="w-full bg-red-600 hover:bg-red-700 text-white py-5 rounded-2xl font-black uppercase tracking-[0.3em] text-xs transition-all transform hover:scale-[1.02] shadow-xl shadow-red-600/30">
                    Konfirmasi Pembayaran
                </button>
            </form>

            <a href="{{ route('booking.index') }}" class="block text-center text-[10px] font-black text-zinc-600 hover:text-white uppercase tracking-widest transition">
                ← Batalkan Pembayaran
            </a>
        </div>
    </div>
</div>
@endsection