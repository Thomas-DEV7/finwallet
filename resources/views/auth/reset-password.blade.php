<x-guest-layout>
    <style>
        body {
            background: linear-gradient(135deg, #1c1e26, #162131);
            color: #e5e5e5;
            font-family: 'Inter', sans-serif;
        }

        form {
            padding: 2rem;
            border-radius: 0.75rem;
            max-width: 400px;
            margin: 2rem auto;
            /* background: #20232b; */
            /* box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3); */
        }

        label {
            text-align: left;
            color: #c7c7c7;
            font-size: 0.875rem;
            display: block;
            margin-bottom: 0.5rem;
        }

        input {
            background: #292c35;
            color: #070707;
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
            color: #ffffff;
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
    </style>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required
                autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <!-- Password -->
        <div>
            <label for="password">Password</label>
            <input id="password" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required
                autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" />
        </div>

        <div class="mt-4">
            <button type="submit">Reset Password</button>
        </div>
    </form>
</x-guest-layout>
