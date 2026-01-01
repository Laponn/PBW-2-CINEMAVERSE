@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="max-w-md w-full bg-white p-6 rounded-lg shadow text-center">

        <h2 class="text-xl font-bold mb-4">Konfirmasi Pembayaran</h2>

        <div class="text-sm text-gray-700 mb-4 space-y-1">
            <p><strong>Total:</strong>
                Rp {{ number_format($booking->total_price, 0, ',', '.') }}
            </p>
            <p><strong>Metode:</strong> QRIS</p>
            <p><strong>Merchant:</strong> CinemaVerse</p>
        </div>

        <form method="POST" action="{{ route('booking.payment', $booking->id) }}">
            @csrf
            <button
                type="submit"
                class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700"
            >
                Bayar dengan QRIS
            </button>
        </form>

    </div>
</div>
@endsection
