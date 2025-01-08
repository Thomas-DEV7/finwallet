<x-guest-layout>
    <style>
        body {
            background: linear-gradient(135deg, #1c1e26, #162131);
            /* color: #e5e5e5; */
            font-family: 'Inter', sans-serif;
        }

        .header {
            margin-bottom: 2rem;
            text-align: center;
        }

        .header h2 {
            font-size: 1.75rem;
            font-weight: 700;
            color: #ffffff;
        }

        .header p {
            color: #a3a3a3;
            font-size: 1rem;
        }

        form {
            padding: 2rem;
            border-radius: 0.75rem;
            max-width: 400px;
            margin: 0 auto;
            /* background: #20232b; */
            /* box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3); */
        }

        form label {
            font-size: 0.875rem;
            color: #c7c7c7;
            margin-bottom: 0.5rem;
            text-align: left;
        }

        input {
            /* background: #292c35; */
            color: #000000;
            border: 1px solid #3a3d47;
            border-radius: 0.375rem;
            padding: 0.75rem;
            width: 100%;
            margin-bottom: 1rem;
            transition: border 0.3s ease;
        }

        input:focus {
            border: 1px solid #3b82f6;
            outline: none;
        }

        button {
            background: #10b981;
            /* color: #ffffff; */
            font-weight: 600;
            padding: 0.75rem;
            width: 100%;
            border: none;
            border-radius: 0.375rem;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button:hover {
            background: #059669;
        }

        .link-to-login {
            text-align: center;
            margin-top: 1rem;
        }

        .link-to-login a {
            color: #3b82f6;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .link-to-login a:hover {
            color: #2563eb;
        }
    </style>

    <div class="header">
        <h2>Password Recovery</h2>
        <p>Enter your email to receive a reset link.</p>
    </div>

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <label for="email">Email Address</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus />
        <x-input-error :messages="$errors->get('email')" class="mt-2" />

        <button type="submit">Send Reset Link</button>

        <div class="link-to-login">
            <p><a href="{{ route('login') }}">Back to Login</a></p>
        </div>
    </form>
</x-guest-layout>
