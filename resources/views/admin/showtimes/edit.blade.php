@extends('layouts.admin')

@section('content')
<div class="max-w-xl mx-auto bg-white p-8 rounded-lg shadow">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Edit Jadwal Tayang</h2>

    <form action="{{ route('admin.showtimes.update', $showtime->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        {{-- PILIH FILM --}}
        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Pilih Film</label>
            <select name="movie_id" class="w-full border border-gray-300 rounded px-3 py-2 bg-white" required>
                @foreach($movies as $movie)
                    <option value="{{ $movie->id }}" {{ $showtime->movie_id == $movie->id ? 'selected' : '' }}>
                        {{ $movie->title }} ({{ $movie->duration_minutes }} Min)
                    </option>
                @endforeach
            </select>
        </div>

        {{-- PILIH STUDIO --}}
        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Pilih Studio</label>
            <select name="studio_id" class="w-full border border-gray-300 rounded px-3 py-2 bg-white" required>
                @foreach($studios->groupBy('branch.name') as $branchName => $studioGroup)
                    <optgroup label="{{ $branchName }}">
                        @foreach($studioGroup as $studio)
                            <option value="{{ $studio->id }}" {{ $showtime->studio_id == $studio->id ? 'selected' : '' }}>
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
            {{-- Format datetime-local value harus Y-m-d\TH:i --}}
            <input type="datetime-local" name="start_time" value="{{ \Carbon\Carbon::parse($showtime->start_time)->format('Y-m-d\TH:i') }}" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500" required>
        </div>

        {{-- HARGA TIKET --}}
        <div class="mb-6">
            <label class="block text-gray-700 font-bold mb-2">Harga Tiket (Rp)</label>
            <input type="number" name="price" value="{{ $showtime->price }}" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500" min="0" required>
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('admin.showtimes.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Batal</a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update Jadwal</button>
        </div>
    </form>
</div>
@endsection