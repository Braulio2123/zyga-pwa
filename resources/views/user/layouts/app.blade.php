<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    @include('partials.pwa-meta')
    <title>{{ $pageTitle ?? 'ZYGA Cliente' }}</title>
    <link rel="stylesheet" href="{{ asset('css/user-client-portal.css') }}">
</head>
<body class="client-body">
    @php
        $userName = is_array($sessionUser ?? null) ? ($sessionUser['name'] ?? $sessionUser['email'] ?? 'Cliente') : 'Cliente';
        $userEmail = is_array($sessionUser ?? null) ? ($sessionUser['email'] ?? 'cliente@zyga.com') : 'cliente@zyga.com';
        $avatar = strtoupper(substr($userName ?: $userEmail, 0, 1));
    @endphp

    <div class="client-shell">
        <header class="client-topbar">
            <div class="client-topbar__copy">
                <div class="brand-stamp">ZYGA</div>
                <p class="client-topbar__eyebrow">Experiencia cliente</p>
                <h1>{{ $pageHeading ?? 'Portal cliente' }}</h1>
                <p class="client-topbar__meta">{{ $userEmail }}</p>
            </div>
            <div class="client-topbar__actions">
                <a href="{{ route('user.pagos') }}" class="ghost-link">Pagos</a>
                <div class="avatar-circle">{{ $avatar }}</div>
            </div>
        </header>

        <main class="client-main">
            @if (session('success'))
                <div class="flash flash--success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="flash flash--error">{{ session('error') }}</div>
            @endif

            @yield('content')
        </main>

        @include('user.partials.bottom-nav')
    </div>

    <div class="toast" id="appToast" aria-live="polite" aria-atomic="true"></div>

    <script>
        window.ZYGA_CLIENT_APP = {
            page: @json($pageKey ?? 'dashboard'),
            apiBaseUrl: @json($apiBaseUrl ?? ''),
            token: @json($apiToken ?? ''),
            sessionUser: @json($sessionUser ?? []),
            vehicleTypeOptions: @json($vehicleTypeOptions ?? []),
            routes: {
                dashboard: @json(route('user.dashboard')),
                request: @json(route('user.solicitud')),
                active: @json(route('user.activo')),
                history: @json(route('user.historial')),
                payments: @json(route('user.pagos')),
                account: @json(route('user.cuenta')),
                logout: @json(route('logout')),
            },
        };
    </script>
    <script src="{{ asset('js/user-client-portal.js') }}"></script>
    @include('partials.pwa-register')
</body>
</html>
