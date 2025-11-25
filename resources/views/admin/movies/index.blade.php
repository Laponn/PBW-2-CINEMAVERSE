@extends('layouts.admin')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold">Daftar Film</h2>
    <a href="{{ route('admin.movies.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">+ Tambah Film</a>
</div>

<div class="bg-white rounded shadow overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-4 border-b">Poster</th>
                <th class="p-4 border-b">Judul</th>
                <th class="p-4 border-b">Status</th>
                <th class="p-4 border-b">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($movies as $movie)
            <tr class="hover:bg-gray-50">
                <td class="p-4 border-b">
                    <img src="{{ $movie->poster_url }}" class="h-16 w-12 object-cover rounded">
                </td>
                <td class="p-4 border-b font-bold">{{ $movie->title }}</td>
                <td class="p-4 border-b">
                    <span class="px-2 py-1 text-xs rounded {{ $movie->status == 'now_showing' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $movie->status }}
                    </span>
                </td>
                <td class="p-4 border-b">
                    <a href="{{ route('admin.movies.edit', $movie->id) }}" class="text-blue-600 hover:underline mr-2">Edit</a>
                    <form action="{{ route('admin.movies.destroy', $movie->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection