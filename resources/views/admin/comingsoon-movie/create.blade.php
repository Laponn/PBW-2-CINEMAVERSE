@extends('layouts.admin')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl w-full space-y-8 bg-white p-10 rounded-[2.5rem] shadow-2xl border border-zinc-200">

        <div>
            <h2 class="text-center text-3xl font-black uppercase italic tracking-tighter text-zinc-900">
                Tambah <span class="text-red-600">Coming Soon</span>
            </h2>
            <p class="mt-2 text-center text-sm text-zinc-500 font-bold uppercase tracking-widest">
                Masukkan data film akan tayang + trailer
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

        <form action="{{ route('admin.comingsoon-movie.store') }}" method="POST" enctype="multipart/form-data" class="mt-8 space-y-6">
            @csrf

            <div class="space-y-4">
                {{-- Judul --}}
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-zinc-400 mb-1 ml-2">Judul Film</label>
                    <input type="text" name="title" value="{{ old('title') }}"
                           class="appearance-none block w-full px-4 py-3 border border-zinc-300 rounded-2xl placeholder-zinc-400 text-zinc-900 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm font-bold"
                           placeholder="Contoh: Dune Part Two" required>
                </div>

                {{-- Durasi & Tanggal --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-zinc-400 mb-1 ml-2">Durasi (Menit)</label>
                        <input type="number" name="duration_minutes" value="{{ old('duration_minutes') }}"
                               class="appearance-none block w-full px-4 py-3 border border-zinc-300 rounded-2xl placeholder-zinc-400 text-zinc-900 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm font-bold"
                               placeholder="148" required>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-zinc-400 mb-1 ml-2">Tanggal Tayang</label>
                        <input type="date" name="release_date" value="{{ old('release_date') }}"
                               class="appearance-none block w-full px-4 py-3 border border-zinc-300 rounded-2xl text-zinc-900 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm font-bold"
                               required>
                    </div>
                </div>

                {{-- Poster --}}
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-zinc-400 mb-1 ml-2">Poster Film</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-zinc-300 border-dashed rounded-[2rem] hover:border-red-500 transition-colors">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-zinc-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-zinc-600 justify-center">
                                <label class="relative cursor-pointer bg-white rounded-md font-black text-red-600 hover:text-red-500 focus-within:outline-none">
                                    <span>Upload file</span>
                                    <input name="poster" type="file" class="sr-only" required>
                                </label>
                            </div>
                            <p class="text-xs text-zinc-400 font-bold">JPG/PNG/WEBP maks 2MB</p>
                        </div>
                    </div>
                </div>

                {{-- Genre --}}
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-zinc-400 mb-1 ml-2">Genre</label>
                    <input type="text" name="genre" value="{{ old('genre') }}"
                           class="appearance-none block w-full px-4 py-3 border border-zinc-300 rounded-2xl placeholder-zinc-400 text-zinc-900 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm font-bold"
                           placeholder="Action, Adventure">
                </div>

                {{-- Deskripsi --}}
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-zinc-400 mb-1 ml-2">Deskripsi</label>
                    <textarea name="description" rows="4"
                              class="appearance-none block w-full px-4 py-3 border border-zinc-300 rounded-2xl placeholder-zinc-400 text-zinc-900 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm font-bold"
                              placeholder="Tulis deskripsi singkat / sinopsis..." required>{{ old('description') }}</textarea>
                </div>

                {{-- Trailer --}}
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-zinc-400 mb-1 ml-2">YouTube Trailer URL</label>
                    <input type="text" name="trailer_url" value="{{ old('trailer_url') }}"
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
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
