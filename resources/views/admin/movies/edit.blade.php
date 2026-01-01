@extends('layouts.admin')

@section('content')
{{-- Container agar Center --}}
<div class="min-h-screen flex items-center justify-center py-12 px-4">
    <div class="max-w-2xl w-full bg-white p-10 rounded-[2.5rem] shadow-2xl border border-zinc-200">
        
        <h2 class="text-center text-3xl font-black uppercase italic tracking-tighter text-zinc-900 mb-8">
            Edit <span class="text-red-600">Film</span>
        </h2>

        <form action="{{ route('admin.movies.update', $movie->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                {{-- Judul --}}
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-zinc-400 mb-1 ml-2">Judul Film</label>
                    <input type="text" name="title" value="{{ old('title', $movie->title) }}" 
                           class="w-full px-4 py-3 border border-zinc-300 rounded-2xl font-bold focus:ring-red-500 focus:border-red-500" required>
                </div>

                {{-- Durasi & Status --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-zinc-400 mb-1 ml-2">Durasi (Menit)</label>
                        <input type="number" name="duration_minutes" value="{{ old('duration_minutes', $movie->duration_minutes) }}" 
                               class="w-full px-4 py-3 border border-zinc-300 rounded-2xl font-bold focus:ring-red-500" required>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-zinc-400 mb-1 ml-2">Status</label>
                        <select name="status" class="w-full px-4 py-3 border border-zinc-300 rounded-2xl font-bold bg-white">
                            <option value="now_showing" {{ $movie->status == 'now_showing' ? 'selected' : '' }}>Sedang Tayang</option>
                            <option value="coming_soon" {{ $movie->status == 'coming_soon' ? 'selected' : '' }}>Segera Tayang</option>
                            <option value="ended" {{ $movie->status == 'ended' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>
                </div>

                {{-- Preview Poster & Upload --}}
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-zinc-400 mb-2 ml-2">Poster Film</label>
                    <div class="flex items-center gap-6 p-4 border border-zinc-200 rounded-2xl bg-zinc-50">
                        <img src="{{ str_starts_with($movie->poster_url, 'http') ? $movie->poster_url : asset($movie->poster_url) }}" 
                             class="w-20 rounded-lg shadow-md border border-white">
                        <div class="flex-1">
                            <input type="file" name="poster" class="text-xs text-zinc-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-black file:bg-zinc-200 file:text-zinc-700 hover:file:bg-zinc-300">
                            <p class="text-[9px] text-zinc-400 mt-2 italic">*Kosongkan jika tidak ingin mengganti poster</p>
                        </div>
                    </div>
                </div>

                {{-- Genre --}}
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-zinc-400 mb-1 ml-2">Genre</label>
                    <input type="text" name="genre" value="{{ old('genre', $movie->genre) }}" 
                           class="w-full px-4 py-3 border border-zinc-300 rounded-2xl font-bold" placeholder="Action, Drama">
                </div>

                {{-- Deskripsi --}}
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-zinc-400 mb-1 ml-2">Deskripsi</label>
                    <textarea name="description" rows="3" 
                              class="w-full px-4 py-3 border border-zinc-300 rounded-2xl font-bold" required>{{ old('description', $movie->description) }}</textarea>
                </div>

                {{-- Trailer --}}
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-zinc-400 mb-1 ml-2">YouTube Trailer ID</label>
                    <input type="text" name="trailer_url" value="{{ old('trailer_url', $movie->trailer_url) }}" 
                           class="w-full px-4 py-3 border border-zinc-300 rounded-2xl font-bold" placeholder="Contoh: dQw4w9WgXcQ">
                </div>
            </div>

            <div class="flex gap-4 pt-4">
                <button type="submit" class="flex-1 bg-red-600 text-white py-4 rounded-full font-black uppercase text-xs tracking-widest shadow-xl shadow-red-600/20 hover:bg-red-700 transition-all">
                    Update Data Film
                </button>
                <a href="{{ route('admin.movies.index') }}" class="flex-1 bg-zinc-100 text-zinc-500 py-4 rounded-full font-black uppercase text-xs tracking-widest text-center hover:bg-zinc-200 transition-all">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection