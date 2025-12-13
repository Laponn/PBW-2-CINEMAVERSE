@extends('layouts.admin')

@section('content')
<div class="max-w-lg mx-auto bg-white p-8 rounded-lg shadow">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Edit Studio</h2>

    <form action="{{ route('admin.studios.update', $studio->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Pilih Cabang</label>
            <select name="branch_id" class="w-full border border-gray-300 rounded px-3 py-2 bg-white focus:outline-none focus:border-blue-500" required>
                @foreach($branches as $branch)
                    <option value="{{ $branch->id }}" {{ $studio->branch_id == $branch->id ? 'selected' : '' }}>
                        {{ $branch->name }} - {{ $branch->city }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Nama Studio</label>
            <input type="text" name="name" value="{{ old('name', $studio->name) }}" class="w-full border border-gray-300 rounded px-3 py-2" required>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-gray-700 font-bold mb-2">Tipe Studio</label>
                <select name="type" class="w-full border border-gray-300 rounded px-3 py-2 bg-white">
                    <option value="regular" {{ $studio->type == 'regular' ? 'selected' : '' }}>Regular</option>
                    <option value="vip" {{ $studio->type == 'vip' ? 'selected' : '' }}>VIP</option>
                    <option value="imax" {{ $studio->type == 'imax' ? 'selected' : '' }}>IMAX</option>
                </select>
            </div>
            <div>
                <label class="block text-gray-700 font-bold mb-2">Harga Dasar (Rp)</label>
                <input type="number" name="base_price" value="{{ old('base_price', $studio->base_price) }}" class="w-full border border-gray-300 rounded px-3 py-2" required>
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 font-bold mb-2">Kapasitas</label>
            <input type="number" name="capacity" value="{{ old('capacity', $studio->capacity) }}" class="w-full border border-gray-300 rounded px-3 py-2" min="1" required>
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('admin.studios.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Batal</a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update Studio</button>
        </div>
    </form>
</div>
@endsection