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
    <main class="auth-shell auth-shell--register">
        <section class="auth-panel auth-panel--brand">
            <a href="{{ route('login') }}" class="auth-back">← Volver al acceso</a>
            <div class="auth-brand-badge">ZYGA</div>
            <p class="auth-kicker">Alta de cuenta</p>
            <h1>Una incorporación sobria, rápida y alineada al servicio.</h1>
            <p class="auth-copy">
                Registra tu acceso y continúa con una experiencia pensada para clientes y proveedores que buscan atención seria, ágil y bien presentada.
            </p>
            <div class="auth-points">
                <span>Registro directo</span>
                <span>Perfil profesional</span>
                <span>Base segura</span>
            </div>
        </section>

        <section class="auth-panel auth-panel--form">
            <div class="auth-card">
                <div class="auth-header">
                    <p class="auth-eyebrow">{{ $selectedRole === 'provider' ? 'Alta de proveedor' : 'Alta de cliente' }}</p>
                    <h2>Crear cuenta</h2>
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
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="nuevo@zyga.com" autocomplete="email" required>
                    </label>

                    <label class="auth-field">
                        <span>Contraseña</span>
                        <input type="password" name="password" placeholder="Mínimo 8 caracteres" autocomplete="new-password" required>
                    </label>

                    <label class="auth-field">
                        <span>Confirmar contraseña</span>
                        <input type="password" name="password_confirmation" placeholder="Repite la contraseña" autocomplete="new-password" required>
                    </label>

                    <button type="submit" class="auth-button">Crear cuenta</button>
                </form>
            </div>
        </section>
    </main>
    @include('partials.pwa-register')
</body>
</html>
