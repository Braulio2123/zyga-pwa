<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    @include('partials.pwa-meta')
    <title>Registro | ZYGA</title>
    <link rel="stylesheet" href="{{ asset('css/auth-client.css') }}">
</head>
<body class="auth-body">
    <main class="auth-shell">
        <div class="auth-container">
            <section class="auth-showcase">
                <div>
                    <a href="{{ route('login') }}" class="auth-back">← Volver al acceso</a>
                    <div class="auth-brand-badge">ZYGA</div>
                    <p class="auth-kicker">Alta de cuenta</p>

                    <div class="auth-showcase-body">
                        <h1>Regístrate y entra al flujo principal con una experiencia clara y profesional.</h1>
                        <p class="auth-copy">
                            Crea tu cuenta para continuar en el portal cliente o iniciar tu preparación como proveedor dentro de ZYGA.
                        </p>

                        <div class="auth-points">
                            <span>Registro directo</span>
                            <span>Flujo guiado</span>
                            <span>Base segura</span>
                        </div>
                    </div>
                </div>

                <div class="auth-showcase-footer">
                    <div class="auth-mini-card">
                        <strong>Cliente</strong>
                        <small>Podrás registrar vehículos, solicitar asistencia y seguir el servicio.</small>
                    </div>
                    <div class="auth-mini-card">
                        <strong>Proveedor</strong>
                        <small>Después del alta podrás completar perfil, servicios, agenda y documentos.</small>
                    </div>
                </div>
            </section>

            <section class="auth-card-wrap">
                <div class="auth-card">
                    <div class="auth-header">
                        <span class="auth-register-role">
                            {{ $selectedRole === 'provider' ? 'Registro de proveedor' : 'Registro de cliente' }}
                        </span>
                        <h2>Crear cuenta</h2>
                        <p class="auth-subcopy">
                            Completa los datos básicos para comenzar dentro del ecosistema ZYGA.
                        </p>
                    </div>

                    @if ($errors->any())
                        <div class="auth-alert auth-alert--error">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register.post') }}" class="auth-form" novalidate>
                        @csrf

                        <label class="auth-field">
                            <span>Rol</span>
                            <select name="role" required>
                                <option value="client" @selected(old('role', $selectedRole) === 'client')>Cliente</option>
                                <option value="provider" @selected(old('role', $selectedRole) === 'provider')>Proveedor</option>
                            </select>
                        </label>

                        <label class="auth-field">
                            <span>Correo electrónico</span>
                            <input
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                placeholder="nuevo@zyga.com"
                                autocomplete="email"
                                required
                            >
                        </label>

                        <label class="auth-field">
                            <span>Contraseña</span>
                            <input
                                type="password"
                                name="password"
                                placeholder="Mínimo 8 caracteres"
                                autocomplete="new-password"
                                required
                            >
                        </label>

                        <label class="auth-field">
                            <span>Confirmar contraseña</span>
                            <input
                                type="password"
                                name="password_confirmation"
                                placeholder="Repite tu contraseña"
                                autocomplete="new-password"
                                required
                            >
                        </label>

                        <button type="submit" class="auth-button">Crear cuenta</button>
                    </form>

                    <div class="auth-helper-card">
                        <strong>Importante:</strong>
                        {{ $selectedRole === 'provider'
                            ? 'después del registro aún deberás completar tu perfil operativo, tus servicios, tu agenda y tus documentos.'
                            : 'después del registro podrás continuar con vehículos, solicitud de asistencia y seguimiento desde tu portal.' }}
                    </div>

                    <p class="auth-footer-note">
                        ¿Ya tienes acceso?
                        <a href="{{ route('login') }}">Volver a iniciar sesión</a>
                    </p>
                </div>
            </section>
        </div>
    </main>

    @include('partials.pwa-register')
</body>
</html>