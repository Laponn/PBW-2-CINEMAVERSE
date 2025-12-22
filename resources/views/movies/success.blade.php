@extends('layouts.app')

@section('content')
<div class="bg-[#05070b] min-h-screen flex items-center justify-center pt-20">
    <div class="bg-white text-black p-8 rounded-3xl w-full max-w-md shadow-2xl overflow-hidden relative">
        {{-- Efek Tiket Sobek --}}
        <div class="absolute -left-4 top-1/2 w-8 h-8 bg-[#05070b] rounded-full"></div>
        <div class="absolute -right-4 top-1/2 w-8 h-8 bg-[#05070b] rounded-full"></div>

        <div class="text-center border-b-2 border-dashed border-gray-300 pb-6 mb-6">
            <h1 class="text-xl font-black tracking-widest uppercase">E-TICKET CINEMAVERSE</h1>
            <p class="text-xs text-gray-500">Booking ID: #{{ $booking->id }}</p>
        </div>

        <div class="space-y-4 mb-6">
            <div>
                <p class="text-[10px] text-gray-400 uppercase font-bold">Film</p>
                <h2 class="text-xl font-bold uppercase">{{ $booking->showtime->movie->title }}</h2>
                <p class="text-sm">{{ $booking->showtime->movie->duration_minutes }} Menit</p>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-[10px] text-gray-400 uppercase font-bold">Bioskop & Cabang</p>
                    <p class="text-sm font-bold">{{ $booking->showtime->studio->name }}</p>
                    <p class="text-xs">{{ $booking->showtime->studio->branch->name }}</p>
                </div>
                <div>
                    <p class="text-[10px] text-gray-400 uppercase font-bold">Waktu Tayang</p>
                    <p class="text-sm font-bold">{{ \Carbon\Carbon::parse($booking->showtime->start_time)->format('d M Y') }}</p>
                    <p class="text-sm font-bold">{{ \Carbon\Carbon::parse($booking->showtime->start_time)->format('H:i') }}</p>
                </div>
            </div>

            <div>
                <p class="text-[10px] text-gray-400 uppercase font-bold">Kursi</p>
                <h3 class="text-lg font-black text-red-600">
                    {{ $booking->bookingSeats->map(fn($s) => $s->seat->row_label . $s->seat->seat_number)->join(', ') }}
                </h3>
            </div>
        </div>

        <div class="bg-gray-100 p-4 rounded-2xl flex justify-between items-center">
            <p class="text-sm font-bold">Total Pembayaran</p>
            <p class="text-lg font-black">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</p>
        </div>

        <a href="{{ route('home') }}" class="block text-center mt-8 text-sm font-bold text-gray-400 hover:text-black transition">Kembali ke Beranda</a>
    </div>
</div>
@endsection