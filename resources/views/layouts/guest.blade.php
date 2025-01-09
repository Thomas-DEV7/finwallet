<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=Inter:wght@300;400;600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script defer>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            customBlue: '#1e40af',
                            customGreen: '#16a34a',
                        },
                    },
                },
            }
        </script>
        <!-- Custom Styles -->
        <style>
            body {
                font-family: 'Inter', sans-serif;
                margin: 0;
                padding: 0;
                background-color: #121212;
                color: #E5E5E5;
            }
            .container {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                min-height: 100vh;
                padding: 1rem;
                background: linear-gradient(135deg, #1E293B, #0F172A);
            }
            .logo {
                margin-bottom: 2rem;
            }
            .logo img {
                width: 80px;
                height: auto;
            }
            .card {
                width: 100%;
                max-width: 400px;
                padding: 2rem;
                background: #1E1E1E;
                border-radius: 1rem;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
                text-align: center;
            }
            .card .header {
                font-size: 1.5rem;
                font-weight: bold;
                margin-bottom: 1rem;
                color: #FFFFFF;
            }
            .card .content {
                color: #A3A3A3;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <!-- Logo -->
            <div class="logo">
                <a href="/">
                    <img src="{{ asset('images/logo.png') }}" alt="FinWallet Logo">
                </a>
            </div>

            <!-- Card -->
            <div class="card">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
