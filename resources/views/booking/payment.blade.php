<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Pembayaran - {{ $booking->booking_code }}</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#05070b] text-white min-h-screen flex items-center justify-center p-6">
  <div class="w-full max-w-lg bg-black/70 border border-white/10 rounded-3xl p-6 space-y-4">
    <div class="flex justify-between items-start gap-3">
      <div>
        <div class="text-xs text-gray-400 uppercase tracking-[0.25em]">Payment</div>
        <div class="text-xl font-bold">{{ $booking->booking_code }}</div>
        <div class="text-sm text-gray-300">
          {{ $booking->showtime->movie->title }} • {{ \Carbon\Carbon::parse($booking->showtime->start_time)->format('d M Y H:i') }}
        </div>
        <div class="text-sm text-gray-400">
          {{ $booking->showtime->studio->name }} • {{ $booking->showtime->studio->branch->name }}
        </div>
      </div>
      <div class="text-right">
        <div class="text-xs text-gray-400">Status</div>
        <div class="font-semibold {{ $booking->payment_status === 'paid' ? 'text-green-400' : 'text-yellow-400' }}">
          {{ strtoupper($booking->payment_status) }}
        </div>
      </div>
    </div>

    <div class="text-sm">
      <div class="text-gray-400">Kursi</div>
      <div class="font-semibold text-red-400">
        {{ $booking->tickets->map(fn($t) => $t->seat->label)->join(', ') }}
      </div>
    </div>

    <div class="flex justify-between text-lg font-bold border-t border-white/10 pt-4">
      <span>Total</span>
      <span class="text-red-400">Rp{{ number_format($booking->total_price,0,',','.') }}</span>
    </div>

    @php
      $qrPayload = "BOOKING:{$booking->booking_code}|TOTAL:{$booking->total_price}";
    @endphp

    <div class="bg-white rounded-2xl p-4 flex items-center justify-center">
      <img
        alt="QR Payment"
        src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&data={{ urlencode($qrPayload) }}">
    </div>

    <form method="POST" action="{{ route('booking.pay', $booking->id) }}">
      @csrf
      <button class="w-full bg-red-600 hover:bg-red-700 py-3 rounded-full font-semibold">
        Saya sudah bayar (Simulasi)
      </button>
    </form>

    <a href="{{ route('movies.ticket', $booking->showtime->movie_id) }}" class="block text-center text-sm text-gray-400 hover:text-white">
      Kembali
    </a>
  </div>
</body>
</html>
