<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Cinemaverse Login</title>

        {{-- Bootstrap 5 --}}
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

        {{-- Font --}}
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

        <style>
            body {
                background: #0e0e0e;
                font-family: 'Inter', sans-serif;
                color: #fff;
            }

            .card-elegant {
                background: #161616;
                border: 1px solid #2a2a2a;
                border-radius: 10px;
                box-shadow: 0 6px 18px rgba(0,0,0,0.4);
            }

            .input-dark {
                background-color: #1f1f1f !important;
                border: 1px solid #333 !important;
                color: #fff !important;
            }

            .input-dark:focus {
                border-color: #c62828 !important;
                box-shadow: none;
            }

            .btn-red {
                background-color: #c62828;
                border: none;
                color: white;
                font-weight: 600;
                transition: 0.25s;
            }

            .btn-red:hover {
                background-color: #b71c1c;
            }

            .text-red {
                color: #c62828 !important;
            }

        </style>
    </head>

    <body>
        {{ $slot }}

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
