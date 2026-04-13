<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('partials.pwa-meta')

    <title>{{ $pageTitle ?? 'ZYGA Cliente' }}</title>

    <link rel="stylesheet" href="{{ asset('css/user-client-portal.css') }}">
</head>
<body class="client-body" data-page="{{ $pageKey ?? 'dashboard' }}">
    @php
        $resolvedUser = is_array($sessionUser ?? null) ? $sessionUser : [];
        $userName = $resolvedUser['name'] ?? $resolvedUser['email'] ?? 'Cliente';
        $userEmail = $resolvedUser['email'] ?? 'cliente@zyga.com';
        $avatar = strtoupper(substr(trim($userName ?: $userEmail), 0, 1));
    @endphp

    <div class="client-shell">
        @include('user.partials.bottom-nav')

        <header class="client-topbar">
            <div class="client-topbar__copy">
                <div class="brand-stamp">ZYGA</div>
                <p class="client-topbar__eyebrow">Portal del cliente</p>
                <h1>{{ $pageHeading ?? 'Portal cliente' }}</h1>
                <p class="client-topbar__meta">{{ $userEmail }}</p>
            </div>

            <div class="client-topbar__actions">
                @if (($pageKey ?? '') !== 'request')
                    <a href="{{ route('user.solicitud') }}" class="ghost-link">Nueva solicitud</a>
                @endif

                @if (($pageKey ?? '') !== 'active')
                    <a href="{{ route('user.activo') }}" class="ghost-link">Servicio activo</a>
                @endif

                <button
                    type="button"
                    class="ghost-link"
                    onclick="event.preventDefault(); document.getElementById('clientLogoutForm').submit();"
                >
                    Cerrar sesión
                </button>

                <div class="avatar-circle" title="{{ $userName }}">{{ $avatar }}</div>
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
    </div>

    <form id="clientLogoutForm" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>

    <div class="toast" id="appToast" aria-live="polite" aria-atomic="true"></div>

    <script>
        window.ZYGA_CLIENT_APP = {
            page: @json($pageKey ?? 'dashboard'),
            apiBaseUrl: @json($apiBaseUrl ?? ''),
            token: @json($apiToken ?? ''),
            csrfToken: @json(csrf_token()),
            sessionUser: @json($resolvedUser),
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
