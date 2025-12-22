@extends('layouts.admin')

@section('content')
<div class="max-w-xl mx-auto bg-white p-8 rounded-lg shadow">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Tambah Studio Baru</h2>

    <form action="{{ route('admin.studios.store') }}" method="POST">
        @csrf
        
        {{-- PILIH CABANG --}}
        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Cabang (Branch)</label>
            <select name="branch_id" class="w-full border border-gray-300 rounded px-3 py-2 bg-white" required>
                <option value="">-- Pilih Cabang --</option>
                @foreach($branches as $branch)
                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- NAMA STUDIO --}}
        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Nama Studio</label>
            <input type="text" name="name" class="w-full border border-gray-300 rounded px-3 py-2" placeholder="Contoh: Studio 1" required>
        </div>

        {{-- TIPE STUDIO --}}
        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Tipe</label>
            <select name="type" class="w-full border border-gray-300 rounded px-3 py-2 bg-white" required>
                <option value="regular">Regular</option>
                <option value="vip">VIP</option>
                <option value="imax">IMAX</option>
            </select>
        </div>

        {{-- HARGA DASAR --}}
        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Harga Dasar (Base Price)</label>
            <input type="number" name="base_price" class="w-full border border-gray-300 rounded px-3 py-2" placeholder="Contoh: 35000" required>
        </div>

        {{-- KAPASITAS --}}
        <div class="mb-6">
            <label class="block text-gray-700 font-bold mb-2">Kapasitas Kursi</label>
            <input type="number" name="capacity" class="w-full border border-gray-300 rounded px-3 py-2" placeholder="Contoh: 50" required>
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('admin.studios.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Batal</a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan Studio</button>
        </div>
    </form>
</div>
@endsection