<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Iniciar sesión | Zyga</title>

    <!-- PWA -->
    <meta name="theme-color" content="#0f172a">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="ZYGA">
    <link rel="manifest" href="{{ asset('build/manifest.webmanifest') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ asset('css/auth-custom.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <div class="login-page">
        <div class="login-background">
            <img src="{{ asset('images/city.jpg') }}" alt="Fondo Zyga">
        </div>

        <div class="login-overlay">
            <div class="login-container">

                <div class="login-brand">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo Zyga">
                </div>

                <div class="login-form-wrapper">
                    <form action="{{ route('login.post') }}" method="POST" class="login-form" novalidate>
                        @csrf

                        @if ($errors->has('email'))
                            <div class="alert-error">
                                {{ $errors->first('email') }}
                            </div>
                        @endif

                        <div class="form-group">
                            <label for="email">Correo electrónico</label>
                            <div class="input-group">
                                <input type="email" id="email" name="email" value="{{ old('email') }}"
                                    placeholder="Example@gmail.com" autocomplete="email" autofocus>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password">Contraseña</label>
                            <div class="input-group password-group">
                                <input type="password" id="password" name="password" placeholder="****************"
                                    autocomplete="current-password">
                                <button type="button" id="togglePassword" class="toggle-password"
                                    aria-label="Mostrar contraseña">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                            </div>

                            @error('password')
                                <small class="field-error">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="forgot-password">
                            <a href="#">Olvidaste tu contraseña?</a>
                        </div>

                        <button type="submit" class="login-btn">
                            Ingresar
                        </button>
                    </form>
                </div>

                <div class="login-footer-register">
                    <div class="footer-option">
                        <a href="#" class="footer-icon-link">
                            <img src="{{ asset('images/google.png') }}" alt="Google">
                        </a>
                    </div>

                    <div class="footer-option">
                        <a href="{{ url('/register') }}" class="register-link">
                            <span class="register-title">
                                <img src="{{ asset('images/logo.png') }}" alt="Logo Zyga" class="footer-logo">
                            </span>
                            <span class="register-subtitle">Registrarse</span>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        if (togglePassword && passwordInput) {
            togglePassword.addEventListener('click', function () {
                const icon = this.querySelector('i');
                const currentType = passwordInput.getAttribute('type');

                if (currentType === 'password') {
                    passwordInput.setAttribute('type', 'text');
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    passwordInput.setAttribute('type', 'password');
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        }
    </script>
</body>

</html>
