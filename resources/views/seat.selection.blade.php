<!DOCTYPE html>
<html lang="id">
<head>
    <title>Pilih Kursi - {{ $showtime->movie->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* CSS Khusus Kursi */
        .seat-checkbox { display: none; }
        .seat-label {
            display: flex; align-items: center; justify-content: center;
            width: 35px; height: 35px; border-radius: 6px; cursor: pointer; font-size: 10px; font-weight: bold;
            transition: all 0.2s;
        }
        
        /* Available (Abu Gelap -> Hover Hijau) */
        .seat-avail { background-color: #374151; color: #9CA3AF; }
        .seat-avail:hover { background-color: #10B981; color: white; transform: scale(1.1); }
        
        /* Selected (Biru) saat checkbox dicentang */
        .seat-checkbox:checked + .seat-label { background-color: #3B82F6; color: white; box-shadow: 0 0 10px #3B82F6; }
        
        /* Booked/Sold (Merah) */
        .seat-sold { background-color: #EF4444; color: white; cursor: not-allowed; opacity: 0.6; }
        
        /* Maintenance (Hitam/Invisible) */
        .seat-broken { background-color: #1F2937; color: #374151; cursor: not-allowed; border: 1px dashed #374151; }

        /* Layar Lengkung */
        .screen-container { perspective: 1000px; margin-bottom: 40px; }
        .screen {
            height: 60px; width: 80%; margin: 0 auto; background: linear-gradient(to bottom, #ffffff, rgba(255,255,255,0));
            transform: rotateX(-30deg) scale(0.8);
            box-shadow: 0 20px 50px rgba(255,255,255,0.1);
            border-radius: 20px; opacity: 0.8;
        }
    </style>
</head>
<body class="bg-gray-900 text-white min-h-screen flex flex-col items-center justify-center p-4">

    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold">{{ $showtime->movie->title }}</h2>
        <p class="text-gray-400 mt-1">
            {{ $showtime->studio->name }} <span class="mx-2">•</span> 
            {{ \Carbon\Carbon::parse($showtime->start_time)->format('H:i') }} <span class="mx-2">•</span>
            Rp {{ number_format($showtime->price) }}
        </p>
    </div>

    <div class="screen-container w-full max-w-2xl">
        <div class="screen"></div>
        <p class="text-center text-xs text-gray-500 mt-4">LAYAR BIOSKOP</p>
    </div>

    <form action="{{ route('booking.store') }}" method="POST" class="w-full max-w-4xl flex flex-col items-center">
        @csrf
        <input type="hidden" name="showtime_id" value="{{ $showtime->id }}">

        <div class="bg-gray-800 p-8 rounded-2xl shadow-2xl border border-gray-700 mb-8 inline-block">
            <div class="grid gap-3 justify-center" style="grid-template-columns: repeat({{ $showtime->studio->total_cols }}, minmax(0, 1fr));">
                @foreach($showtime->studio->seats as $seat)
                    @php
                        // Logika Status Kursi
                        $isBooked = in_array($seat->id, $bookedSeatIds);
                        $isMaintenance = !$seat->is_usable;
                    @endphp

                    @if($isMaintenance)
                        <div class="seat-label seat-broken">X</div>
                    @elseif($isBooked)
                        <div class="seat-label seat-sold" title="Sudah Dipesan">{{ $seat->row_label }}{{ $seat->seat_number }}</div>
                    @else
                        <label>
                            <input type="checkbox" name="seats[]" value="{{ $seat->id }}" class="seat-checkbox">
                            <div class="seat-label seat-avail">
                                {{ $seat->row_label }}{{ $seat->seat_number }}
                            </div>
                        </label>
                    @endif
                @endforeach
            </div>
        </div>

        <div class="flex gap-6 text-sm text-gray-400 mb-8">
            <div class="flex items-center gap-2"><div class="w-4 h-4 bg-gray-600 rounded"></div> Tersedia</div>
            <div class="flex items-center gap-2"><div class="w-4 h-4 bg-blue-500 rounded"></div> Dipilih</div>
            <div class="flex items-center gap-2"><div class="w-4 h-4 bg-red-500 rounded"></div> Terjual</div>
        </div>

        <button type="submit" class="bg-gradient-to-r from-red-600 to-pink-600 hover:from-red-700 hover:to-pink-700 text-white font-bold py-4 px-12 rounded-full shadow-lg transform transition hover:scale-105">
            Konfirmasi Pembayaran
        </button>
        <a href="{{ route('movie.show', $showtime->movie_id) }}" class="mt-4 text-gray-500 hover:text-white text-sm">Batal</a>
    </form>

</body>
</html>