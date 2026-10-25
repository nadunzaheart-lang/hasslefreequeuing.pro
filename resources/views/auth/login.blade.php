<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Registrar Queuing System</title>
    
    <!-- Keep Breeze's Tailwind for the form elements -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        body {
            background-color: #f3f4f6;
            font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
        }
        .login-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            padding: 40px;
            width: 100%;
            max-width: 420px;
        }
        .brand-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .brand-header img {
            height: 80px;
            margin: 0 auto 15px auto;
        }
        .brand-header h1 {
            font-size: 22px;
            font-weight: 800;
            color: #1e3a8a; /* Deep Registrar Blue */
            margin: 0;
        }
        .brand-header p {
            font-size: 13px;
            color: #6b7280;
            margin-top: 5px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }
    </style>
</head>
<body class="antialiased flex items-center justify-center min-h-screen">

    <div class="login-card">
        
        <!-- BRANDING -->
        <div class="brand-header">
            <img src="{{ asset('images/logo.png') }}" alt="TCC Logo">
            <h1>TAGOLOAN COMMUNITY COLLEGE</h1>
            <p>REGISTRAR QUEUING SYSTEM</p>
        </div>

        <!-- SESSION STATUS -->
        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <!-- LOGIN FORM -->
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div>
                <label for="email" class="block font-medium text-sm text-gray-700">Email</label>
                <input id="email" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                @error('email')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <!-- Password -->
            <div class="mt-4">
                <label for="password" class="block font-medium text-sm text-gray-700">Password</label>
                <input id="password" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" type="password" name="password" required autocomplete="current-password" />
                @error('password')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                    <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif
            </div>

            <!-- Submit Button -->
            <div class="mt-6">
                <button type="submit" style="background-color: #1e3a8a; color: white;" class="w-full justify-center py-2 px-4 border border-transparent rounded-md font-semibold text-sm uppercase tracking-widest hover:bg-blue-900 focus:bg-blue-900 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Log in') }}
                </button>
            </div>
        </form>
    </div>

</body>
</html>