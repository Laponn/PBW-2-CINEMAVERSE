<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - CinemaVerse</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans flex min-h-screen">

    <aside class="w-64 bg-gray-900 text-white flex-shrink-0 fixed h-full z-20">
        <div class="p-6 text-2xl font-bold text-red-500 border-b border-gray-800 tracking-wider">
            CinemaVerse
        </div>
        <nav class="flex-1 p-4 space-y-2">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 rounded transition hover:bg-gray-800 {{ request()->routeIs('admin.dashboard') ? 'bg-red-600 text-white' : 'text-gray-300' }}">
                Dashboard
            </a>
            
            <a href="{{ route('admin.movies.index') }}" class="flex items-center px-4 py-3 rounded transition hover:bg-gray-800 {{ request()->routeIs('admin.movies.*') ? 'bg-red-600 text-white' : 'text-gray-300' }}">
                Data Film
            </a>

            <a href="#" class="flex items-center px-4 py-3 rounded transition hover:bg-gray-800 text-gray-500 cursor-not-allowed">
                Studio (Coming Soon)
            </a>
        </nav>

        <div class="p-4 border-t border-gray-800 absolute bottom-0 w-full">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full text-left px-4 py-2 text-gray-400 hover:text-white transition">
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 ml-64 p-8 bg-gray-100 min-h-screen">
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm" role="alert">
                <p class="font-bold">Berhasil!</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @yield('content')
    </main>

</body>
</html>