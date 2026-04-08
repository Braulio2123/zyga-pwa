<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>@yield('title', 'ZYGA Provider')</title>
    <style>
        :root {
            --bg: #081224;
            --bg-soft: #0f1b33;
            --panel: #ffffff;
            --panel-alt: #f8fafc;
            --text: #0f172a;
            --muted: #64748b;
            --border: #e2e8f0;
            --brand: #f59e0b;
            --brand-dark: #d97706;
            --accent: #173b8a;
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
            color: var(--text);
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background:
                radial-gradient(circle at top left, rgba(245, 158, 11, 0.18), transparent 28%),
                linear-gradient(180deg, #09111f 0%, #0b1630 100%);
            min-height: 100vh;
        }
        a { color: inherit; text-decoration: none; }
        .shell { max-width: 1280px; margin: 0 auto; padding: 24px 20px 110px; }
        .topbar {
            display: flex; justify-content: space-between; align-items: center; gap: 16px;
            color: #fff; margin-bottom: 22px; padding: 22px 24px; border-radius: 24px;
            background: linear-gradient(135deg, rgba(9,17,31,.88), rgba(15,27,51,.96));
            border: 1px solid rgba(255,255,255,.08); box-shadow: 0 16px 40px rgba(0,0,0,.18);
        }
        .eyebrow { margin: 0 0 6px; font-size: .78rem; letter-spacing: .12em; text-transform: uppercase; color: #fbbf24; font-weight: 800; }
        .page-title { margin: 0; font-size: 1.65rem; line-height: 1.05; }
        .muted { color: var(--muted); }
        .topbar-right { display: flex; align-items: center; gap: 14px; }
        .user-meta { text-align: right; }
        .user-meta strong { display: block; }
        .user-meta small { color: rgba(255,255,255,.72); }
        .avatar {
            width: 48px; height: 48px; border-radius: 50%; display: grid; place-items: center;
            background: linear-gradient(135deg, #f59e0b, #f97316); color: #fff; font-weight: 800;
        }
        .grid { display: grid; gap: 18px; }
        .hero, .card, .kpi, .lockbox {
            background: var(--panel); border-radius: 24px; border: 1px solid rgba(226,232,240,.9); box-shadow: var(--shadow);
        }
        .hero, .card, .lockbox { padding: 24px; }
        .hero {
            padding: 30px;
            background: radial-gradient(circle at top right, rgba(245,158,11,.12), transparent 28%), linear-gradient(135deg, #ffffff, #f8fafc);
        }
        .hero-split, .two-col, .three-col, .kpi-grid { display: grid; gap: 16px; }
        .hero-split { grid-template-columns: 1.25fr .9fr; }
        .two-col { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .three-col { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        .kpi-grid { grid-template-columns: repeat(4, minmax(0, 1fr)); }
        .hero-panel {
            background: linear-gradient(160deg, #0f1b33, #12264b); color: #fff; border-radius: 22px; padding: 22px; display: grid; gap: 12px;
        }
        .chip {
            display: inline-flex; align-items: center; gap: 8px; padding: 8px 12px; border-radius: 999px;
            font-size: .85rem; font-weight: 800; border: 1px solid #dbeafe; background: var(--panel-alt); color: var(--accent);
        }
        .chip.success { background: var(--success-bg); color: var(--success-text); border-color: #a7f3d0; }
        .chip.warning { background: var(--warning-bg); color: var(--warning-text); border-color: #fdba74; }
        .chip.info { background: var(--info-bg); color: var(--info-text); border-color: #bfdbfe; }
        .chip.dark { background: #0f1b33; color: #fff; border-color: #0f1b33; }
        .btn, .btn-outline, .btn-ghost {
            border: 0; cursor: pointer; border-radius: 14px; padding: 12px 16px; font-weight: 800; font-size: .95rem;
            display: inline-flex; align-items: center; justify-content: center; transition: .2s ease;
        }
        .btn { background: linear-gradient(135deg, var(--brand), #f97316); color: #fff; box-shadow: 0 14px 26px rgba(245,158,11,.26); }
        .btn-outline { background: #fff; color: var(--accent); border: 1px solid var(--border); }
        .btn-ghost { background: transparent; color: var(--accent); border: 1px dashed #cbd5e1; }
        .btn:hover, .btn-outline:hover, .btn-ghost:hover { transform: translateY(-1px); }
        .full { width: 100%; }
        .kpi { padding: 20px; background: linear-gradient(180deg, #ffffff, #f8fafc); }
        .kpi-label { color: var(--muted); font-size: .9rem; }
        .kpi-value { font-size: 2rem; font-weight: 900; color: var(--accent); line-height: 1; margin-top: 6px; }
        .kpi-hint { color: var(--muted); font-size: .85rem; margin-top: 8px; }
        .section-head { display: flex; justify-content: space-between; align-items: center; gap: 12px; margin-bottom: 14px; }
        .section-head h3, .card h3 { margin: 0; }
        .list, .stack { display: grid; gap: 12px; }
        .item {
            border: 1px solid var(--border); border-radius: 20px; padding: 16px 18px; background: #fff; box-shadow: 0 10px 20px rgba(15,23,42,.04);
        }
        .item-head { display: flex; justify-content: space-between; gap: 14px; align-items: flex-start; }
        .item h4 { margin: 0 0 6px; }
        .item p, .item small { margin: 0; color: var(--muted); }
        .empty {
            border: 1px dashed #cbd5e1; border-radius: 20px; padding: 22px; background: var(--panel-alt);
        }
        .empty h4 { margin: 0 0 8px; }
        .alert { border-radius: 18px; padding: 14px 16px; border: 1px solid transparent; }
        .alert.success { background: var(--success-bg); color: var(--success-text); border-color: #a7f3d0; }
        .alert.danger { background: var(--danger-bg); color: var(--danger-text); border-color: #fecaca; }
        .alert.warning { background: var(--warning-bg); color: var(--warning-text); border-color: #fdba74; }
        .helper { font-size: .9rem; color: var(--muted); }
        .summary { padding: 18px; border-radius: 20px; border: 1px solid var(--border); background: var(--panel-alt); }
        .summary strong { display: block; font-size: 1.35rem; margin-top: 8px; color: var(--accent); }
        .meta-grid { display: grid; grid-template-columns: repeat(2, minmax(0,1fr)); gap: 12px; }
        .meta-box { padding: 14px; border-radius: 18px; background: var(--panel-alt); border: 1px solid var(--border); }
        .meta-box span { display: block; font-size: .82rem; color: var(--muted); margin-bottom: 6px; }
        .form-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px; }
        .field { display: grid; gap: 8px; }
        .field.full { grid-column: 1 / -1; }
        .label { font-size: .92rem; font-weight: 700; }
        input, select {
            width: 100%; padding: 12px 14px; border-radius: 14px; border: 1px solid #cbd5e1; background: #fff; color: var(--text); font-size: .95rem;
        }
        .inline-form { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 12px; }
        .lockbox { background: linear-gradient(135deg, #fff7ed, #ffffff); }
        .checklist { display: grid; gap: 10px; }
        .check { display: flex; align-items: center; justify-content: space-between; gap: 12px; border: 1px solid var(--border); border-radius: 16px; padding: 12px 14px; background: #fff; }
        .timeline { display: grid; gap: 12px; }
        .timeline-entry { border-left: 4px solid #dbeafe; padding: 12px 14px; background: var(--panel-alt); border-radius: 16px; }
        .bottom-nav {
            position: fixed; left: 50%; bottom: 16px; transform: translateX(-50%); width: min(860px, calc(100% - 20px));
            background: rgba(9,17,31,.96); border: 1px solid rgba(255,255,255,.06); border-radius: 20px; display: grid; grid-template-columns: repeat(6, 1fr); gap: 8px; padding: 10px; z-index: 30;
            box-shadow: 0 20px 40px rgba(0,0,0,.28);
        }
        .bottom-nav a { color: rgba(255,255,255,.74); text-align: center; padding: 10px 8px; border-radius: 14px; font-size: .88rem; font-weight: 700; }
        .bottom-nav a.active { background: linear-gradient(135deg, #f59e0b, #f97316); color: #fff; }
        @media (max-width: 960px) {
            .hero-split, .two-col, .three-col, .kpi-grid, .meta-grid, .form-grid { grid-template-columns: 1fr; }
            .topbar { flex-direction: column; align-items: stretch; }
            .topbar-right { justify-content: space-between; }
            .bottom-nav { grid-template-columns: repeat(3, 1fr); }
        }
    </style>
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
            <div>
                <p class="eyebrow">ZYGA · Provider portal</p>
                <h1 class="page-title">@yield('page-title', 'Portal provider')</h1>
                <p class="helper" style="color: rgba(255,255,255,.75); margin:8px 0 0;">Consola operativa conectada al flujo oficial de <strong>assistance_requests</strong>.</p>
            </div>
            <div class="topbar-right">
                <div class="user-meta">
                    <strong>{{ $displayName }}</strong>
                    <small>{{ $displayEmail }}</small>
                </div>
                <div class="avatar">{{ $initial ?: 'P' }}</div>
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
                <div class="alert danger">
                    {{ $errors->first() }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    @include('provider.partials.bottom-nav')
</body>
</html>
