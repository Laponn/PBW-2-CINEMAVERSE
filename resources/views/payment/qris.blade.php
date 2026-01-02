@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center px-4 py-10">
    <div class="w-full max-w-4xl grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- LEFT: PAYMENT INFO --}}
        <div class="bg-white rounded-2xl shadow p-6 lg:p-8">
            <h2 class="text-2xl font-bold text-gray-900">
                Pembayaran QRIS
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                Scan QR menggunakan <span class="font-medium">m-banking</span> atau
                <span class="font-medium">e-wallet</span>.
            </p>

            {{-- TRANSACTION DETAIL --}}
            <div class="mt-6 space-y-4 text-sm">
                <div class="flex justify-between items-center">
                    <span class="text-gray-500">Total</span>
                    <span class="font-semibold text-gray-900">
                        Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                    </span>
                </div>

                <div class="flex justify-between items-center gap-3">
                    <span class="text-gray-500">Order ID</span>
                    <div class="flex items-center gap-2">
                        <span class="font-mono text-gray-900 text-xs sm:text-sm">
                            {{ $orderId }}
                        </span>
                        <button
                            type="button"
                            onclick="copyOrderId()"
                            class="px-3 py-1.5 text-xs rounded-lg
                                   bg-blue-600 text-white
                                   hover:bg-blue-700 transition"
                        >
                            Copy
                        </button>
                    </div>
                </div>

                <div class="flex justify-between items-center">
                    <span class="text-gray-500">Merchant</span>
                    <span class="text-gray-900">CinemaVerse</span>
                </div>
            </div>

            {{-- NOTE --}}
            <div class="mt-6 rounded-xl border border-yellow-300 bg-yellow-50 p-4 text-sm text-yellow-900">
                <div class="font-semibold mb-1">Catatan</div>
                QR hanya berlaku untuk <span class="font-medium">1 kali pembayaran</span>.
                Jangan tutup halaman sebelum pembayaran selesai.
            </div>

            {{-- ACTION BUTTONS --}}
            <div class="mt-6 flex flex-col sm:flex-row gap-3">
                <a href="{{ route('booking.show', $booking->id) }}"
                   class="inline-flex justify-center items-center
                          rounded-xl px-4 py-2
                          bg-gray-900 text-white
                          hover:bg-black transition">
                    Kembali ke Booking
                </a>

                <button
                    type="button"
                    onclick="window.location.reload()"
                    class="inline-flex justify-center items-center
                           rounded-xl px-4 py-2
                           bg-emerald-600 text-white
                           hover:bg-emerald-700 transition"
                >
                    Refresh
                </button>
            </div>
        </div>

        {{-- RIGHT: QR CODE --}}
        <div class="bg-white rounded-2xl shadow p-6 lg:p-8 flex flex-col items-center justify-center">
            <div class="text-center">
                <div class="text-sm font-medium text-gray-700">QR Code</div>
                <div class="text-xs text-gray-500">
                    Scan untuk menyelesaikan pembayaran
                </div>
            </div>

            <div class="mt-6 w-full flex justify-center">
                <div id="qrcode"
                     class="p-4 rounded-2xl border bg-white shadow-sm">
                </div>
            </div>

            <input type="hidden" id="qr-string" value="{{ $qrString }}">

            <div class="mt-4 text-xs text-gray-400 text-center">
                Jika QR tidak muncul, klik tombol <span class="font-medium">Refresh</span>.
            </div>
        </div>
    </div>
</div>

{{-- QR GENERATOR --}}
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>

<script>
    const qrString = document.getElementById('qr-string').value;

    if (qrString) {
        new QRCode(document.getElementById("qrcode"), {
            text: qrString,
            width: 280,
            height: 280,
        });
    }

    function copyOrderId() {
        navigator.clipboard.writeText('{{ $orderId }}');
        alert('Order ID berhasil disalin');
    }
</script>
@endsection
