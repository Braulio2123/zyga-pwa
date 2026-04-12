<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    @include('partials.pwa-meta')
    <title>Ingresar | ZYGA</title>
    <link rel="stylesheet" href="{{ asset('css/auth-client.css') }}">
</head>
<body class="auth-body">
    <main class="auth-shell">
        <section class="auth-panel auth-panel--brand">
            <div class="auth-brand-badge">ZYGA</div>
            <p class="auth-kicker">Asistencia vial premium</p>
            <h1>Control, respaldo y atención inmediata desde una sola experiencia.</h1>
            <p class="auth-copy">
                Inicia sesión para gestionar tu vehículo, solicitar apoyo y dar seguimiento a cada servicio con una interfaz clara, elegante y confiable.
            </p>
            <div class="auth-points">
                <span>Atención prioritaria</span>
                <span>Seguimiento en tiempo real</span>
                <span>Operación centralizada</span>
            </div>
        </section>

        <section class="auth-panel auth-panel--form">
            <div class="auth-card">
                <div class="auth-header">
                    <p class="auth-eyebrow">Acceso seguro</p>
                    <h2>Bienvenido a ZYGA</h2>
                </div>

                @if (session('success'))
                    <div class="auth-alert auth-alert--success">{{ session('success') }}</div>
                @endif

                @if ($errors->any())
                    <div class="auth-alert auth-alert--error">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login.post') }}" class="auth-form" novalidate>
                    @csrf

                    <label class="auth-field">
                        <span>Correo electrónico</span>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="cliente@zyga.com" autocomplete="email" required>
                    </label>

                    <label class="auth-field">
                        <span>Contraseña</span>
                        <input type="password" name="password" placeholder="********" autocomplete="current-password" required>
                    </label>

                    <button type="submit" class="auth-button">Ingresar</button>
                </form>

                <div class="auth-links">
                    <a href="{{ route('register', ['role' => 'client']) }}">Crear cuenta de cliente</a>
                    <a href="{{ route('register', ['role' => 'provider']) }}">Crear cuenta de proveedor</a>
                </div>
            </div>
        </section>
    </main>
    @include('partials.pwa-register')
</body>
</html>
