@extends('layouts.admin')

@section('content')
    <h2 class="text-3xl font-bold text-gray-800 mb-6">Dashboard</h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-lg shadow border-l-4 border-blue-500">
            <h3 class="text-gray-500 text-sm font-bold uppercase">Total Film</h3>
            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalMovies }}</p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow border-l-4 border-green-500">
            <h3 class="text-gray-500 text-sm font-bold uppercase">Pendapatan Tiket</h3>
            <p class="text-3xl font-bold text-gray-800 mt-2">Rp {{ number_format($totalEarnings) }}</p>
        </div>
    </div>
@endsection