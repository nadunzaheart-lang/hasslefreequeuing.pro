<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#4e0a10">
    <title>{{ __('Login') }} - Registrar Queuing System</title>

    <!-- FAVICON (public/favicon.ico in the project is an empty file, so we use the logo) -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo-small.png') }}">

    <!-- PAGE STYLES (no Tailwind CDN needed anymore) -->
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">

    <!-- PAGE SCRIPT (show/hide password, Caps Lock hint, loading button) -->
    <script src="{{ asset('js/login.js') }}" defer></script>
</head>
<body>

    <main class="login-page">

        <div class="login-card">

            <!-- BRANDING -->
            <header class="brand-header">
                <!-- 80x80 display size, 160x160 file (sharp on high-DPI screens, ~58 KB instead of ~440 KB) -->
                <img src="{{ asset('images/logo-small.png') }}" alt="Tagoloan Community College logo" width="80" height="80">
                <h1>TAGOLOAN COMMUNITY COLLEGE</h1>
                <p>REGISTRAR QUEUING SYSTEM</p>
            </header>

            <div class="welcome">
                <h2>{{ __('Welcome') }}</h2>
                <p>{{ __('TCC REGISTRAR QUEUING SYSTEM.') }}</p>
            </div>

            <!-- SESSION STATUS -->
            @if (session('status'))
                <div class="status-message" role="status">
                    {{ session('status') }}
                </div>
            @endif

            <!-- LOGIN FORM -->
            @php
                /*
                 * The backend reports every failed login as ONE generic message on
                 * "email" (it cannot say which field was wrong). Here we decide which
                 * field the message belongs to, so each error shows under its own input:
                 *   - email not registered  -> error under Email
                 *   - email found, wrong pw -> error under Password
                 * Any other message (empty field, bad format, too many attempts)
                 * stays exactly where the backend put it.
                 */
                $emailError    = $errors->first('email');
                $passwordError = $errors->first('password');

                if ($emailError !== '' && $emailError === trans('auth.failed')) {
                    $emailExists = \App\Models\User::where('email', old('email'))->exists();

                    if ($emailExists) {
                        $emailError    = '';
                        $passwordError = __('Incorrect password. Please try again.');
                    } else {
                        $emailError = __('No account found with this email address.');
                    }
                }
            @endphp

            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf

                <!-- Email Address -->
                <div class="field {{ $emailError ? 'has-error' : '' }}">
                    <label for="email">{{ __('Email') }}</label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        @if (! $passwordError || $emailError) autofocus @endif
                        autocomplete="username"
                        @if ($emailError) aria-invalid="true" aria-describedby="email-error" @endif
                    >
                    @if ($emailError)
                        <p class="field-error" id="email-error" role="alert">{{ $emailError }}</p>
                    @endif
                </div>

                <!-- Password -->
                <div class="field {{ $passwordError ? 'has-error' : '' }}">
                    <label for="password">{{ __('Password') }}</label>

                    <div class="password-wrap">
                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            @if ($passwordError && ! $emailError) autofocus @endif
                            autocomplete="current-password"
                            @if ($passwordError) aria-invalid="true" aria-describedby="password-error" @endif
                        >

                        <button
                            type="button"
                            class="toggle-password"
                            id="togglePassword"
                            aria-label="{{ __('Show password') }}"
                            aria-controls="password"
                            aria-pressed="false"
                        >
                            <!-- eye icon (shown while password is hidden) -->
                            <svg class="icon-eye" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                            <!-- eye-off icon (shown while password is visible) -->
                            <svg class="icon-eye-off" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M17.94 17.94A10.94 10.94 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"></path>
                                <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"></path>
                                <path d="M14.12 14.12a3 3 0 1 1-4.24-4.24"></path>
                                <line x1="1" y1="1" x2="23" y2="23"></line>
                            </svg>
                        </button>
                    </div>

                    <!-- Caps Lock hint (hidden until Caps Lock is detected) -->
                    <p class="caps-hint" id="capsHint" role="status" hidden>
                        {{ __('Caps Lock is on') }}
                    </p>

                    @if ($passwordError)
                        <p class="field-error" id="password-error" role="alert">{{ $passwordError }}</p>
                    @endif
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-login" id="loginButton">
                    <span class="spinner" aria-hidden="true"></span>
                    <span class="btn-text">{{ __('Log in') }}</span>
                </button>
            </form>

            <!-- HELP -->
            <p class="help-text">
                {{ __('Forgot your password? Please approach your system administrator.') }}
            </p>
        </div>

        <footer class="login-footer">
            &copy; {{ date('Y') }} Tagoloan Community College &middot; Registrar's Office
        </footer>

    </main>

</body>
</html>
