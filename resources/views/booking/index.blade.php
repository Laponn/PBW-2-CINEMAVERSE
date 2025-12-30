<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Booking Saya</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#05070b] text-white min-h-screen p-6">
  <div class="max-w-5xl mx-auto space-y-6">

    <div class="flex items-center justify-between gap-3">
      <div>
        <h1 class="text-2xl font-extrabold">Booking Saya</h1>
        <p class="text-sm text-gray-400">Daftar transaksi (pending/paid)</p>
      </div>

      <div class="flex gap-2 text-sm">
        <a href="{{ route('booking.index') }}"
           class="px-4 py-2 rounded-full border border-white/10 {{ !$status ? 'bg-white/10' : 'bg-transparent hover:bg-white/5' }}">
          Semua
        </a>
        <a href="{{ route('booking.index', ['status' => 'pending']) }}"
           class="px-4 py-2 rounded-full border border-white/10 {{ $status==='pending' ? 'bg-yellow-500/20 border-yellow-500/40' : 'bg-transparent hover:bg-white/5' }}">
          Pending
        </a>
        <a href="{{ route('booking.index', ['status' => 'paid']) }}"
           class="px-4 py-2 rounded-full border border-white/10 {{ $status==='paid' ? 'bg-green-500/20 border-green-500/40' : 'bg-transparent hover:bg-white/5' }}">
          Paid
        </a>
      </div>
    </div>

    @if(session('success'))
      <div class="bg-green-500/15 border border-green-500/30 text-green-200 rounded-2xl p-4">
        {{ session('success') }}
      </div>
    @endif

    @if(session('error'))
      <div class="bg-red-500/15 border border-red-500/30 text-red-200 rounded-2xl p-4">
        {{ session('error') }}
      </div>
    @endif

    @if($bookings->count() === 0)
      <div class="bg-black/60 border border-white/10 rounded-3xl p-8 text-center text-gray-300">
        Belum ada booking.
      </div>
    @else
      <div class="space-y-4">
        @foreach($bookings as $b)
          @php
            $seatList = $b->tickets->map(fn($t) => $t->seat->row_label.$t->seat->seat_number)->join(', ');
            $start = \Carbon\Carbon::parse($b->showtime->start_time);
          @endphp

          <div class="bg-black/70 border border-white/10 rounded-3xl p-5 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="space-y-1">
              <div class="flex items-center gap-2">
                <span class="text-xs uppercase tracking-[0.25em] text-gray-400">Code</span>
                <span class="font-semibold">{{ $b->booking_code }}</span>

                @if($b->payment_status === 'paid')
                  <span class="ml-2 text-xs px-2 py-1 rounded-full bg-green-500/20 border border-green-500/30 text-green-200">
                    PAID
                  </span>
                @else
                  <span class="ml-2 text-xs px-2 py-1 rounded-full bg-yellow-500/20 border border-yellow-500/30 text-yellow-200">
                    PENDING
                  </span>
                @endif
              </div>

              <div class="text-lg font-bold">{{ $b->showtime->movie->title }}</div>
              <div class="text-sm text-gray-300">
                {{ $start->format('d M Y') }} • {{ $start->format('H:i') }}
                • {{ $b->showtime->studio->name }} • {{ $b->showtime->studio->branch->name }}
              </div>
              <div class="text-sm text-red-400 font-semibold">
                Kursi: {{ $seatList }}
              </div>
              <div class="text-sm text-gray-200">
                Total: <span class="font-bold">Rp{{ number_format($b->total_price,0,',','.') }}</span>
              </div>
            </div>

            <div class="flex gap-2">
              @if($b->payment_status === 'pending')
                <a href="{{ route('booking.payment', $b->id) }}"
                   class="px-4 py-2 rounded-full bg-red-600 hover:bg-red-700 font-semibold text-sm">
                  Bayar (QR)
                </a>
              @else
                <a href="{{ route('booking.ticket', $b->id) }}"
                   class="px-4 py-2 rounded-full bg-white/10 hover:bg-white/15 border border-white/10 font-semibold text-sm">
                  Lihat Ticket
                </a>
              @endif
            </div>
          </div>
        @endforeach
      </div>

      <div class="pt-2">
        {{ $bookings->links() }}
      </div>
    @endif

    <div class="pt-2">
      <a href="{{ route('home') }}" class="text-sm text-gray-400 hover:text-white">← Kembali ke Home</a>
    </div>
  </div>
</body>
</html>
