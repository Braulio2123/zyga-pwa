<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Zyga</title>

    <link rel="manifest" href="{{ asset('build/manifest.webmanifest') }}">
    <meta name="theme-color" content="#0f172a">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            margin: 0;
            font-family: 'Instrument Sans', sans-serif;
            color: #444;
        }

        .hero {
            height: 100vh;
            background: url('/images/city.jpg') center/cover no-repeat;
            filter: grayscale(100%);
            position: relative;
        }

        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.9);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 20px;
        }

        .logo-lines {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
        }

        .line {
            width: 60px;
            height: 4px;
            background: #ff6a00;
        }

        .title {
            font-size: 70px;
            color: #555;
            font-weight: 600;
        }

        .subtitle {
            margin-top: 10px;
            font-size: 18px;
            color: #777;
            max-width: 500px;
        }

        .actions {
            margin-top: 40px;
            display: flex;
            gap: 20px;
        }

        .btn-main {
            background: #ff6a00;
            color: white;
            padding: 14px 40px;
            border-radius: 10px;
            font-size: 18px;
            text-decoration: none;
            transition: .2s;
        }

        .btn-main:hover {
            background: #e55e00;
        }

        .btn-outline {
            border: 2px solid #ff6a00;
            color: #ff6a00;
            padding: 14px 40px;
            border-radius: 10px;
            font-size: 18px;
            text-decoration: none;
        }

        .features {
            padding: 80px 20px;
            background: #fafafa;
            text-align: center;
        }

        .features h2 {
            font-size: 36px;
            margin-bottom: 50px;
        }

        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            max-width: 1100px;
            margin: auto;
        }

        .card {
            background: white;
            text-align: justify;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            transition: .2s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .icon {
            font-size: 32px;
            margin-bottom: 10px;
            color: #ff6a00;
        }

        .about {
            padding: 80px 20px;
            text-align: justify;
            max-width: 900px;
            margin: auto;
            text-align: center;
        }

        .about h2 {
            font-size: 36px;
            margin-bottom: 20px;
        }

        .about p {
            text-align: justify;
        }

        .footer {
            padding: 30px;
            text-align: center;
            background: #f3f3f3;
            color: #777;
        }
    </style>

</head>

<body>

    <section class="hero">
        <div class="overlay">
            <div class="logo-lines">
                <div class="line"></div>
                <div class="line"></div>
                <div class="line"></div>
            </div>

            <div class="title">Zyga</div>

            <div class="subtitle">
                Asistencia vial inteligente cuando más la necesitas
            </div>

            <div class="actions">
                <a href="{{ route('login') }}" class="btn-main">
                    Solicitar asistencia
                </a>

                <a href="{{ route('register') }}" class="btn-outline">
                    Registrarse
                </a>
            </div>
        </div>
    </section>

    <section class="features">
        <h2>¿Qué puedes hacer con Zyga?</h2>

        <div class="feature-grid">
            <div class="card">
                <h3>Asistencia vial inmediata</h3>
                <p>Solicita ayuda para llantas ponchadas, batería descargada, falta de combustible o fallas mecánicas
                    menores directamente desde tu teléfono.</p>
            </div>

            <div class="card">
                <h3>Seguimiento en tiempo real</h3>
                <p>Visualiza el estado de tu solicitud, conoce al proveedor asignado y sigue su ubicación hasta que
                    llegue a ayudarte.</p>
            </div>

            <div class="card">
                <h3>Proveedores verificados</h3>
                <p>Talleres mecánicos, grúas y técnicos afiliados ofrecen servicios confiables y regulados dentro de la
                    plataforma.</p>
            </div>

            <div class="card">
                <h3>Costos claros y seguros</h3>
                <p>Conoce el precio del servicio desde el inicio y evita sorpresas o servicios informales sin garantías.</p>
            </div>
        </div>
    </section>

    <section class="about">
        <h2>Una nueva forma de recibir auxilio vial</h2>

        <p>
            ZYGA es una plataforma digital que conecta conductores con proveedores de asistencia vial
            de forma rápida, segura y transparente. A través de una aplicación móvil, los usuarios
            pueden solicitar ayuda en situaciones como llantas ponchadas, baterías descargadas,
            falta de combustible o fallas mecánicas menores.
        </p>

        <p>
            La plataforma integra geolocalización, seguimiento en tiempo real y proveedores
            validados para reducir tiempos de espera y brindar mayor seguridad en momentos
            de urgencia. Además, ZYGA ofrece un modelo de suscripción que permite acceder
            a ciertos servicios sin costos adicionales.
        </p>
    </section>

    <footer class="footer">
        © {{ date('Y') }} Zyga · Plataforma digital de asistencia vial
    </footer>
</body>

</html>
