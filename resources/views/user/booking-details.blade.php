@extends('layouts.app')

@section('content')
<div class="min-h-screen pt-24 pb-12 px-6 bg-[#050509]">
    <div class="max-w-4xl mx-auto">
        <div class="bg-zinc-900 border border-white/10 rounded-[3rem] overflow-hidden shadow-2xl relative">
            {{-- Status Pembayaran Badge (Pojok Kanan Atas) --}}
            <div class="absolute top-8 right-8 z-20">
                @php
                    $statusClasses = [
                        'paid' => 'bg-green-500/10 text-green-500 border-green-500/20',
                        'pending' => 'bg-yellow-500/10 text-yellow-500 border-yellow-500/20',
                        'cancelled' => 'bg-red-500/10 text-red-500 border-red-500/20',
                        'expired' => 'bg-zinc-500/10 text-zinc-500 border-zinc-500/20',
                    ];
                    $class = $statusClasses[$booking->payment_status] ?? $statusClasses['pending'];
                @endphp
                <span class="px-6 py-2 rounded-full border {{ $class }} text-[10px] font-black uppercase tracking-[0.2em] backdrop-blur-md">
                    {{ $booking->payment_status }}
                </span>
            </div>

            <div class="bg-red-600 p-10 text-center relative overflow-hidden">
                {{-- Pola Dekorasi --}}
                <div class="absolute inset-0 opacity-10 flex items-center justify-center">
                    <h1 class="text-9xl font-black italic tracking-tighter uppercase">CINEMAVERSE</h1>
                </div>
                <div class="relative z-10">
                    <h1 class="text-3xl font-black uppercase italic tracking-tighter text-white">E-Ticket Details</h1>
                    <p class="text-xs font-bold opacity-80 uppercase tracking-widest mt-2">Booking ID: {{ $booking->booking_code }}</p>
                </div>
            </div>
            
            <div class="p-10 md:p-16 grid md:grid-cols-2 gap-16">
                <div class="space-y-10">
                    <div>
                        <label class="text-[10px] font-black text-zinc-500 uppercase tracking-widest block mb-2">Movie Title</label>
                        <h2 class="text-4xl font-black text-white uppercase italic leading-none">{{ $booking->showtime->movie->title }}</h2>
                    </div>

                    <div class="grid grid-cols-2 gap-8">
                        <div>
                            <label class="text-[10px] font-black text-zinc-500 uppercase tracking-widest block mb-2">Date & Time</label>
                            <p class="text-sm font-bold text-white uppercase tracking-tight">
                                {{ \Carbon\Carbon::parse($booking->showtime->start_time)->format('D, d M Y') }}<br>
                                <span class="text-red-500">{{ \Carbon\Carbon::parse($booking->showtime->start_time)->format('H:i') }} WIB</span>
                            </p>
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-zinc-500 uppercase tracking-widest block mb-2">Studio & Class</label>
                            <p class="text-sm font-bold text-white uppercase">{{ $booking->showtime->studio->name }}</p>
                            <p class="text-[10px] font-bold text-red-500 uppercase">{{ $booking->showtime->studio->type }} Class</p>
                        </div>
                    </div>

                    <div>
                        <label class="text-[10px] font-black text-zinc-500 uppercase tracking-widest block mb-2">Seats Selected</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach($booking->tickets as $ticket)
                                <span class="px-4 py-2 bg-white/5 border border-white/10 rounded-xl text-lg font-black text-white">
                                    {{ $ticket->seat->row_label }}{{ $ticket->seat->seat_number }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    {{-- Info Pembayaran Tambahan --}}
                    <div class="pt-6 border-t border-white/5">
                        <div class="flex justify-between items-center">
                            <span class="text-[10px] font-black text-zinc-500 uppercase tracking-widest">Total Payment</span>
                            <span class="text-2xl font-black text-white">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col items-center justify-center bg-white p-10 rounded-[2.5rem] border-4 border-dashed border-zinc-900 shadow-xl">
                    {{-- Simulasi QR Code --}}
                    <div class="w-full aspect-square bg-zinc-100 flex items-center justify-center rounded-2xl mb-6 p-4">
                         <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ $booking->booking_code }}" alt="QR Code" class="w-full h-full">
                    </div>
                    <div class="text-center space-y-1">
                        <p class="text-[10px] font-black text-zinc-900 uppercase tracking-widest">Scan QR to Check-in</p>
                        <p class="text-[9px] font-medium text-zinc-400 uppercase italic">Show this to cinema staff</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-12 text-center">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-3 text-[10px] font-black text-zinc-500 hover:text-red-500 uppercase tracking-[0.3em] transition group">
                <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 19l-7-7m0 0l7-7m-7 7h18" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Kembali ke My Bookings
            </a>
        </div>
    </div>
</div>
@endsection