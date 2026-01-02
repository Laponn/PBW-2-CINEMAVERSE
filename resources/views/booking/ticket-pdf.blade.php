<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>E-Ticket</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .wrap { border: 1px solid #ddd; padding: 18px; border-radius: 10px; }
        h1 { margin: 0 0 10px; font-size: 18px; }
        .muted { color: #666; }
        .row { margin: 8px 0; }
        .label { width: 130px; display: inline-block; color: #666; }
        .qr { margin-top: 18px; text-align: center; }
    </style>
</head>
<body>
@php
    $start = \Carbon\Carbon::parse($booking->showtime->start_time);
    $seatList = $booking->tickets->map(fn($t) => $t->seat->row_label.$t->seat->seat_number)->join(', ');
@endphp

<div class="wrap">
    <h1>E-TICKET CINEMAVERSE</h1>
    <div class="row"><span class="label">Booking Code</span> : <strong>{{ $booking->booking_code }}</strong></div>
    <div class="row"><span class="label">Film</span> : <strong>{{ $booking->showtime->movie->title }}</strong></div>
    <div class="row"><span class="label">Tanggal</span> : {{ $start->format('d M Y') }}</div>
    <div class="row"><span class="label">Jam</span> : {{ $start->format('H:i') }} WIB</div>
    <div class="row"><span class="label">Studio</span> : {{ $booking->showtime->studio->name }}</div>
    <div class="row"><span class="label">Cabang</span> : {{ $booking->showtime->studio->branch->name }}</div>
    <div class="row"><span class="label">Kursi</span> : <strong>{{ $seatList }}</strong></div>
    <div class="row"><span class="label">Total</span> : <strong>Rp {{ number_format($booking->total_price,0,',','.') }}</strong></div>

    <div class="qr">
        <div class="muted">Scan saat check-in:</div>
        {{-- QR image via Google chart (simple untuk dompdf) --}}
        <img
            src="https://chart.googleapis.com/chart?chs=220x220&cht=qr&chl={{ urlencode($booking->booking_code) }}"
            alt="QR"
        >
    </div>
</div>
</body>
</html>
