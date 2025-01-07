<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome to FinWallet</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #121212;
            color: #E5E5E5;
        }
        .hero {
            background: linear-gradient(135deg, #1E293B, #0F172A);
            color: #FFFFFF;
            text-align: center;
            padding: 4rem 2rem;
            border-bottom-left-radius: 2rem;
            border-bottom-right-radius: 2rem;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
        }
        .hero img {
            width: 120px;
            margin: 0 auto 1.5rem auto;
            display: block;
        }
        .hero h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        .hero p {
            font-size: 1.25rem;
            color: #A3A3A3;
            margin-top: 0.5rem;
        }
        .features {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 2rem;
            padding: 3rem 2rem;
        }
        .feature-card {
            background: #1E1E1E;
            border-radius: 1rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            padding: 2rem;
            text-align: center;
            flex: 1;
            max-width: 300px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.5);
        }
        .feature-card img {
            width: 60px;
            margin: 0 auto 1rem auto;
            display: block;
        }
        .feature-card h3 {
            font-size: 1.5rem;
            color: #3B82F6;
            margin-bottom: 0.5rem;
        }
        .feature-card p {
            font-size: 1rem;
            color: #A3A3A3;
        }
        .cta {
            text-align: center;
            margin: 3rem 0;
        }
        .cta a {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            margin: 0.5rem;
            font-size: 1.25rem;
            font-weight: 500;
            border-radius: 0.5rem;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        .cta a.login {
            background: #3B82F6;
            color: #FFFFFF;
        }
        .cta a.login:hover {
            background: #2563EB;
        }
        .cta a.signup {
            background: #10B981;
            color: #FFFFFF;
        }
        .cta a.signup:hover {
            background: #059669;
        }
        footer {
            text-align: center;
            padding: 1rem 0;
            background: #1E1E1E;
            font-size: 0.875rem;
            color: #A3A3A3;
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <div class="hero">
        <img src="{{ asset('images/logo.png') }}" alt="FinWallet Logo">
        <h1>Welcome to FinWallet</h1>
        <p>The Secure and Modern Way to Manage Your Money</p>
    </div>

    <!-- Features Section -->
    <div class="features">
        <div class="feature-card">
            <img src="https://img.icons8.com/ios-filled/100/3b82f6/money.png" alt="Fast Transactions">
            <h3>Instant Transfers</h3>
            <p>Quick and seamless money transfers, anytime, anywhere.</p>
        </div>
        <div class="feature-card">
            <img src="https://img.icons8.com/ios-filled/100/3b82f6/security-checked.png" alt="Secure">
            <h3>Bank-Level Security</h3>
            <p>Advanced encryption to protect your funds and data.</p>
        </div>
        <div class="feature-card">
            <img src="https://img.icons8.com/ios-filled/100/3b82f6/smartphone.png" alt="User-Friendly">
            <h3>Mobile Optimized</h3>
            <p>Access and manage your account from any device.</p>
        </div>
    </div>

    <!-- Call-to-Action Section -->
    <div class="cta">
        @if (Route::has('login'))
            @auth
                <a href="{{ url('/dashboard') }}" class="login">Go to Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="login">Log In</a>
                <a href="{{ route('register') }}" class="signup">Sign Up</a>
            @endauth
        @endif
    </div>

    <!-- Footer Section -->
    <footer class="">
        &copy; {{ date('Y') }} FinWallet. Designed for Financial Freedom. All Rights Reserved.
    </footer>
</body>
</html>
