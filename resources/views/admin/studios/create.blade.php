@extends('layouts.admin')

@section('content')
<div class="max-w-xl mx-auto bg-white p-8 rounded-lg shadow">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Buat Jadwal Tayang</h2>

    <form action="{{ route('admin.showtimes.store') }}" method="POST">
        @csrf
        
        {{-- PILIH FILM --}}
        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Pilih Film</label>
            <select name="movie_id" class="w-full border border-gray-300 rounded px-3 py-2 bg-white focus:outline-none focus:border-blue-500" required>
                <option value="">-- Pilih Film --</option>
                @foreach($movies as $movie)
                    <option value="{{ $movie->id }}">{{ $movie->title }} ({{ $movie->duration_minutes }} Min)</option>
                @endforeach
            </select>
        </div>

        {{-- PILIH STUDIO --}}
        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Pilih Studio</label>
            {{-- Tambahkan ID 'studio-select' untuk selector JS --}}
            <select name="studio_id" id="studio-select" class="w-full border border-gray-300 rounded px-3 py-2 bg-white focus:outline-none focus:border-blue-500" required>
                <option value="" data-price="0">-- Pilih Studio --</option>
                @foreach($studios->groupBy('branch.name') as $branchName => $studioGroup)
                    <optgroup label="{{ $branchName }}">
                        @foreach($studioGroup as $studio)
                            {{-- PENTING: Tambahkan data-price="{{ $studio->base_price }}" --}}
                            <option value="{{ $studio->id }}" data-price="{{ $studio->base_price }}">
                                {{ $studio->name }} ({{ ucfirst($studio->type) }}) - Default: Rp {{ number_format($studio->base_price, 0, ',', '.') }}
                            </option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
        </div>

        {{-- WAKTU MULAI --}}
        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Waktu Mulai</label>
            <input type="datetime-local" name="start_time" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500" required>
        </div>

        {{-- HARGA TIKET --}}
        <div class="mb-6">
            <div class="flex justify-between">
                <label class="block text-gray-700 font-bold mb-2">Harga Tiket (Rp)</label>
                <span class="text-xs text-blue-600 cursor-pointer hover:underline" onclick="resetPrice()">Reset ke Harga Dasar</span>
            </div>
            
            {{-- Tambahkan ID 'price-input' --}}
            <input type="number" name="price" id="price-input" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500 font-bold text-gray-800" placeholder="0" min="0" required>
            <p class="text-xs text-gray-500 mt-1">Harga otomatis terisi sesuai harga dasar studio, namun bisa diubah (misal untuk Weekend).</p>
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('admin.showtimes.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Batal</a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan Jadwal</button>
        </div>
    </form>
</div>

{{-- SCRIPT JAVASCRIPT --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const studioSelect = document.getElementById('studio-select');
        const priceInput = document.getElementById('price-input');

        // Saat pilihan studio berubah
        studioSelect.addEventListener('change', function() {
            // Ambil element <option> yang dipilih
            const selectedOption = studioSelect.options[studioSelect.selectedIndex];
            
            // Ambil nilai data-price
            const basePrice = selectedOption.getAttribute('data-price');

            // Masukkan ke input harga
            if(basePrice) {
                priceInput.value = basePrice;
            }
        });
    });

    // Fungsi helper tombol reset kecil
    function resetPrice() {
        const studioSelect = document.getElementById('studio-select');
        const priceInput = document.getElementById('price-input');
        const selectedOption = studioSelect.options[studioSelect.selectedIndex];
        const basePrice = selectedOption.getAttribute('data-price');
        if(basePrice) priceInput.value = basePrice;
    }
</script>
@endsection