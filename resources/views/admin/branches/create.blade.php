@extends('layouts.admin')

@section('content')
    <div class="max-w-lg mx-auto bg-white p-8 rounded-lg shadow">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Tambah Cabang Baru</h2>

        <form action="{{ route('admin.branches.store') }}" method="POST">
            @csrf

            {{-- INPUT NAMA --}}
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Nama Cabang</label>
                <input type="text" name="name"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500"
                    placeholder="Contoh: CinemaVerse Jakarta Pusat" required>
            </div>

            {{-- INPUT KOTA --}}
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Kota</label>
                <input type="text" name="city"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500"
                    placeholder="Contoh: Jakarta" required>
            </div>

            {{-- INPUT ALAMAT (BARU) --}}
            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2">Alamat Lengkap</label>
                <textarea name="address" rows="3"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500"
                    placeholder="Contoh: Jl. MH Thamrin No. 1" required></textarea>
            </div>
            <div class="mt-4">
                <label>Latitude</label>
                <input type="text" name="latitude" class="..." placeholder="Contoh: -6.914744">
            </div>
            <div class="mt-4">
                <label>Longitude</label>
                <input type="text" name="longitude" class="..." placeholder="Contoh: 107.611940">
            </div>
            <p class="text-xs text-gray-500 mb-4">* Tips: Dapatkan koordinat dengan klik kanan lokasi di Google Maps, lalu
                salin angkanya.</p>

            <div class="flex justify-end gap-2">
                <a href="{{ route('admin.branches.index') }}"
                    class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Batal</a>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
            </div>
        </form>
    </div>
@endsection