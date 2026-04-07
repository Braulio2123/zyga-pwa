<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Iniciar sesión | Zyga</title>
    <link rel="stylesheet" href="{{ asset('css/auth-custom.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        /* Estilos adicionales para mensajes */
        .alert-error {
            background: #fee2e2;
            color: #dc2626;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 16px;
            font-size: 14px;
        }
        
        .alert-success {
            background: #dcfce7;
            color: #16a34a;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 16px;
            font-size: 14px;
        }
    </style>
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
                    <form id="loginForm" class="login-form" novalidate>
                        @csrf

                        <div id="messageBox"></div>

                        <div class="form-group">
                            <label for="email">Correo electrónico</label>
                            <div class="input-group">
                                <input type="email" id="email" name="email" placeholder="Example@gmail.com" autocomplete="email" autofocus required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password">Contraseña</label>
                            <div class="input-group password-group">
                                <input type="password" id="password" name="password" placeholder="****************" autocomplete="current-password" required>
                                <button type="button" id="togglePassword" class="toggle-password" aria-label="Mostrar contraseña">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="forgot-password">
                            <a href="#">¿Olvidaste tu contraseña?</a>
                        </div>

                        <button type="submit" class="login-btn" id="btnLogin">
                            Ingresar
                        </button>
                    </form>
                </div>

                <div class="login-footer-register">
                    <div class="footer-option">
                        <button type="button" onclick="loginWithGoogle()" class="footer-icon-link" style="background: none; border: none; cursor: pointer;">
                            <img src="{{ asset('images/google.png') }}" alt="Google">
                        </button>
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
        const API_BASE = 'https://zyga-api-production.up.railway.app/api/v1';
        
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

        const loginForm = document.getElementById('loginForm');
        const btnLogin = document.getElementById('btnLogin');
        const messageBox = document.getElementById('messageBox');

        function showMessage(type, text) {
            messageBox.innerHTML = `<div class="alert-${type}">${text}</div>`;
        }

        function loginWithGoogle() {
            window.location.href = `${API_BASE}/auth/google`;
        }

        loginForm.addEventListener('submit', async function (e) {
            e.preventDefault();

            btnLogin.disabled = true;
            btnLogin.textContent = 'Ingresando...';

            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;

            try {
                const response = await fetch(`${API_BASE}/auth/login`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ email, password })
                });

                const data = await response.json();

                if (!response.ok) {
                    showMessage('error', data.message || 'Error al iniciar sesión');
                    btnLogin.disabled = false;
                    btnLogin.textContent = 'Ingresar';
                    return;
                }

                // Extraer token según la estructura de tu API
                const token = data.data?.token || data.access_token;
                const user = data.data?.user || data.user;

                if (!token) {
                    showMessage('error', 'No se recibió token de autenticación');
                    btnLogin.disabled = false;
                    btnLogin.textContent = 'Ingresar';
                    return;
                }

                console.log('Token obtenido:', token.substring(0, 30) + '...');
                console.log('Usuario:', user?.email);

                // Guardar en localStorage
                localStorage.setItem('zyga_token', token);
                localStorage.setItem('zyga_user', JSON.stringify(user));

                showMessage('success', 'Login correcto, redirigiendo...');

                setTimeout(() => {
                    window.location.href = '/user';
                }, 800);

            } catch (error) {
                console.error('Error:', error);
                showMessage('error', 'Error de conexión: ' + error.message);
                btnLogin.disabled = false;
                btnLogin.textContent = 'Ingresar';
            }
        });
    </script>
</body>

</html>