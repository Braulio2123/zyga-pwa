<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>@yield('title', 'ZYGA Provider')</title>
    <style>
        :root {
            --bg: #f5f4f1;
            --shell: #ffffff;
            --shell-soft: #f7f8fb;
            --text: #101828;
            --muted: #667085;
            --line: #e5e7eb;
            --brand: #ff8a00;
            --brand-strong: #f97316;
            --navy: #0c275d;
            --navy-soft: #173b8a;
            --navy-panel: #eaf0ff;
            --success-bg: #e8f8ee;
            --success-text: #0f8a51;
            --warning-bg: #fff3df;
            --warning-text: #c26b00;
            --danger-bg: #fef0f0;
            --danger-text: #b42318;
            --shadow-lg: 0 22px 44px rgba(15, 23, 42, 0.10);
            --shadow-sm: 0 10px 20px rgba(15, 23, 42, 0.05);
            --radius-xl: 30px;
            --radius-lg: 24px;
            --radius-md: 18px;
        }
        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            margin: 0;
            min-height: 100vh;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            color: var(--text);
            background:
                radial-gradient(circle at top left, rgba(255, 138, 0, 0.16), transparent 18%),
                radial-gradient(circle at right top, rgba(23, 59, 138, 0.18), transparent 24%),
                linear-gradient(180deg, #f4f1eb 0%, #f7f7fb 100%);
        }
        a { color: inherit; text-decoration: none; }
        button { font: inherit; }
        .shell { max-width: 1320px; margin: 0 auto; padding: 24px 20px 104px; }
        .topbar {
            display: flex;
            justify-content: space-between;
            gap: 18px;
            padding: 22px 24px;
            border-radius: 30px;
            background: linear-gradient(135deg, #0b1f46 0%, #0d2f72 100%);
            color: #fff;
            box-shadow: 0 20px 38px rgba(12, 39, 93, 0.22);
            border: 1px solid rgba(255,255,255,.08);
            margin-bottom: 22px;
        }
        .topbar-left { min-width: 0; }
        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin: 0 0 10px;
            padding: 8px 14px;
            border-radius: 999px;
            background: rgba(255,255,255,.10);
            color: #dbe6ff;
            font-size: .86rem;
            font-weight: 800;
            letter-spacing: .02em;
        }
        .page-title {
            margin: 0;
            font-size: clamp(1.9rem, 3vw, 2.4rem);
            line-height: 1.05;
            letter-spacing: -.03em;
        }
        .topbar-copy {
            margin: 10px 0 0;
            max-width: 760px;
            color: rgba(255,255,255,.80);
            font-size: 1rem;
            line-height: 1.45;
        }
        .topbar-right {
            display: grid;
            grid-template-columns: auto auto;
            gap: 16px;
            align-items: start;
        }
        .topbar-actions {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-end;
            gap: 10px;
            align-content: start;
        }
        .pill-link, .logout-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 44px;
            padding: 10px 16px;
            border-radius: 999px;
            font-weight: 800;
            font-size: .96rem;
            border: 0;
            cursor: pointer;
        }
        .pill-link.primary { background: linear-gradient(135deg, var(--brand), var(--brand-strong)); color: #fff; box-shadow: 0 12px 24px rgba(255,138,0,.26); }
        .pill-link.secondary { background: rgba(255,255,255,.12); color: #fff; }
        .logout-btn { background: rgba(255,255,255,.95); color: var(--navy); box-shadow: var(--shadow-sm); }
        .user-box {
            display: flex;
            align-items: center;
            gap: 12px;
            padding-left: 6px;
        }
        .user-meta { text-align: right; }
        .user-meta strong { display: block; font-size: 1rem; }
        .user-meta small { display: block; margin-top: 2px; color: rgba(255,255,255,.74); }
        .avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            background: linear-gradient(135deg, #ffb14b, #ff7c5c);
            color: #fff;
            font-weight: 900;
            font-size: 1.1rem;
            box-shadow: 0 14px 30px rgba(255,138,0,.28);
        }
        .grid { display: grid; gap: 18px; }
        .hero, .card, .lockbox, .kpi {
            background: rgba(255,255,255,.96);
            border: 1px solid rgba(229,231,235,.95);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-lg);
        }
        .hero, .card, .lockbox { padding: 24px; }
        .hero {
            background:
                radial-gradient(circle at right top, rgba(255, 138, 0, 0.12), transparent 24%),
                linear-gradient(180deg, #ffffff 0%, #fbfbfd 100%);
        }
        .hero-split, .two-col, .three-col, .kpi-grid, .meta-grid, .form-grid {
            display: grid;
            gap: 16px;
        }
        .hero-split { grid-template-columns: 1.28fr .88fr; }
        .two-col { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .three-col { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        .kpi-grid { grid-template-columns: repeat(4, minmax(0, 1fr)); }
        .hero-panel {
            display: grid;
            gap: 12px;
            padding: 18px;
            border-radius: 26px;
            background: linear-gradient(180deg, #0f2e6f 0%, #173b8a 100%);
            color: #fff;
            box-shadow: inset 0 0 0 1px rgba(255,255,255,.06);
        }
        .chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 13px;
            border-radius: 999px;
            font-size: .88rem;
            font-weight: 800;
            background: #eef2ff;
            color: var(--navy-soft);
            border: 1px solid #dbe6ff;
        }
        .chip.success { background: var(--success-bg); color: var(--success-text); border-color: #c7efd7; }
        .chip.warning { background: var(--warning-bg); color: var(--warning-text); border-color: #ffd79a; }
        .chip.info { background: #eef4ff; color: var(--navy-soft); border-color: #d4e2ff; }
        .btn, .btn-outline, .btn-ghost {
            border: 0;
            cursor: pointer;
            border-radius: 16px;
            padding: 13px 17px;
            font-size: .96rem;
            font-weight: 800;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: transform .18s ease, box-shadow .18s ease, background .18s ease;
        }
        .btn { background: linear-gradient(135deg, var(--brand), var(--brand-strong)); color: #fff; box-shadow: 0 14px 28px rgba(255,138,0,.22); }
        .btn-outline { background: #f8fafc; color: var(--navy); border: 1px solid #d4dce8; }
        .btn-ghost { background: transparent; color: var(--navy-soft); border: 1px dashed #cfd8e3; }
        .btn:hover, .btn-outline:hover, .btn-ghost:hover, .pill-link:hover, .logout-btn:hover { transform: translateY(-1px); }
        .full { width: 100%; }
        .section-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 14px;
        }
        .section-head h3, .card h3 { margin: 0; }
        .muted, .helper { color: var(--muted); }
        .list, .stack, .timeline { display: grid; gap: 12px; }
        .item {
            padding: 16px 18px;
            border-radius: 22px;
            border: 1px solid #e7ebf3;
            background: linear-gradient(180deg, #ffffff 0%, #fafbfd 100%);
            box-shadow: var(--shadow-sm);
        }
        .item-head {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 14px;
        }
        .item h4 { margin: 0 0 6px; }
        .item p, .item small { margin: 0; color: var(--muted); }
        .empty {
            padding: 22px;
            border-radius: 22px;
            border: 1px dashed #d6dee9;
            background: linear-gradient(180deg, #fbfbfc 0%, #f6f8fb 100%);
        }
        .empty h4 { margin: 0 0 8px; }
        .alert {
            border-radius: 18px;
            padding: 14px 16px;
            border: 1px solid transparent;
            box-shadow: var(--shadow-sm);
        }
        .alert.success { background: var(--success-bg); color: var(--success-text); border-color: #c7efd7; }
        .alert.danger { background: var(--danger-bg); color: var(--danger-text); border-color: #f7c7c5; }
        .alert.warning { background: var(--warning-bg); color: var(--warning-text); border-color: #ffd79a; }
        .summary {
            padding: 18px;
            border-radius: 22px;
            background: rgba(255,255,255,.12);
            border: 1px solid rgba(255,255,255,.10);
        }
        .summary .helper { color: rgba(255,255,255,.72); }
        .summary strong { display: block; margin-top: 8px; font-size: 1.78rem; color: #fff; letter-spacing: -.03em; }
        .meta-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .meta-box {
            padding: 15px;
            border-radius: 18px;
            border: 1px solid #e5eaf2;
            background: var(--shell-soft);
        }
        .meta-box span { display: block; margin-bottom: 6px; font-size: .84rem; color: var(--muted); }
        .form-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .field { display: grid; gap: 8px; }
        .field.full { grid-column: 1 / -1; }
        .label { font-size: .92rem; font-weight: 700; color: #344054; }
        input, select {
            width: 100%;
            padding: 12px 14px;
            border-radius: 15px;
            border: 1px solid #d6dde8;
            background: #fff;
            color: var(--text);
            font-size: .95rem;
            outline: none;
        }
        input:focus, select:focus { border-color: #b6c6eb; box-shadow: 0 0 0 4px rgba(23,59,138,.08); }
        .inline-form { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 12px; }
        .lockbox {
            background: linear-gradient(180deg, #fff7ec 0%, #fffdf8 100%);
            border-color: #ffe1b2;
        }
        .checklist { display: grid; gap: 10px; }
        .check {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 13px 15px;
            border-radius: 18px;
            background: #fff;
            border: 1px solid #f4d7a6;
        }
        .timeline-entry {
            padding: 13px 15px;
            border-left: 4px solid #c7d7ff;
            border-radius: 16px;
            background: var(--shell-soft);
        }
        .bottom-nav {
            position: fixed;
            left: 50%;
            bottom: 14px;
            transform: translateX(-50%);
            width: min(820px, calc(100% - 28px));
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 8px;
            padding: 9px;
            border-radius: 22px;
            background: rgba(17, 28, 45, 0.82);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255,255,255,.10);
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.20);
            z-index: 40;
        }
        .bottom-nav a {
            text-align: center;
            padding: 11px 8px;
            border-radius: 15px;
            color: rgba(255,255,255,.76);
            font-size: .88rem;
            font-weight: 800;
        }
        .bottom-nav a.active { background: linear-gradient(135deg, rgba(255,138,0,.28), rgba(23,59,138,.78)); color: #fff; }
        @media (max-width: 960px) {
            .hero-split, .two-col, .three-col, .kpi-grid, .meta-grid, .form-grid { grid-template-columns: 1fr; }
            .topbar { padding: 20px; }
            .topbar-right { grid-template-columns: 1fr; width: 100%; }
            .topbar-actions { justify-content: flex-start; }
            .user-box { justify-content: space-between; padding-left: 0; }
            .user-meta { text-align: left; }
            .bottom-nav { grid-template-columns: repeat(3, 1fr); }
        }
    </style>
    @stack('page_styles')
</head>
<body>
    @php
        $sessionUser = session('user', []);
        $displayName = $sessionUser['name'] ?? $sessionUser['email'] ?? 'Provider';
        $displayEmail = $sessionUser['email'] ?? 'Sin correo';
        $initial = strtoupper(substr($displayName, 0, 1));
    @endphp

    <div class="shell">
        <header class="topbar">
            <div class="topbar-left">
                <p class="eyebrow">ZYGA PROVIDER HUB</p>
                <h1 class="page-title">@yield('page-title', 'Centro operativo del provider')</h1>
                <p class="topbar-copy">@yield('page-copy', 'Gestiona disponibilidad, servicios y atención con una consola enfocada en respuesta y ejecución.') </p>
            </div>

            <div class="topbar-right">
                <div class="topbar-actions">
                    <a href="{{ route('provider.asistencias') }}" class="pill-link primary">Asistencias</a>
                    <a href="{{ route('provider.dashboard') }}" class="pill-link secondary">Resumen</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="logout-btn">Cerrar sesión</button>
                    </form>
                </div>

                <div class="user-box">
                    <div class="user-meta">
                        <strong>{{ $displayName }}</strong>
                        <small>{{ $displayEmail }}</small>
                    </div>
                    <div class="avatar">{{ $initial ?: 'P' }}</div>
                </div>
            </div>
        </header>

        <main class="grid">
            @if(session('success'))
                <div class="alert success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert danger">{{ session('error') }}</div>
            @endif

            @if($errors->any())
                <div class="alert danger">{{ $errors->first() }}</div>
            @endif

            @yield('content')
        </main>
    </div>

    @include('provider.partials.bottom-nav')
    <script>
        window.ZYGA_PROVIDER_APP = {
            page: @json(trim($__env->yieldContent('page-key')) !== '' ? $__env->yieldContent('page-key') : 'dashboard'),
            apiBaseUrl: @json(rtrim((string) env('URL_BASE_API', 'http://127.0.0.1:8000'), '/')),
            token: @json(session('api_token', '')),
            csrfToken: @json(csrf_token()),
            sessionUser: @json($sessionUser),
        };
    </script>

    @stack('page_scripts')
</body>
</html>
