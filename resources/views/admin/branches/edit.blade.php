@extends('layouts.admin')

@section('content')
    <div class="max-w-lg mx-auto bg-white p-8 rounded-lg shadow">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Edit Cabang</h2>

        <form action="{{ route('admin.branches.update', $branch->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Nama Cabang</label>
                <input type="text" name="name" value="{{ old('name', $branch->name) }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500"
                    required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Kota</label>
                <input type="text" name="city" value="{{ old('city', $branch->city) }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500"
                    required>
            </div>

            {{-- EDIT ALAMAT --}}
            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2">Alamat Lengkap</label>
                <textarea name="address" rows="3"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500"
                    required>{{ old('address', $branch->address) }}</textarea>
            </div>
            {{-- Input Latitude --}}
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Latitude</label>
                    <input type="text" name="latitude" value="{{ old('latitude', $branch->latitude) }}"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:border-blue-500"
                        placeholder="Contoh: -6.9147">
                </div>
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Longitude</label>
                    <input type="text" name="longitude" value="{{ old('longitude', $branch->longitude) }}"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:border-blue-500"
                        placeholder="Contoh: 107.6119">
                </div>
            </div>

            <p class="text-xs text-gray-500 mb-4">* Tips: Dapatkan koordinat dengan klik kanan lokasi di Google Maps, lalu
                salin angkanya.</p>

            <div class="flex justify-end gap-2">
                <a href="{{ route('admin.branches.index') }}"
                    class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Batal</a>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update</button>
            </div>
        </form>
    </div>
@endsection