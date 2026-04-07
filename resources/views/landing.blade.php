<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Zyga</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --zyga-orange: #ff6a00;
            --zyga-orange-dark: #e55e00;
            --zyga-yellow: #ffd43b;
            --zyga-text: #3f3f3f;
            --zyga-muted: #6c6c6c;
            --zyga-bg: #f6f6f6;
            --zyga-white: #ffffff;
            --zyga-light: #f2f2f2;
            --zyga-border: #ececec;
            --zyga-shadow: 0 18px 40px rgba(0, 0, 0, 0.08);
            --radius-xl: 32px;
            --radius-lg: 22px;
            --radius-md: 14px;
            --container: 1180px;
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            font-family: 'Instrument Sans', sans-serif;
            color: var(--zyga-text);
            background: var(--zyga-white);
        }

        img {
            max-width: 100%;
            display: block;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        .container {
            width: min(var(--container), calc(100% - 28px));
            margin: 0 auto;
        }

        .site-header {
            position: sticky;
            top: 0;
            z-index: 50;
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .site-header__inner {
            min-height: 78px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .brand-lines {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .brand-lines span {
            width: 54px;
            height: 4px;
            border-radius: 999px;
            background: var(--zyga-orange);
        }

        .brand-name {
            font-size: 1.55rem;
            font-weight: 700;
            color: #4a4a4a;
        }

        .site-nav {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .site-nav a {
            padding: 10px 14px;
            border-radius: 999px;
            font-weight: 600;
            color: #444;
        }

        .site-nav a:hover {
            background: #f4f4f4;
        }

        .btn-main,
        .btn-outline {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 14px 22px;
            border-radius: 14px;
            font-size: 1rem;
            font-weight: 700;
            transition: .2s ease;
        }

        .btn-main {
            background: var(--zyga-orange);
            color: #fff;
            box-shadow: 0 12px 26px rgba(255, 106, 0, 0.22);
        }

        .btn-main:hover {
            background: var(--zyga-orange-dark);
        }

        .btn-outline {
            border: 2px solid var(--zyga-orange);
            color: var(--zyga-orange);
            background: #fff;
        }

        .hero {
            position: relative;
            overflow: hidden;
            background:
                linear-gradient(rgba(255, 255, 255, 0.84), rgba(255, 255, 255, 0.92)),
                url('https://images.unsplash.com/photo-1480714378408-67cf0d13bc1b?auto=format&fit=crop&w=1600&q=80') center/cover no-repeat;
        }

        .hero__inner {
            padding: 64px 0 48px;
        }

        .hero__grid {
            display: grid;
            grid-template-columns: 1.05fr 0.95fr;
            gap: 28px;
            align-items: center;
        }

        .hero__content {
            max-width: 640px;
        }

        .hero__kicker {
            display: inline-flex;
            align-items: center;
            padding: 8px 14px;
            border-radius: 999px;
            background: #fff;
            border: 1px solid var(--zyga-border);
            color: var(--zyga-orange-dark);
            font-size: .92rem;
            font-weight: 700;
            margin-bottom: 18px;
        }

        .hero__title {
            margin: 0 0 16px;
            font-size: clamp(2.5rem, 6vw, 4.8rem);
            line-height: 1.02;
            color: #404040;
        }

        .hero__text {
            margin: 0;
            font-size: 1.08rem;
            line-height: 1.75;
            color: var(--zyga-muted);
            max-width: 580px;
        }

        .hero__actions {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
            margin-top: 28px;
        }

        .hero__stats {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
            margin-top: 28px;
        }

        .stat-card {
            padding: 18px;
            border-radius: 20px;
            box-shadow: var(--zyga-shadow);
            background: var(--zyga-white);
            border: 1px solid var(--zyga-border);
        }

        .stat-card strong {
            display: block;
            margin-bottom: 6px;
            font-size: 1.05rem;
        }

        .stat-card span {
            font-size: .92rem;
            color: var(--zyga-muted);
            line-height: 1.45;
        }

        .hero__visual {
            display: flex;
            justify-content: center;
        }

        .phone-shell {
            width: min(100%, 430px);
            background: var(--zyga-white);
            border: 1px solid var(--zyga-border);
            border-radius: 34px;
            padding: 16px;
            box-shadow: 0 26px 50px rgba(0, 0, 0, 0.14);
        }

        .phone-screen {
            border-radius: 28px;
            overflow: hidden;
            background:
                linear-gradient(rgba(255, 255, 255, 0.60), rgba(255, 255, 255, 0.78)),
                url('https://images.unsplash.com/photo-1449824913935-59a10b8d2000?auto=format&fit=crop&w=1200&q=80') center/cover no-repeat;
            padding: 16px;
            display: grid;
            gap: 14px;
        }

        .welcome-card,
        .services-card,
        .request-card,
        .banner-card,
        .support-card,
        .bottom-nav {
            background: rgba(255, 255, 255, 0.97);
            border-radius: 20px;
            box-shadow: var(--zyga-shadow);
        }

        .welcome-card {
            padding: 14px 16px;
            font-weight: 700;
            color: #555;
        }

        .section-pill {
            display: inline-flex;
            width: fit-content;
            padding: 8px 14px;
            border-radius: 999px;
            background: var(--zyga-orange);
            color: #fff;
            font-size: .88rem;
            font-weight: 700;
        }

        .services-card {
            padding: 16px;
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 10px;
        }

        .service-box {
            border-radius: 16px;
            padding: 12px 8px;
            text-align: center;
            font-size: .74rem;
            font-weight: 700;
            line-height: 1.35;
            color: #555;
            background: #fff;
            border: 1px solid var(--zyga-border);
        }

        .service-box:nth-child(1),
        .service-box:nth-child(4) {
            border-color: rgba(255, 106, 0, 0.24);
        }

        .request-card {
            overflow: hidden;
        }

        .request-card__top {
            padding: 14px 16px 0;
        }

        .map-area {
            height: 215px;
            margin: 10px 16px 0;
            border-radius: 18px;
            background:
                linear-gradient(rgba(255,255,255,.16), rgba(255,255,255,.16)),
                url('https://images.unsplash.com/photo-1524661135-423995f22d0b?auto=format&fit=crop&w=1200&q=80') center/cover no-repeat;
            position: relative;
        }

        .map-area::before,
        .map-area::after {
            content: "";
            position: absolute;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: var(--zyga-orange);
            box-shadow: 0 0 0 6px rgba(255, 106, 0, 0.18);
        }

        .map-area::before {
            top: 42px;
            left: 74px;
        }

        .map-area::after {
            right: 88px;
            bottom: 40px;
        }

        .request-card__actions {
            display: flex;
            justify-content: flex-end;
            padding: 14px 16px 16px;
        }

        .mini-btn {
            border: none;
            border-radius: 999px;
            padding: 10px 18px;
            background: var(--zyga-orange);
            color: #fff;
            font-weight: 700;
            font-family: inherit;
        }

        .banner-card {
            padding: 16px;
            background: var(--zyga-yellow);
            color: #4d4d4d;
            font-weight: 700;
        }

        .support-card {
            padding: 14px;
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 10px;
        }

        .support-card div {
            padding: 12px 8px;
            border-radius: 16px;
            font-size: .75rem;
            font-weight: 700;
            text-align: center;
            line-height: 1.35;
            color: #555;
            background: #fff;
            border: 1px solid var(--zyga-border);
        }

        .bottom-nav {
            padding: 12px;
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 8px;
            text-align: center;
            font-size: .74rem;
            font-weight: 700;
            color: #888;
        }

        .bottom-nav div {
            padding: 10px 6px;
            border-radius: 12px;
        }

        .bottom-nav div.active {
            background: #fff3ea;
            color: var(--zyga-orange);
        }

        .section {
            padding: 88px 0;
        }

        .section--soft {
            background: #fafafa;
        }

        .section-head {
            max-width: 760px;
            margin: 0 auto 48px;
            text-align: center;
        }

        .section-head h2 {
            margin: 0 0 14px;
            font-size: clamp(2rem, 4vw, 3rem);
            color: #444;
        }

        .section-head p {
            margin: 0;
            font-size: 1.02rem;
            line-height: 1.75;
            color: var(--zyga-muted);
        }

        .cards-4,
        .cards-3 {
            display: grid;
            gap: 24px;
        }

        .cards-4 {
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }

        .cards-3 {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .info-card {
            padding: 30px;
            border-radius: 24px;
            box-shadow: 0 14px 32px rgba(0, 0, 0, 0.06);
            transition: .2s ease;
            background: #fff;
            border: 1px solid var(--zyga-border);
        }

        .info-card:hover,
        .step-card:hover {
            transform: translateY(-4px);
        }

        .info-card h3 {
            margin: 0 0 12px;
            font-size: 1.2rem;
            color: #3f3f3f;
            line-height: 1.35;
        }

        .info-card p {
            margin: 0;
            text-align: justify;
            line-height: 1.72;
            color: var(--zyga-muted);
        }

        .step-card {
            overflow: hidden;
            padding: 0;
            background: #fff;
            border-radius: 24px;
            box-shadow: 0 14px 32px rgba(0, 0, 0, 0.06);
            transition: .2s ease;
            border: 1px solid var(--zyga-border);
        }

        .step-card__image {
            height: 220px;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .step-card__body {
            padding: 26px;
        }

        .step-card__number {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            border-radius: 999px;
            background: var(--zyga-orange);
            color: #fff;
            font-weight: 700;
            margin-bottom: 14px;
        }

        .step-card__body h3 {
            margin: 0 0 12px;
            font-size: 1.18rem;
            color: #444;
        }

        .step-card__body p {
            margin: 0;
            color: var(--zyga-muted);
            line-height: 1.72;
            text-align: justify;
        }

        .highlight {
            display: grid;
            grid-template-columns: 0.95fr 1.05fr;
            gap: 28px;
            align-items: center;
        }

        .highlight__content {
            background: #fff;
            border-radius: 28px;
            padding: 34px;
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.06);
            border: 1px solid var(--zyga-border);
        }

        .highlight__content h2 {
            margin: 0 0 16px;
            font-size: clamp(2rem, 4vw, 3rem);
            color: #444;
        }

        .highlight__content p {
            margin: 0 0 16px;
            text-align: justify;
            line-height: 1.82;
            color: var(--zyga-muted);
        }

        .highlight__actions {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
            margin-top: 24px;
        }

        .highlight__image {
            min-height: 430px;
            border-radius: 28px;
            background:
                linear-gradient(rgba(255, 106, 0, 0.06), rgba(255, 106, 0, 0.06)),
                url('https://ahorraseguros.mx/wp-content/uploads/2025/08/asistencia-vial.jpg') center/cover no-repeat;
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.08);
        }

        .download-section {
            background: #111;
            color: #fff;
        }

        .download-grid {
            display: grid;
            grid-template-columns: 1.05fr 0.95fr;
            gap: 28px;
            align-items: center;
        }

        .download-copy h2 {
            margin: 0 0 16px;
            font-size: clamp(2rem, 4vw, 3.4rem);
            line-height: 1.08;
        }

        .download-copy p {
            margin: 0;
            color: rgba(255,255,255,.78);
            line-height: 1.8;
            max-width: 620px;
        }

        .download-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
            margin-top: 28px;
        }

        .download-actions .btn-main {
            background: var(--zyga-orange);
            color: #fff;
        }

        .download-actions .btn-outline {
            border-color: #fff;
            color: #fff;
            background: transparent;
        }

        .download-panel {
            background: #1c1c1c;
            border-radius: 30px;
            padding: 24px;
            border: 1px solid rgba(255,255,255,.08);
            box-shadow: 0 20px 40px rgba(0,0,0,.20);
        }

        .download-phone {
            background: #fff;
            border-radius: 26px;
            padding: 14px;
            width: min(100%, 360px);
            margin: 0 auto;
        }

        .download-phone__screen {
            min-height: 520px;
            border-radius: 22px;
            overflow: hidden;
            background:
                linear-gradient(rgba(255,255,255,.68), rgba(255,255,255,.82)),
                url('https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&w=1200&q=80') center/cover no-repeat;
            padding: 16px;
            display: grid;
            gap: 12px;
            align-content: start;
        }

        .download-mini-card {
            background: rgba(255,255,255,.95);
            border-radius: 18px;
            padding: 14px;
            box-shadow: var(--zyga-shadow);
        }

        .download-mini-card strong {
            display: block;
            margin-bottom: 6px;
            color: #444;
        }

        .download-mini-card p {
            margin: 0;
            color: var(--zyga-muted);
            font-size: .92rem;
            line-height: 1.55;
        }

        .download-highlight {
            background: var(--zyga-orange);
            color: #fff;
            border-radius: 18px;
            padding: 16px;
            font-weight: 700;
            line-height: 1.5;
        }

        .footer {
            padding: 30px;
            text-align: center;
            background: #f3f3f3;
            color: #777;
        }

        @media (max-width: 1100px) {
            .hero__grid,
            .highlight,
            .download-grid,
            .cards-4,
            .cards-3 {
                grid-template-columns: 1fr;
            }

            .hero__content {
                max-width: none;
            }

            .hero__stats {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .site-header__inner {
                align-items: flex-start;
                flex-direction: column;
                padding: 14px 0;
            }

            .site-nav {
                width: 100%;
                justify-content: flex-start;
            }

            .hero__inner {
                padding: 42px 0 40px;
            }

            .hero__actions,
            .highlight__actions,
            .download-actions {
                flex-direction: column;
                align-items: stretch;
            }

            .btn-main,
            .btn-outline {
                width: 100%;
            }

            .services-grid,
            .support-card,
            .bottom-nav {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .section {
                padding: 68px 0;
            }
        }

        @media (max-width: 480px) {
            .container {
                width: min(100%, calc(100% - 20px));
            }

            .phone-shell,
            .highlight__content,
            .download-panel,
            .info-card,
            .step-card__body {
                padding-left: 18px;
                padding-right: 18px;
            }

            .services-grid,
            .support-card,
            .bottom-nav {
                grid-template-columns: 1fr;
            }

            .site-nav a {
                width: 100%;
            }
        }
    </style>
</head>

<body>

    <header class="site-header">
        <div class="container site-header__inner">
            <div class="brand">
                <div class="brand-lines">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                <div class="brand-name">Zyga</div>
            </div>

            <nav class="site-nav">
                <a href="#beneficios">Beneficios</a>
                <a href="#como-funciona">Cómo funciona</a>
                <a href="#proveedores">Proveedores</a>
                <a href="{{ route('login') }}" class="btn-outline">Iniciar sesión</a>
            </nav>
        </div>
    </header>

    <section class="hero">
        <div class="container hero__inner">
            <div class="hero__grid">
                <div class="hero__content">
                    <div class="hero__kicker">Asistencia vial digital</div>

                    <h1 class="hero__title">Resuelve emergencias viales con una experiencia más rápida y clara.</h1>

                    <p class="hero__text">
                        Zyga conecta conductores con proveedores de asistencia vial en una plataforma
                        visual, moderna y práctica. Solicita grúa, cambio de llanta, combustible,
                        batería o cerrajería con seguimiento del servicio y mejor organización de la atención.
                    </p>

                    <div class="hero__actions">
                        <a href="{{ route('login') }}" class="btn-main">Solicitar asistencia</a>
                        <a href="{{ route('register') }}" class="btn-outline">Registrarse</a>
                    </div>

                    <div class="hero__stats">
                        <div class="stat-card">
                            <strong>Atención inmediata</strong>
                            <span>Solicita ayuda vial desde una sola plataforma cuando se presente una urgencia.</span>
                        </div>

                        <div class="stat-card">
                            <strong>Seguimiento del servicio</strong>
                            <span>Consulta el estado de la solicitud y da seguimiento al proceso de atención.</span>
                        </div>

                        <div class="stat-card">
                            <strong>Proveedores confiables</strong>
                            <span>Conecta con prestadores registrados para una operación más organizada.</span>
                        </div>
                    </div>
                </div>

                <div class="hero__visual">
                    <div class="phone-shell">
                        <div class="phone-screen">
                            <div class="welcome-card">Hola, bienvenido a Zyga</div>

                            <div class="section-pill">¿Problemas en el camino?</div>

                            <div class="services-card">
                                <div class="services-grid">
                                    <div class="service-box">Servicio de grúa</div>
                                    <div class="service-box">Cambio de llanta</div>
                                    <div class="service-box">Combustible</div>
                                    <div class="service-box">Más servicios</div>
                                </div>
                            </div>

                            <div class="request-card">
                                <div class="request-card__top">
                                    <div class="section-pill">Solicitud en proceso</div>
                                </div>

                                <div class="map-area"></div>

                                <div class="request-card__actions">
                                    <button class="mini-btn">Detalles</button>
                                </div>
                            </div>

                            <div class="banner-card">
                                Tu asistencia vial, donde la necesites.
                            </div>

                            <div class="support-card">
                                <div>Manejo seguro</div>
                                <div>Asistencia médica</div>
                                <div>Soporte</div>
                            </div>

                            <div class="bottom-nav">
                                <div class="active">Inicio</div>
                                <div>Historial</div>
                                <div>Billetera</div>
                                <div>Cuenta</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section section--soft" id="beneficios">
        <div class="container">
            <div class="section-head">
                <h2>¿Qué puedes hacer con Zyga?</h2>
                <p>
                    Zyga toma la lógica de asistencia vial y la transforma en una experiencia digital
                    más clara, visual y funcional para quienes necesitan ayuda en el camino.
                </p>
            </div>

            <div class="cards-4">
                <article class="info-card">
                    <h3>Asistencia vial inmediata</h3>
                    <p>
                        Solicita ayuda para llantas ponchadas, batería descargada, falta de combustible
                        o fallas mecánicas menores directamente desde tu dispositivo.
                    </p>
                </article>

                <article class="info-card">
                    <h3>Seguimiento en tiempo real</h3>
                    <p>
                        Visualiza el estado de la solicitud, consulta el progreso del servicio
                        y mantén visibilidad del proceso hasta la atención final.
                    </p>
                </article>

                <article class="info-card">
                    <h3>Proveedores verificados</h3>
                    <p>
                        Talleres, grúas y técnicos afiliados participan dentro de un flujo más organizado,
                        fortaleciendo la confianza y la operación del sistema.
                    </p>
                </article>

                <article class="info-card">
                    <h3>Mayor claridad del servicio</h3>
                    <p>
                        Zyga busca ofrecer una experiencia más transparente en información, estado del servicio
                        y visualización de la atención solicitada.
                    </p>
                </article>
            </div>
        </div>
    </section>

    <section class="section" id="como-funciona">
        <div class="container">
            <div class="section-head">
                <h2>Cómo funciona</h2>
                <p>
                    El flujo de Zyga está diseñado para que la solicitud de asistencia sea simple
                    para el usuario y clara en cada etapa del servicio.
                </p>
            </div>

            <div class="cards-3">
                <article class="step-card">
                    <div class="step-card__image"
                        style="background-image:
                        linear-gradient(rgba(255,255,255,.10), rgba(255,255,255,.10)),
                        url('https://images.unsplash.com/photo-1485291571150-772bcfc10da5?auto=format&fit=crop&w=1200&q=80');">
                    </div>
                    <div class="step-card__body">
                        <div class="step-card__number">1</div>
                        <h3>Selecciona el servicio</h3>
                        <p>
                            El usuario elige el tipo de apoyo que necesita según la situación del vehículo,
                            como grúa, cambio de llanta, batería, combustible o cerrajería.
                        </p>
                    </div>
                </article>

                <article class="step-card">
                    <div class="step-card__image"
                        style="background-image:
                        linear-gradient(rgba(255,255,255,.10), rgba(255,255,255,.10)),
                        url('https://images.unsplash.com/photo-1524661135-423995f22d0b?auto=format&fit=crop&w=1200&q=80');">
                    </div>
                    <div class="step-card__body">
                        <div class="step-card__number">2</div>
                        <h3>Comparte tu ubicación</h3>
                        <p>
                            La plataforma toma la referencia del lugar para facilitar la asignación
                            del proveedor y permitir un seguimiento más claro del servicio.
                        </p>
                    </div>
                </article>

                <article class="step-card">
                    <div class="step-card__image"
                        style="background-image:
                        linear-gradient(rgba(255,255,255,.10), rgba(255,255,255,.10)),
                        url('https://images.unsplash.com/photo-1519003722824-194d4455a60c?auto=format&fit=crop&w=1200&q=80');">
                    </div>
                    <div class="step-card__body">
                        <div class="step-card__number">3</div>
                        <h3>Recibe asistencia</h3>
                        <p>
                            Zyga conecta al usuario con el proveedor y permite consultar el avance
                            de la solicitud hasta completar la atención en el punto requerido.
                        </p>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <section class="section section--soft" id="proveedores">
        <div class="container">
            <div class="highlight">
                <div class="highlight__content">
                    <h2>Un portal pensado también para proveedores</h2>

                    <p>
                        Zyga no solo está diseñado para quien solicita ayuda. También integra un portal web
                        para proveedores, donde pueden consultar su perfil, administrar servicios, definir horarios,
                        revisar documentos y visualizar solicitudes disponibles.
                    </p>

                    <p>
                        Esto permite una operación más organizada entre la parte visual del sistema y la atención real,
                        fortaleciendo la coordinación dentro de la plataforma.
                    </p>

                    <div class="highlight__actions">
                        <a href="{{ route('register') }}" class="btn-main">Crear cuenta</a>
                        <a href="{{ route('login') }}" class="btn-outline">Acceder</a>
                    </div>
                </div>

                <div class="highlight__image"></div>
            </div>
        </div>
    </section>

    <section class="section download-section">
        <div class="container">
            <div class="download-grid">
                <div class="download-copy">
                    <h2>Lleva Zyga contigo y responde más rápido ante cualquier emergencia.</h2>
                    <p>
                        La experiencia de Zyga está pensada para sentirse como una app real desde el navegador.
                        Consulta servicios, da seguimiento a la solicitud y mantén el control de la asistencia
                        desde una interfaz clara, adaptable y preparada para uso móvil.
                    </p>

                    <div class="download-actions">
                        <a href="{{ route('register') }}" class="btn-main">Comenzar ahora</a>
                        <a href="{{ route('login') }}" class="btn-outline">Ya tengo cuenta</a>
                    </div>
                </div>

                <div class="download-panel">
                    <div class="download-phone">
                        <div class="download-phone__screen">
                            <div class="download-mini-card">
                                <strong>Solicitud activa</strong>
                                <p>Consulta el estado del servicio y mantén el seguimiento de tu asistencia.</p>
                            </div>

                            <div class="download-highlight">
                                Zyga centraliza la ayuda vial en una sola experiencia móvil, rápida y organizada.
                            </div>

                            <div class="download-mini-card">
                                <strong>Servicios disponibles</strong>
                                <p>Grúa, llantas, combustible, batería, cerrajería y más en una sola plataforma.</p>
                            </div>

                            <div class="download-mini-card">
                                <strong>Portal proveedor</strong>
                                <p>Los proveedores pueden gestionar perfil, horarios, documentos y solicitudes.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer">
        © {{ date('Y') }} Zyga · Plataforma digital de asistencia vial
    </footer>

</body>

</html>