<x-guest-layout>
    <div class="min-vh-100 d-flex align-items-center justify-content-center">
        <div class="card card-elegant p-4" style="max-width: 420px; width:100%;">

            {{-- Header --}}
            <div class="text-center mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="45" height="45"
                     fill="#c62828" class="bi bi-film mb-3" viewBox="0 0 16 16">
                    <path d="M0 1a1 1 0 0 1 1-1h1v2H1v1h1v2H1v1h1v2H1v1h1v2H1a1 1 0 0 1-1-1V1zm4-1h8v2H4V0zm9 0h1a1 1 0 0 1 1 1v1h-2V0zm2 3h-2v2h2V3zm0 3h-2v2h2V6zm0 3h-2v2h2V9zm0 3h-2v2h2v-1a1 1 0 0 1-1-1zm-3 2H4v-2h8v2zM3 3V1h1v2H3zm0 3V4h1v2H3zm0 3V7h1v2H3zm0 3v-2h1v2H3z"/>
                </svg>

                <h3 class="fw-bold text-white">CINEMAVERSE</h3>
                <p class="text-secondary">Masuk ke akun Anda</p>
            </div>

            {{-- Error --}}
            @if ($errors->any())
                <div class="alert alert-danger py-2">
                    <ul class="m-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li class="small">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Form --}}
            <form method="POST" action="{{ route('login') }}">
                @csrf

                {{-- Email --}}
                <div class="mb-3">
                    <label class="form-label text-white">Email</label>
                    <input type="email"
                           class="form-control input-dark"
                           name="email"
                           required autofocus>
                </div>

                {{-- Password --}}
                <div class="mb-3">
                    <label class="form-label text-white d-flex justify-content-between">
                        <span>Password</span>
                        <a href="#" class="small text-red">Lupa password?</a>
                    </label>
                    <input type="password"
                           class="form-control input-dark"
                           name="password"
                           required>
                </div>

                {{-- Remember --}}
                <div class="form-check mb-4">
                    <input class="form-check-input bg-dark" type="checkbox" id="remember">
                    <label class="form-check-label text-white" for="remember">
                        Remember me
                    </label>
                </div>

                {{-- Tombol --}}
                <button type="submit" class="btn btn-red w-100 py-2">
                    LOGIN
                </button>

            </form>

        </div>
    </div>
</x-guest-layout>
