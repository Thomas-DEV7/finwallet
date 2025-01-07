<x-guest-layout>
    <div class="header text-center">
        <h2 class="text-2xl font-semibold text-white mb-6">Welcome Back to FinWallet</h2>
        <p class="text-gray-400 mb-4">Manage your finances effortlessly</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="text-left">
        @csrf

        <!-- Email Address -->
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-400 mb-1">Email</label>
            <input
                id="email"
                type="email"
                name="email"
                class="w-full px-4 py-2 rounded-md bg-gray-800 text-white border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
                value="{{ old('email') }}"
                required
                autofocus
            />
            @error('email')
                <span class="text-sm text-red-500 mt-1">{{ $message }}</span>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-400 mb-1">Password</label>
            <input
                id="password"
                type="password"
                name="password"
                class="w-full px-4 py-2 rounded-md bg-gray-800 text-white border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
                required
            />
            @error('password')
                <span class="text-sm text-red-500 mt-1">{{ $message }}</span>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between mb-4">
            <label for="remember_me" class="inline-flex items-center text-sm text-gray-400">
                <input
                    id="remember_me"
                    type="checkbox"
                    class="form-checkbox rounded text-blue-500 focus:ring-blue-500 bg-gray-800 border-gray-600"
                    name="remember"
                />
                <span class="ml-2">Remember me</span>
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm text-blue-400 hover:underline">
                    Forgot your password?
                </a>
            @endif
        </div>

        <!-- Submit Button -->
        <div class="mt-6">
            <button
                type="submit"
                class="w-full px-4 py-2 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                Log In
            </button>
        </div>
    </form>

    <!-- Register Link -->
    <div class="text-center mt-6">
        <p class="text-sm text-gray-400">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-blue-400 hover:underline">Sign up</a>
        </p>
    </div>
</x-guest-layout>
