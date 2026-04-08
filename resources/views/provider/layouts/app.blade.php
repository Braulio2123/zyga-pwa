<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ZYGA Provider')</title>
    <link rel="stylesheet" href="{{ asset('css/provider-portal.css') }}">
    <style>
        :root {
            --bg: #081224;
            --bg-soft: #0f1b33;
            --panel: #ffffff;
            --panel-alt: #f7f8fc;
            --text: #0f172a;
            --muted: #64748b;
            --border: #e2e8f0;
            --brand: #f59e0b;
            --brand-dark: #d97706;
            --accent: #172554;
            --success-bg: #ecfdf5;
            --success-text: #047857;
            --danger-bg: #fef2f2;
            --danger-text: #b91c1c;
            --warning-bg: #fff7ed;
            --warning-text: #c2410c;
            --info-bg: #eff6ff;
            --info-text: #1d4ed8;
            --shadow: 0 20px 40px rgba(15, 23, 42, 0.12);
        }

        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            color: var(--text);
            background:
                radial-gradient(circle at top left, rgba(245, 158, 11, 0.18), transparent 28%),
                linear-gradient(180deg, #09111f 0%, #0b1630 100%);
            min-height: 100vh;
        }
        a { color: inherit; text-decoration: none; }
        .provider-shell {
            max-width: 1280px;
            margin: 0 auto;
            padding: 24px 20px 110px;
        }
        .provider-topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            background: linear-gradient(135deg, rgba(9, 17, 31, 0.88), rgba(15, 27, 51, 0.96));
            color: #fff;
            border: 1px solid rgba(255,255,255,.08);
            box-shadow: 0 16px 40px rgba(0,0,0,.18);
            border-radius: 24px;
            padding: 22px 24px;
            margin-bottom: 22px;
            backdrop-filter: blur(10px);
        }
        .provider-eyebrow, .hero-kicker, .dashboard-card__eyebrow, .provider-hero__eyebrow {
            margin: 0 0 6px;
            font-size: .78rem;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: #fbbf24;
            font-weight: 700;
        }
        .provider-page-title, .provider-hero__title, .section-head h3, .dashboard-card h3 {
            margin: 0;
            font-weight: 800;
            line-height: 1.05;
        }
        .provider-page-title { font-size: 1.65rem; }
        .provider-topbar-right { display: flex; align-items: center; gap: 14px; }
        .provider-user-meta { text-align: right; }
        .provider-user-meta strong { display: block; font-size: .98rem; }
        .provider-user-meta small { color: rgba(255,255,255,.72); }
        .provider-avatar {
            width: 48px; height: 48px; border-radius: 50%;
            display: grid; place-items: center;
            background: linear-gradient(135deg, #f59e0b, #f97316);
            color: #fff; font-weight: 800; font-size: 1.05rem;
            box-shadow: 0 10px 22px rgba(245,158,11,.35);
        }
        .provider-main { display: grid; gap: 18px; }
        .alert {
            border-radius: 18px; padding: 14px 16px; border: 1px solid transparent;
            box-shadow: 0 10px 24px rgba(15,23,42,.06);
        }
        .alert.success { background: var(--success-bg); color: var(--success-text); border-color: #a7f3d0; }
        .alert.danger { background: var(--danger-bg); color: var(--danger-text); border-color: #fecaca; }
        .hero-card, .section-card, .dashboard-card, .kpi-card, .provider-hero, .locked-module {
            background: var(--panel);
            border: 1px solid rgba(226,232,240,.9);
            border-radius: 24px;
            box-shadow: var(--shadow);
        }
        .hero-card, .section-card, .dashboard-card, .locked-module { padding: 24px; }
        .provider-hero {
            overflow: hidden;
            position: relative;
            padding: 30px;
            background:
                radial-gradient(circle at top right, rgba(245,158,11,.12), transparent 28%),
                linear-gradient(135deg, #ffffff, #f8fafc);
        }
        .provider-hero-lg {
            display: grid;
            grid-template-columns: 1.35fr .9fr;
            gap: 18px;
            align-items: stretch;
        }
        .provider-hero__text, .muted, .empty-state p, .section-head p, .list-card p, .request-item p, .card-subtext {
            color: var(--muted);
            line-height: 1.55;
            margin: 0;
        }
        .provider-hero__title { font-size: 2rem; margin-bottom: 12px; }
        .provider-hero__content { display: grid; gap: 16px; }
        .provider-hero__panel {
            background: linear-gradient(160deg, #0f1b33, #12264b);
            color: #fff;
            border-radius: 22px;
            padding: 22px;
            display: grid;
            gap: 12px;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            align-content: start;
        }
        .mini-stat {
            background: rgba(255,255,255,.08);
            border: 1px solid rgba(255,255,255,.08);
            border-radius: 18px;
            padding: 14px;
        }
        .mini-stat span { display:block; color: rgba(255,255,255,.72); font-size: .85rem; margin-bottom: 6px; }
        .mini-stat strong { font-size: 1.45rem; }
        .provider-hero__badges, .hero-stats, .hero-card, .section-head, .list-card-grid, .cta-row, .dashboard-kpis, .provider-dashboard-grid, .split-grid, .profile-summary-grid { display: flex; gap: 12px; }
        .provider-hero__badges, .hero-stats, .cta-row { flex-wrap: wrap; }
        .status-chip {
            display: inline-flex; align-items: center; gap: 8px;
            border-radius: 999px; padding: 8px 12px; font-size: .85rem; font-weight: 700;
            background: var(--panel-alt); color: var(--accent); border: 1px solid #dbeafe;
        }
        .status-chip.success { background: var(--success-bg); color: var(--success-text); border-color: #a7f3d0; }
        .status-chip.warning { background: var(--warning-bg); color: var(--warning-text); border-color: #fdba74; }
        .status-chip.info { background: var(--info-bg); color: var(--info-text); border-color: #bfdbfe; }
        .status-chip.dark { background: #0f1b33; color: #fff; border-color: #0f1b33; }
        .btn-primary, .btn-secondary, .btn-ghost {
            border: 0; cursor: pointer; border-radius: 14px; padding: 12px 16px; font-weight: 800;
            font-size: .95rem; display: inline-flex; align-items: center; justify-content: center;
            transition: .2s ease; text-decoration: none;
        }
        .btn-primary { background: linear-gradient(135deg, var(--brand), #f97316); color: #fff; box-shadow: 0 14px 26px rgba(245,158,11,.26); }
        .btn-primary:hover { transform: translateY(-1px); }
        .btn-secondary { background: #fff; color: var(--accent); border: 1px solid var(--border); }
        .btn-ghost { background: transparent; color: var(--accent); border: 1px dashed #cbd5e1; }
        .btn-sm { padding: 10px 12px; font-size: .86rem; }
        .w-full { width: 100%; }
        .dashboard-kpis {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }
        .kpi-card {
            padding: 20px; display:grid; gap:8px;
            background: linear-gradient(180deg, #ffffff, #f8fafc);
        }
        .kpi-card__label { color: var(--muted); font-size: .9rem; }
        .kpi-card__value { font-size: 2rem; font-weight: 800; color: var(--accent); line-height: 1; }
        .kpi-card__hint { color: #64748b; font-size: .85rem; }
        .provider-dashboard-grid {
            display: grid;
            grid-template-columns: 1.35fr .85fr;
            align-items: start;
        }
        .dashboard-card--wide { grid-column: 1 / 2; }
        .dashboard-card__head, .section-head, .hero-card { align-items: center; justify-content: space-between; }
        .text-link { color: var(--brand-dark); font-weight: 700; }
        .info-stack, .stack-list, .request-list { display: grid; gap: 12px; }
        .info-row {
            display: flex; justify-content: space-between; align-items: center; gap: 12px;
            padding: 12px 0; border-bottom: 1px solid var(--border);
        }
        .info-row:last-child { border-bottom: 0; }
        .empty-state {
            border: 1px dashed #cbd5e1; border-radius: 20px; padding: 22px; background: var(--panel-alt);
        }
        .empty-state h4 { margin: 0 0 8px; }
        .request-item, .list-card {
            border: 1px solid var(--border); border-radius: 20px; padding: 16px 18px; background: #fff;
            box-shadow: 0 10px 20px rgba(15,23,42,.04);
        }
        .request-item { display: flex; justify-content: space-between; gap: 16px; align-items: center; }
        .request-item__main, .request-item__side { display: grid; gap: 8px; }
        .request-item__main h4, .list-card h4 { margin: 0; font-size: 1rem; }
        .list-card small, .request-item small, .helper-text { color: var(--muted); }
        .split-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .profile-summary-grid { display: grid; grid-template-columns: repeat(3, minmax(0,1fr)); }
        .summary-card {
            padding: 18px; border-radius: 20px; border: 1px solid var(--border); background: var(--panel-alt);
        }
        .summary-card strong { display:block; font-size: 1.3rem; margin-top: 8px; color: var(--accent); }
        .form-grid { display: grid; grid-template-columns: repeat(2, minmax(0,1fr)); gap: 14px; }
        .form-field { display: grid; gap: 8px; }
        .form-field.full { grid-column: 1 / -1; }
        .label { font-size: .92rem; font-weight: 700; color: var(--accent); }
        input, select, textarea {
            width: 100%; border: 1px solid #d5dce8; background: #fff; color: var(--text);
            border-radius: 14px; padding: 13px 14px; font-size: .95rem; outline: 0;
        }
        input:focus, select:focus, textarea:focus { border-color: #93c5fd; box-shadow: 0 0 0 4px rgba(147,197,253,.18); }
        .inline-form {
            display: flex; flex-wrap: wrap; gap: 10px; align-items: center;
        }
        .locked-module {
            padding: 26px;
            background: linear-gradient(135deg, #fff7ed, #fffbeb);
            border-color: #fed7aa;
        }
        .locked-module h3 { margin: 0 0 10px; }
        .locked-module p { margin: 0 0 16px; color: #9a3412; }
        .helper-box {
            border-radius: 18px; background: #f8fafc; border: 1px solid var(--border); padding: 16px 18px;
        }
        .provider-bottom-nav {
            position: fixed; left: 50%; bottom: 18px; transform: translateX(-50%);
            width: min(760px, calc(100% - 24px));
            background: rgba(9,17,31,.92);
            backdrop-filter: blur(14px);
            border: 1px solid rgba(255,255,255,.08);
            border-radius: 22px; padding: 10px; display: grid; grid-template-columns: repeat(6, 1fr);
            gap: 8px; box-shadow: 0 16px 34px rgba(0,0,0,.22); z-index: 50;
        }
        .provider-bottom-nav a {
            color: rgba(255,255,255,.75); text-align: center; padding: 10px 8px; border-radius: 16px;
            font-size: .86rem; font-weight: 700;
        }
        .provider-bottom-nav a.active {
            background: linear-gradient(135deg, rgba(245,158,11,.18), rgba(249,115,22,.18));
            color: #fff; border: 1px solid rgba(251,191,36,.25);
        }
        .tableish { display:grid; gap: 10px; }
        .tableish-row {
            display:grid; grid-template-columns: 1.1fr 1fr .8fr auto; gap: 10px; align-items: center;
            border: 1px solid var(--border); border-radius: 18px; padding: 14px; background: #fff;
        }

        @media (max-width: 1024px) {
            .dashboard-kpis, .provider-dashboard-grid, .split-grid, .profile-summary-grid, .provider-hero-lg, .form-grid {
                grid-template-columns: 1fr;
            }
            .provider-hero__panel { grid-template-columns: repeat(2, minmax(0,1fr)); }
        }
        @media (max-width: 768px) {
            .provider-shell { padding: 16px 14px 110px; }
            .provider-topbar, .hero-card, .section-head, .request-item, .tableish-row { flex-direction: column; align-items: stretch; }
            .provider-topbar-right { justify-content: space-between; }
            .provider-bottom-nav { grid-template-columns: repeat(3, 1fr); }
            .provider-page-title { font-size: 1.35rem; }
            .provider-hero__title { font-size: 1.6rem; }
            .request-item { align-items: stretch; }
        }
    </style>
</head>
<body>
    @php
        $sessionUser = session('user', []);
        $userName = $sessionUser['name'] ?? 'Proveedor';
        $userEmail = $sessionUser['email'] ?? '';
        $avatarLetter = strtoupper(substr($userName ?: $userEmail, 0, 1));
    @endphp

    <div class="provider-shell">
        <header class="provider-topbar">
            <div>
                <p class="provider-eyebrow">Portal de proveedor</p>
                <h1 class="provider-page-title">@yield('page-title', 'Panel')</h1>
            </div>

            <div class="provider-topbar-right">
                <div class="provider-user-meta">
                    <strong>{{ $userName }}</strong>
                    <small>{{ $userEmail }}</small>
                </div>

                <div class="provider-avatar">{{ $avatarLetter }}</div>
            </div>
        </header>

        <main class="provider-main">
            @if(session('success'))
                <div class="alert success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert danger">{{ session('error') }}</div>
            @endif

            @if($errors->any())
                <div class="alert danger">
                    <strong>Hay errores en el formulario:</strong>
                    <ul style="margin:8px 0 0 18px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>

        @include('provider.partials.bottom-nav')
    </div>
</body>
</html>
