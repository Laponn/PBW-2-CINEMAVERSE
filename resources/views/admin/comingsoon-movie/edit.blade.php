@extends('layouts.admin')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl w-full space-y-8 bg-white p-10 rounded-[2.5rem] shadow-2xl border border-zinc-200">

        <div>
            <h2 class="text-center text-3xl font-black uppercase italic tracking-tighter text-zinc-900">
                Edit <span class="text-red-600">Coming Soon</span>
            </h2>
            <p class="mt-2 text-center text-sm text-zinc-500 font-bold uppercase tracking-widest">
                Perbarui data film akan tayang
            </p>
        </div>

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-2xl font-bold">
                <ul class="list-disc ml-5">
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.comingsoon-movie.update', $movie->id) }}" method="POST" enctype="multipart/form-data" class="mt-8 space-y-6">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                {{-- Judul --}}
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-zinc-400 mb-1 ml-2">Judul Film</label>
                    <input type="text" name="title" value="{{ old('title', $movie->title) }}"
                           class="appearance-none block w-full px-4 py-3 border border-zinc-300 rounded-2xl placeholder-zinc-400 text-zinc-900 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm font-bold"
                           required>
                </div>

                {{-- Durasi & Tanggal --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-zinc-400 mb-1 ml-2">Durasi (Menit)</label>
                        <input type="number" name="duration_minutes" value="{{ old('duration_minutes', $movie->duration_minutes) }}"
                               class="appearance-none block w-full px-4 py-3 border border-zinc-300 rounded-2xl placeholder-zinc-400 text-zinc-900 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm font-bold"
                               required>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-zinc-400 mb-1 ml-2">Tanggal Tayang</label>
                        <input type="date" name="release_date"
                               value="{{ old('release_date', \Carbon\Carbon::parse($movie->release_date)->format('Y-m-d')) }}"
                               class="appearance-none block w-full px-4 py-3 border border-zinc-300 rounded-2xl text-zinc-900 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm font-bold"
                               required>
                    </div>
                </div>

                {{-- Poster lama --}}
                <div class="flex items-center gap-4">
                    @if($movie->poster_url)
                        <img src="{{ $movie->poster_url }}" alt="{{ $movie->title }}"
                             class="h-24 w-16 object-cover rounded-2xl border border-zinc-200">
                    @endif
                    <div class="text-xs font-bold text-zinc-500 uppercase tracking-widest">
                        Poster saat ini
                    </div>
                </div>

                {{-- Poster baru (optional) --}}
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-zinc-400 mb-1 ml-2">Ganti Poster (Opsional)</label>
                    <input type="file" name="poster"
                           class="block w-full px-4 py-3 border border-zinc-300 rounded-2xl bg-white text-zinc-900 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm font-bold">
                    <p class="text-xs text-zinc-400 font-bold mt-2">Kosongkan jika tidak ingin mengganti poster.</p>
                </div>

                {{-- Genre --}}
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-zinc-400 mb-1 ml-2">Genre</label>
                    <input type="text" name="genre" value="{{ old('genre', $movie->genre) }}"
                           class="appearance-none block w-full px-4 py-3 border border-zinc-300 rounded-2xl placeholder-zinc-400 text-zinc-900 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm font-bold"
                           placeholder="Action, Adventure">
                </div>

                {{-- Deskripsi --}}
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-zinc-400 mb-1 ml-2">Deskripsi</label>
                    <textarea name="description" rows="4"
                              class="appearance-none block w-full px-4 py-3 border border-zinc-300 rounded-2xl placeholder-zinc-400 text-zinc-900 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm font-bold"
                              required>{{ old('description', $movie->description) }}</textarea>
                </div>

                {{-- Trailer --}}
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-zinc-400 mb-1 ml-2">YouTube Trailer URL</label>
                    <input type="text" name="trailer_url" value="{{ old('trailer_url', $movie->trailer_url) }}"
                           class="appearance-none block w-full px-4 py-3 border border-zinc-300 rounded-2xl placeholder-zinc-400 text-zinc-900 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm font-bold"
                           placeholder="https://www.youtube.com/watch?v=...">
                </div>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('admin.comingsoon-movie.index') }}"
                   class="w-1/2 text-center py-4 px-4 text-xs font-black rounded-full border border-zinc-300 text-zinc-800 uppercase tracking-[0.2em] hover:border-zinc-900 transition">
                    Batal
                </a>

                <button type="submit"
                        class="w-1/2 group relative flex justify-center py-4 px-4 border border-transparent text-xs font-black rounded-full text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 uppercase tracking-[0.2em] shadow-xl shadow-red-600/20 transition-transform active:scale-95">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
