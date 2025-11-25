@extends('layouts.admin')

@section('content')
<h2 class="text-2xl font-bold mb-6">Tambah Film Baru</h2>

<div class="bg-white p-6 rounded shadow max-w-2xl">
    <form action="{{ route('admin.movies.update', $movie->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
            
        <div class="mb-4">
            <label class="block text-gray-700">Judul Film</label>
            <input type="text" name="title" class="w-full border p-2 rounded" required>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-gray-700">Durasi (Menit)</label>
                <input type="number" name="duration_minutes" class="w-full border p-2 rounded" required>
            </div>
            <div>
                <label class="block text-gray-700">Status</label>
                <select name="status" class="w-full border p-2 rounded">
                    <option value="now_showing">Sedang Tayang</option>
                    <option value="coming_soon">Segera Tayang</option>
                    <option value="ended">Selesai</option>
                </select>
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Poster Gambar</label>
            <input type="file" name="poster" class="w-full border p-2 rounded" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Deskripsi</label>
            <textarea name="description" class="w-full border p-2 rounded h-32" required></textarea>
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700">ID Youtube Trailer (Contoh: dQw4w9WgXcQ)</label>
            <input type="text" name="trailer_url" class="w-full border p-2 rounded">
        </div>

        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Simpan Film</button>
    @method('PUT') </form>
</div>
@endsection