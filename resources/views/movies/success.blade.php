{{-- resources/views/movies/success.blade.php --}}
@extends('layouts.app')

@section('content')
<main class="min-h-screen flex flex-col items-center justify-center p-6 bg-[#050509]">
    <div class="text-center mb-10 space-y-3">
        <div class="w-20 h-20 bg-green-500/20 border border-green-500/30 rounded-full flex items-center justify-center mx-auto text-4xl shadow-[0_0_40px_rgba(34,197,94,0.2)]">
            ✅
        </div>
        <h1 class="text-4xl font-black uppercase italic tracking-tighter text-white">Pembayaran <span class="text-green-500">Berhasil!</span></h1>
        <p class="text-zinc-500 font-bold text-xs uppercase tracking-widest">Tiket digitalmu sudah aktif</p>
    </div>

    {{-- Tampilan Tiket Mirip yang Kamu Mau --}}
    <div class="bg-white text-zinc-950 p-8 rounded-[2.5rem] w-full max-w-md shadow-[0_0_80px_rgba(255,255,255,0.05)] relative overflow-hidden">
        {{-- Efek Tiket Sobek --}}
        <div class="absolute -left-4 top-[100px] w-8 h-8 bg-[#050509] rounded-full border-r border-zinc-200"></div>
        <div class="absolute -right-4 top-[100px] w-8 h-8 bg-[#050509] rounded-full border-l border-zinc-200"></div>

        <div class="text-center border-b border-dashed border-zinc-300 pb-6 mb-8">
            <h2 class="text-xl font-black tracking-[0.3em] uppercase italic">CINEMAVERSE TICKET</h2>
            <p class="text-[10px] font-bold text-zinc-400 mt-1 uppercase">Booking ID: #{{ $booking->booking_code }}</p>
        </div>

        <div class="space-y-6 mb-8">
            <div class="text-center">
                <p class="text-[10px] text-zinc-400 uppercase font-bold tracking-widest mb-1">Movie Title</p>
                <h3 class="text-3xl font-black uppercase tracking-tighter italic leading-none">{{ $booking->showtime->movie->title }}</h3>
            </div>

            <div class="grid grid-cols-2 gap-6 border-y border-zinc-100 py-6">
                <div>
                    <p class="text-[10px] text-zinc-400 uppercase font-bold tracking-widest">Studio & Branch</p>
                    <p class="text-sm font-black">{{ $booking->showtime->studio->name }}</p>
                    <p class="text-[10px] font-bold text-zinc-500 uppercase">{{ $booking->showtime->studio->branch->name }}</p>
                </div>
                <div class="text-right">
                    <p class="text-[10px] text-zinc-400 uppercase font-bold tracking-widest">Time</p>
                    <p class="text-sm font-black">{{ \Carbon\Carbon::parse($booking->showtime->start_time)->format('d M Y') }}</p>
                    <p class="text-sm font-black text-red-600">{{ \Carbon\Carbon::parse($booking->showtime->start_time)->format('H:i') }}</p>
                </div>
            </div>

            <div class="text-center">
                <p class="text-[10px] text-zinc-400 uppercase font-bold tracking-widest">Selected Seats</p>
                <h4 class="text-2xl font-black text-red-600 italic tracking-tighter">
                    {{ $booking->tickets->map(fn($t) => $t->seat->label)->join(', ') }}
                </h4>
            </div>
        </div>

        <div class="bg-zinc-100 p-4 rounded-2xl flex justify-between items-center mb-6">
            <p class="text-[10px] font-black uppercase text-zinc-500">Paid Total</p>
            <p class="text-lg font-black italic">Rp{{ number_format($booking->total_price, 0, ',', '.') }}</p>
        </div>

        <a href="{{ route('home') }}" class="block text-center text-[10px] font-black text-zinc-400 hover:text-red-600 transition uppercase tracking-[0.2em]">Kembali ke Beranda</a>
    </div>
</main>
@endsection