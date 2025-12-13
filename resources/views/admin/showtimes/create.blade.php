@extends('layouts.admin')

@section('content')
<div class="max-w-xl mx-auto bg-white p-8 rounded-lg shadow">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Buat Jadwal Tayang</h2>

    <form action="{{ route('admin.showtimes.store') }}" method="POST">
        @csrf
        
        {{-- PILIH FILM --}}
        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Pilih Film</label>
            <select name="movie_id" class="w-full border border-gray-300 rounded px-3 py-2 bg-white focus:outline-none focus:border-blue-500 select2" required>
                <option value="">-- Pilih Film --</option>
                @foreach($movies as $movie)
                    <option value="{{ $movie->id }}">{{ $movie->title }} ({{ $movie->duration_minutes }} Min)</option>
                @endforeach
            </select>
        </div>

        {{-- PILIH STUDIO (Grouped by Branch) --}}
        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Pilih Studio</label>
            <select name="studio_id" class="w-full border border-gray-300 rounded px-3 py-2 bg-white focus:outline-none focus:border-blue-500" required>
                <option value="">-- Pilih Studio --</option>
                {{-- Grouping Studio Berdasarkan Nama Cabang --}}
                @foreach($studios->groupBy('branch.name') as $branchName => $studioGroup)
                    <optgroup label="{{ $branchName }}">
                        @foreach($studioGroup as $studio)
                            <option value="{{ $studio->id }}">
                                {{ $studio->name }} ({{ ucfirst($studio->type) }})
                            </option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
        </div>

        {{-- WAKTU MULAI --}}
        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Waktu Mulai</label>
            <input type="datetime-local" name="start_time" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500" required>
            <p class="text-xs text-gray-500 mt-1">*Waktu selesai akan dihitung otomatis (Durasi Film + 10 Menit).</p>
        </div>

        {{-- HARGA TIKET --}}
        <div class="mb-6">
            <label class="block text-gray-700 font-bold mb-2">Harga Tiket (Rp)</label>
            <input type="number" name="price" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500" placeholder="Contoh: 50000" min="0" required>
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('admin.showtimes.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Batal</a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan Jadwal</button>
        </div>
    </form>
</div>
@endsection