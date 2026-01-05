{{-- resources/views/booking/ticket.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center p-6 py-20">
    <div class="w-full max-w-md relative group">
        
        {{-- Card Body --}}
        <div class="bg-white text-zinc-900 rounded-[2.5rem] overflow-hidden shadow-[0_0_80px_rgba(255,255,255,0.05)] relative">
            
            {{-- Header Tiket --}}
            <div class="bg-zinc-900 p-8 text-center text-white">
                <div class="text-[10px] font-black tracking-[0.5em] text-red-600 mb-2 uppercase italic">CinemaVerse</div>
                <h1 class="text-2xl font-black uppercase italic tracking-tighter">OFFICIAL E-TICKET</h1>
            </div>

            {{-- Info Film --}}
            <div class="p-8 space-y-6">
                <div class="text-center space-y-2">
                    <p class="text-[10px] font-black text-zinc-400 uppercase tracking-widest">Movie Title</p>
                    <h2 class="text-3xl font-black uppercase tracking-tighter italic leading-none">{{ $booking->showtime->movie->title }}</h2>
                    <p class="text-xs font-bold text-zinc-500 uppercase">{{ $booking->showtime->movie->duration_minutes }} Minutes • Animation</p>
                </div>

                <div class="grid grid-cols-2 gap-8 border-y border-dashed border-zinc-200 py-6">
                    <div class="space-y-1">
                        <p class="text-[10px] font-black text-zinc-400 uppercase tracking-widest">Cinema</p>
                        <p class="text-sm font-black">{{ $booking->showtime->studio->branch->name }}</p>
                        <p class="text-[10px] font-bold text-zinc-500 uppercase">{{ $booking->showtime->studio->name }}</p>
                    </div>
                    <div class="space-y-1 text-right">
                        <p class="text-[10px] font-black text-zinc-400 uppercase tracking-widest">Date & Time</p>
                        <p class="text-sm font-black uppercase">{{ \Carbon\Carbon::parse($booking->showtime->start_time)->format('d M Y') }}</p>
                        <p class="text-sm font-black text-red-600">{{ \Carbon\Carbon::parse($booking->showtime->start_time)->format('H:i') }}</p>
                    </div>
                </div>

                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-[10px] font-black text-zinc-400 uppercase tracking-widest">Seats</p>
                        <p class="text-2xl font-black italic tracking-tighter">{{ $booking->tickets->map(fn($t) => $t->seat->label)->join(', ') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] font-black text-zinc-400 uppercase tracking-widest">Booking Code</p>
                        <p class="text-sm font-black font-mono">{{ $booking->booking_code }}</p>
                    </div>
                </div>

                {{-- QR Code untuk Check-in --}}
                <div class="pt-6 flex flex-col items-center gap-4">
                    <div class="p-2 border-2 border-zinc-100 rounded-2xl">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ $booking->booking_code }}" class="w-32 h-32">
                    </div>
                    <p class="text-[10px] font-black text-zinc-400 uppercase tracking-widest">Scan this at the entrance</p>
                </div>
            </div>

            {{-- Potongan Lubang Tiket --}}
            <div class="absolute left-0 top-[110px] -translate-x-1/2 w-8 h-8 bg-[#05070b] rounded-full border-r border-white/5"></div>
            <div class="absolute right-0 top-[110px] translate-x-1/2 w-8 h-8 bg-[#05070b] rounded-full border-l border-white/5"></div>
        </div>

        <a href="{{ route('booking.index') }}" class="block text-center mt-10 text-[10px] font-black text-zinc-500 hover:text-white uppercase tracking-[0.3em] transition">
            ← Back 
        </a>
    </div>
</div>
@endsection