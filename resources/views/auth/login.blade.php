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
        <div class="auth-container">
            <section class="auth-showcase">
                <div>
                    <div class="auth-brand-badge">ZYGA</div>
                    <p class="auth-kicker">Acceso seguro</p>

                    <div class="auth-showcase-body">
                        <h1>Control, respaldo y atención inmediata desde una sola experiencia.</h1>
                        <p class="auth-copy">
                            Inicia sesión para gestionar tu vehículo, solicitar apoyo y dar seguimiento a cada servicio
                            desde una interfaz clara, elegante y confiable.
                        </p>

                        <div class="auth-points">
                            <span>Atención prioritaria</span>
                            <span>Seguimiento en tiempo real</span>
                            <span>Operación centralizada</span>
                        </div>
                    </div>
                </div>

                <div class="auth-showcase-footer">
                    <div class="auth-mini-card">
                        <strong>Cliente</strong>
                        <small>Solicita asistencia, consulta estado y administra tu cuenta.</small>
                    </div>
                    <div class="auth-mini-card">
                        <strong>Proveedor</strong>
                        <small>Opera solicitudes, disponibilidad y atención desde un solo panel.</small>
                    </div>
                </div>
            </section>

            <section class="auth-card-wrap">
                <div class="auth-card">
                    <div class="auth-header">
                        <p class="auth-eyebrow">Acceso seguro</p>
                        <h2>Bienvenido a ZYGA</h2>
                        <p class="auth-subcopy">
                            Inicia sesión para continuar con tu flujo de cliente o proveedor.
                        </p>
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
                            <input
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                placeholder="cliente@zyga.com"
                                autocomplete="email"
                                required
                            >
                        </label>

                        <label class="auth-field">
                            <span>Contraseña</span>
                            <input
                                type="password"
                                name="password"
                                placeholder="********"
                                autocomplete="current-password"
                                required
                            >
                        </label>

                        <button type="submit" class="auth-button">Ingresar</button>
                    </form>

                    <div class="auth-links">
                        <a href="{{ route('register', ['role' => 'client']) }}">Crear cuenta de cliente</a>
                        <a href="{{ route('register', ['role' => 'provider']) }}">Crear cuenta de proveedor</a>
                    </div>

                    <p class="auth-footer-note">
                        Accede desde escritorio o móvil con una experiencia adaptada a ambos formatos.
                    </p>
                </div>
            </section>
        </div>
    </main>

    @include('partials.pwa-register')
</body>
</html>