<!DOCTYPE html>
<html lang="id">
<head>
    <title>{{ $movie->title }} - CinemaVerse</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white font-sans pt-20">

    <div class="container mx-auto px-6 py-10">
        <a href="{{ route('home') }}" class="inline-flex items-center text-gray-400 hover:text-white mb-6 transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Home
        </a>

        <div class="flex flex-col md:flex-row gap-10">
            <div class="w-full md:w-1/3 lg:w-1/4">
                <img src="{{ $movie->poster_url }}" class="w-full rounded-lg shadow-2xl shadow-red-900/20 border border-gray-700">
            </div>

            <div class="w-full md:w-2/3 lg:w-3/4">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">{{ $movie->title }}</h1>
                
                <div class="flex items-center gap-4 text-sm text-gray-300 mb-6">
                    <span class="bg-gray-800 px-3 py-1 rounded border border-gray-600">{{ $movie->duration_minutes }} Menit</span>
                    <span class="bg-green-900 text-green-300 px-3 py-1 rounded border border-green-700 uppercase tracking-wider text-xs font-bold">{{ str_replace('_', ' ', $movie->status) }}</span>
                    <span>{{ \Carbon\Carbon::parse($movie->release_date)->format('Y') }}</span>
                </div>

                @if($movie->trailer_url)
                <div class="mb-8 rounded-lg overflow-hidden border border-gray-700">
                    <iframe class="w-full h-64 md:h-96" src="https://www.youtube.com/embed/{{ $movie->trailer_url }}" title="Trailer" frameborder="0" allowfullscreen></iframe>
                </div>
                @endif

                <p class="text-gray-300 leading-relaxed mb-8 text-lg">{{ $movie->description }}</p>

                <div class="bg-gray-800 p-6 rounded-xl border border-gray-700">
                    <h3 class="text-xl font-bold mb-4 border-b border-gray-600 pb-2 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Jadwal Tayang
                    </h3>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @forelse($movie->showtimes as $showtime)
                            <a href="{{ route('booking.seat', $showtime->id) }}" class="group block bg-gray-900 border border-gray-600 hover:border-red-500 hover:bg-gray-800 rounded-lg p-3 transition text-center">
                                <div class="text-2xl font-bold text-white group-hover:text-red-500">
                                    {{ \Carbon\Carbon::parse($showtime->start_time)->format('H:i') }}
                                </div>
                                <div class="text-xs text-gray-400 mt-1 uppercase tracking-wide">{{ $showtime->studio->name }}</div>
                                <div class="text-sm font-semibold text-green-400 mt-2">Rp {{ number_format($showtime->price) }}</div>
                            </a>
                        @empty
                            <p class="text-gray-500 col-span-4 text-center py-4">Belum ada jadwal tayang tersedia.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>