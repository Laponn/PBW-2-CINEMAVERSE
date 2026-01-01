@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-8 rounded-lg shadow">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Tambah Cabang Baru</h2>

    <form action="{{ route('admin.branches.store') }}" method="POST">
        @csrf

        {{-- NAMA CABANG --}}
        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Nama Cabang</label>
            <input type="text" name="name"
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500"
                placeholder="Contoh: CinemaVerse Depok" required>
        </div>

        {{-- KOTA --}}
        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Kota</label>
            <input type="text" id="city" name="city"
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500"
                placeholder="Contoh: Depok" required>
        </div>

        {{-- ALAMAT --}}
        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Alamat Lengkap</label>
            <textarea id="address" name="address" rows="3"
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500"
                placeholder="Klik lokasi pada peta untuk mengisi otomatis" required></textarea>
        </div>

        {{-- KOORDINAT --}}
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-gray-700 font-bold mb-2">Latitude</label>
                <input type="text" id="latitude" name="latitude"
                    class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-100"
                    readonly required>
            </div>
            <div>
                <label class="block text-gray-700 font-bold mb-2">Longitude</label>
                <input type="text" id="longitude" name="longitude"
                    class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-100"
                    readonly required>
            </div>
        </div>

        <p class="text-xs text-gray-500 mb-3">
            * Ketik kota → peta otomatis berpindah. Klik peta untuk menentukan lokasi cabang.
        </p>

        {{-- MAP --}}
        <div id="map" class="w-full h-96 rounded border mb-6"></div>

        {{-- BUTTON --}}
        <div class="flex justify-end gap-2">
            <a href="{{ route('admin.branches.index') }}"
                class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                Batal
            </a>
            <button type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Simpan Cabang
            </button>
        </div>
    </form>
</div>

{{-- LEAFLET CSS & JS --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    // ===============================
    // INISIALISASI MAP
    // ===============================
    let map = L.map('map').setView([-6.200000, 106.816666], 12); // default Jakarta
    let marker;

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
    }).addTo(map);

    // ===============================
    // FORWARD GEOCODING (KOTA → MAP)
    // ===============================
    const cityInput = document.getElementById('city');

    cityInput.addEventListener('blur', function () {
        const city = cityInput.value;
        if (!city) return;

        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${city}`)
            .then(res => res.json())
            .then(data => {
                if (data.length > 0) {
                    const lat = data[0].lat;
                    const lon = data[0].lon;

                    map.setView([lat, lon], 13);
                }
            });
    });

    // ===============================
    // KLIK MAP → ISI KOORDINAT & ALAMAT
    // ===============================
    map.on('click', function (e) {
        const lat = e.latlng.lat;
        const lng = e.latlng.lng;

        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;

        if (marker) {
            marker.setLatLng(e.latlng);
        } else {
            marker = L.marker(e.latlng).addTo(map);
        }

        // Reverse Geocoding
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
            .then(res => res.json())
            .then(data => {
                if (data.display_name) {
                    document.getElementById('address').value = data.display_name;
                }
            });
    });
</script>
@endsection
