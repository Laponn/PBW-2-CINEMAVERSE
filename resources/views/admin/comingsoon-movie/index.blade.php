@extends('layouts.admin')

@section('content')
<div class="p-6 space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black uppercase italic tracking-tighter text-zinc-900">
                Coming <span class="text-red-600">Soon</span>
            </h1>
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-zinc-500 mt-1">
                Kelola film yang akan tayang (khusus admin)
            </p>
        </div>

        <a href="{{ route('admin.comingsoon-movie.create') }}"
           class="inline-flex items-center px-5 py-3 rounded-full bg-red-600 text-white text-xs font-black uppercase tracking-[0.2em] hover:bg-red-700 transition shadow-lg shadow-red-600/20">
            + Tambah Coming Soon
        </a>
    </div>

    {{-- Flash message --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-5 py-4 rounded-2xl font-bold">
            {{ session('success') }}
        </div>
    @endif

    {{-- Table --}}
    <div class="bg-white rounded-[2rem] shadow-xl border border-zinc-200 overflow-hidden">
        <table class="min-w-full divide-y divide-zinc-200">
            <thead class="bg-zinc-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-widest text-zinc-500">Poster</th>
                    <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-widest text-zinc-500">Judul</th>
                    <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-widest text-zinc-500">Tayang</th>
                    <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-widest text-zinc-500">Trailer</th>
                    <th class="px-6 py-4 text-right text-xs font-black uppercase tracking-widest text-zinc-500">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-zinc-100">
                @forelse($movies as $movie)
                    <tr class="hover:bg-zinc-50">
                        {{-- Poster --}}
                        <td class="px-6 py-4">
                            @if($movie->poster_url)
                                <img src="{{ $movie->poster_url }}"
                                     alt="{{ $movie->title }}"
                                     class="h-20 w-14 object-cover rounded-xl border border-zinc-200">
                            @else
                                <span class="text-xs text-zinc-400 italic">No poster</span>
                            @endif
                        </td>

                        {{-- Title + desc --}}
                        <td class="px-6 py-4">
                            <div class="font-black text-zinc-900">{{ $movie->title }}</div>
                            <div class="text-xs text-zinc-500 font-bold mt-1">
                                {{ \Illuminate\Support\Str::limit($movie->description, 70) }}
                            </div>
                            @if($movie->genre)
                                <div class="mt-2 inline-flex items-center px-3 py-1 rounded-full bg-zinc-100 text-zinc-700 text-[10px] font-black uppercase tracking-widest">
                                    {{ $movie->genre }}
                                </div>
                            @endif
                        </td>

                        {{-- Release date --}}
                        <td class="px-6 py-4 text-sm font-bold text-zinc-700">
                            {{ \Carbon\Carbon::parse($movie->release_date)->format('d M Y') }}
                        </td>

                        {{-- Trailer --}}
                        <td class="px-6 py-4">
                            @if($movie->trailer_url)
                                <a href="{{ $movie->trailer_url }}" target="_blank"
                                   class="inline-flex items-center gap-2 px-3 py-2 rounded-full border border-zinc-300 text-xs font-black uppercase tracking-[0.15em] hover:border-red-500 hover:text-red-600 transition">
                                    ▶ Trailer
                                </a>
                            @else
                                <span class="text-xs text-zinc-400 italic">-</span>
                            @endif
                        </td>

                        {{-- Actions --}}
                        <td class="px-6 py-4 text-right whitespace-nowrap space-x-2">
                            <a href="{{ route('admin.comingsoon-movie.edit', $movie->id) }}"
                               class="inline-flex items-center px-3 py-2 rounded-full bg-zinc-900 text-white text-xs font-black uppercase tracking-[0.15em] hover:bg-zinc-800 transition">
                                Edit
                            </a>

                            <form action="{{ route('admin.comingsoon-movie.destroy', $movie->id) }}"
                                  method="POST"
                                  class="inline-block"
                                  onsubmit="return confirm('Hapus film coming soon ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="inline-flex items-center px-3 py-2 rounded-full bg-red-600 text-white text-xs font-black uppercase tracking-[0.15em] hover:bg-red-700 transition">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-sm font-bold text-zinc-500">
                            Belum ada film coming soon.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
