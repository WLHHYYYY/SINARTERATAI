<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="flex justify-center items-center h-screen">
        <!-- Login Card -->
        <div class="bg-white shadow-lg rounded-lg w-full max-w-sm p-8">
            <h2 class="text-2xl font-bold text-center text-gray-700 mb-6">Welcome Back</h2>
            <p class="text-center text-gray-500 mb-6">Please log in to your account</p>

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Field -->
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-medium mb-1">Email</label>
                    <input id="email" type="email" name="email" :value="old('email')" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Field -->
                <div class="mb-6">
                    <label for="password" class="block text-gray-700 font-medium mb-1">Password</label>
                    <input id="password" type="password" name="password" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400">
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me Checkbox -->
                <div class="flex items-center justify-between mb-6">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="remember"
                            class="text-blue-500 border-gray-300 rounded focus:ring-blue-400 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-600">Remember Me</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm text-blue-500 hover:underline">Forgot Password?</a>
                    @endif
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit"
                        class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-50">
                        Log in
                    </button>
                </div>
            </form>

            <!-- Separator -->
            {{-- <div class="flex items-center justify-center mt-6">
                <span class="h-px w-full bg-gray-300"></span>
                <span class="px-4 text-gray-500">or</span>
                <span class="h-px w-full bg-gray-300"></span>
            </div> --}}

            <!-- Register Link -->
            {{-- @if (Route::has('register'))
                <p class="text-center text-sm text-gray-600 mt-6">
                    Don't have an account? <a href="{{ route('register') }}"
                        class="text-blue-500 hover:underline">Sign up</a>
                </p>
            @endif --}}
        </div>
    </div>
</body>

</html>
