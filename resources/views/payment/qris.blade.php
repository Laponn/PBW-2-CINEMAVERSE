@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="max-w-md w-full bg-white p-6 rounded-lg shadow text-center">

        <h2 class="text-xl font-bold mb-2">Pembayaran QRIS</h2>
        <p class="text-sm text-gray-600 mb-4">
            Scan QR di bawah menggunakan <br>
            <span class="font-semibold">m-banking atau e-wallet</span>
        </p>

        <div id="qrcode" class="flex justify-center mb-4"></div>

        <div class="text-xs text-gray-500 space-y-1 mb-4">
            <p>
                <strong>Total:</strong>
                Rp {{ number_format($booking->total_price, 0, ',', '.') }}
            </p>

            <p>
                <strong>Order ID:</strong> {{ $orderId }}
            </p>

            <p>
                <strong>Merchant:</strong> CinemaVerse
            </p>
        </div>

        <input type="hidden" id="qr-string" value="{{ $qrString }}">

        <p class="text-xs text-gray-400">
            QR hanya berlaku untuk satu kali pembayaran
        </p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>

<script>
    const qrString = document.getElementById('qr-string').value;

    if (qrString) {
        new QRCode(document.getElementById("qrcode"), {
            text: qrString,
            width: 256,
            height: 256,
        });
    }
</script>
@endsection
