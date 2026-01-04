@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 flex items-center justify-center px-4 py-10">
        <div class="w-full max-w-4xl grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- LEFT: PAYMENT INFO --}}
            <div class="bg-white rounded-2xl shadow p-6 lg:p-8">
                <h2 class="text-2xl font-bold text-gray-900">Pembayaran QRIS</h2>
                <p class="mt-2 text-sm text-gray-600">Scan QR menggunakan <span class="font-medium">m-banking</span>.</p>

                <div class="mt-6 space-y-4 text-sm">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">Total</span>
                        <span class="font-semibold text-gray-900">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                    </div>
                    {{-- ... detail lainnya ... --}}
                </div>

                {{-- DITAMBAHKAN: Elemen status badge agar script polling bekerja --}}
                <div id="payment-status-badge" class="mt-6 p-4 rounded-xl border border-yellow-200 bg-yellow-50 text-center">
                    <span class="text-xs font-bold text-yellow-700 uppercase animate-pulse">Menunggu Pembayaran...</span>
                </div>

                <div class="mt-6 flex flex-col sm:flex-row gap-3">
                    <button type="button" onclick="copyQr()" class="bg-indigo-600 text-white px-4 py-2 rounded-xl text-sm font-bold">Copy QR String</button>
                    <button type="button" onclick="window.location.reload()" class="bg-emerald-600 text-white px-4 py-2 rounded-xl text-sm font-bold">Refresh</button>
                </div>
            </div>

            {{-- RIGHT: QR CODE --}}
            <div class="bg-white rounded-2xl shadow p-6 lg:p-8 flex flex-col items-center justify-center">
                <div class="text-center mb-6">
                    <div class="text-sm font-medium text-gray-700 uppercase tracking-widest">QR Code</div>
                </div>

                {{-- PERBAIKAN: Container QR dengan padding putih lebar (Quiet Zone) agar simulator tidak error --}}
                <div class="p-8 bg-white border-2 border-gray-100 rounded-3xl shadow-inner">
                    <div id="qrcode"></div>
                </div>

                <input type="hidden" id="qr-string" value="{{ $qrString }}">
            </div>
        </div>
    </div>

   <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    // Render QR Visual
    new QRCode(document.getElementById("qrcode"), {
        text: "{{ $qrString }}",
        width: 280, height: 280, colorDark: "#050509"
    });

    // Polling Status Otomatis setiap 3 detik
    const checkStatus = setInterval(async () => {
        try {
            const res = await fetch("{{ route('booking.show', $booking->id) }}", { 
                headers: { 'Accept': 'application/json' } 
            });
            const data = await res.json();
            
            if (data.payment_status === 'paid') {
                clearInterval(checkStatus);
                document.getElementById('payment-status-badge').innerHTML = "PEMBAYARAN BERHASIL!";
                setTimeout(() => window.location.href = "{{ route('booking.ticket', $booking->id) }}", 1500);
            }
        } catch (e) { console.error(e); }
    }, 3000);
</script><script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    // Render QR Visual
    new QRCode(document.getElementById("qrcode"), {
        text: "{{ $qrString }}",
        width: 280, height: 280, colorDark: "#050509"
    });

    // Polling Status Otomatis setiap 3 detik
    const checkStatus = setInterval(async () => {
        try {
            const res = await fetch("{{ route('booking.show', $booking->id) }}", { 
                headers: { 'Accept': 'application/json' } 
            });
            const data = await res.json();
            
            if (data.payment_status === 'paid') {
                clearInterval(checkStatus);
                document.getElementById('payment-status-badge').innerHTML = "PEMBAYARAN BERHASIL!";
                setTimeout(() => window.location.href = "{{ route('booking.ticket', $booking->id) }}", 1500);
            }
        } catch (e) { console.error(e); }
    }, 3000);
</script>
@endsection