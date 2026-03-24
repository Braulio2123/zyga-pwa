<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro | Zyga</title>
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
</head>
<body>

    <div class="register-page">

        <div class="register-bg">
            <img src="{{ asset('images/city.jpg') }}" alt="">
        </div>

        <div class="register-overlay">

            <div class="back-button">
                <a href="{{ route('login') }}">&#8592;</a>
            </div>

            <div class="register-container">

                @if ($errors->any())
                    <div class="card error-card">
                        <div class="error-list">
                            @foreach ($errors->all() as $error)
                                <div class="error-item">{{ $error }}</div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <form action="{{ route('register.post') }}" method="POST">
                    @csrf

                    <div class="card">
                        <h2>Datos personales</h2>

                        <div class="form-field">
                            <label for="name" class="label">Nombre</label>
                            <input
                                type="text"
                                name="name"
                                id="name"
                                class="input-custom"
                                value="{{ old('name') }}"
                                placeholder="Ingresa tu nombre">
                        </div>

                        <div class="form-field">
                            <label for="last_name" class="label">Apellidos</label>
                            <input
                                type="text"
                                name="last_name"
                                id="last_name"
                                class="input-custom"
                                value="{{ old('last_name') }}"
                                placeholder="Ingresa tus apellidos">
                        </div>
                    </div>

                    <div class="card">
                        <div class="form-field">
                            <label for="phone" class="label">Número de teléfono</label>
                            <input
                                type="text"
                                name="phone"
                                id="phone"
                                class="input-custom"
                                value="{{ old('phone') }}"
                                placeholder="Ingresa tu número">
                        </div>

                        <div class="form-field">
                            <label for="email" class="label">Correo electrónico</label>
                            <input
                                type="email"
                                name="email"
                                id="email"
                                class="input-custom"
                                value="{{ old('email') }}"
                                placeholder="ejemplo@correo.com">
                        </div>

                        <div class="form-field">
                            <label for="social_media" class="label">Redes sociales</label>
                            <input
                                type="text"
                                name="social_media"
                                id="social_media"
                                class="input-custom"
                                value="{{ old('social_media') }}"
                                placeholder="Agrega tu usuario o enlace">
                        </div>

                        <div class="form-field">
                            <label for="address" class="label">Domicilio</label>
                            <input
                                type="text"
                                name="address"
                                id="address"
                                class="input-custom"
                                value="{{ old('address') }}"
                                placeholder="Ingresa tu domicilio">
                        </div>

                        <div class="form-field">
                            <label for="payment_method" class="label">Método de pago</label>
                            <input
                                type="text"
                                name="payment_method"
                                id="payment_method"
                                class="input-custom"
                                value="{{ old('payment_method') }}"
                                placeholder="Ej. Tarjeta, transferencia, efectivo">
                        </div>

                        <div class="form-field">
                            <label for="password" class="label">Contraseña</label>
                            <div class="password-wrapper">
                                <input
                                    type="password"
                                    name="password"
                                    id="password"
                                    class="input-custom input-password"
                                    placeholder="Ingresa tu contraseña">
                                <button type="button" class="toggle-password" data-target="password" aria-label="Mostrar u ocultar contraseña">
                                    👁
                                </button>
                            </div>
                        </div>

                        <div class="form-field">
                            <label for="password_confirmation" class="label">Confirmar contraseña</label>
                            <div class="password-wrapper">
                                <input
                                    type="password"
                                    name="password_confirmation"
                                    id="password_confirmation"
                                    class="input-custom input-password"
                                    placeholder="Confirma tu contraseña">
                                <button type="button" class="toggle-password" data-target="password_confirmation" aria-label="Mostrar u ocultar contraseña">
                                    👁
                                </button>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="submit-btn">
                        Aceptar y avanzar
                    </button>
                </form>

            </div>

        </div>

    </div>

    <script>
        document.querySelectorAll('.toggle-password').forEach(function (button) {
            button.addEventListener('click', function (e) {
                e.preventDefault();

                const targetName = this.getAttribute('data-target');
                const input = document.querySelector(`input[name="${targetName}"]`);

                if (!input) return;

                const isPassword = input.type === 'password';
                input.type = isPassword ? 'text' : 'password';
                this.textContent = isPassword ? '🙈' : '👁';
                input.focus();
            });
        });
    </script>

</body>
</html>