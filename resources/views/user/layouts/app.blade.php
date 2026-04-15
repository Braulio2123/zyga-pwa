<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('partials.pwa-meta')

    <title>{{ $pageTitle ?? 'ZYGA Cliente' }}</title>

    <link rel="stylesheet" href="{{ asset('css/user-client-portal.css') }}">
    @stack('page_styles')

    <script>
        (function () {
            try {
                if (window.innerWidth >= 1024) {
                    if (localStorage.getItem('zyga-client-sidebar') === 'collapsed') {
                        document.documentElement.classList.add('client-sidebar-collapsed');
                    }

                    if (localStorage.getItem('zyga-client-top-actions') === 'collapsed') {
                        document.documentElement.classList.add('client-top-actions-collapsed');
                    }
                }
            } catch (e) {}
        })();
    </script>
</head>
<body class="client-body" data-page="{{ $pageKey ?? 'dashboard' }}">
    @php
        $resolvedUser = is_array($sessionUser ?? null) ? $sessionUser : [];

        $rawName = trim((string) ($resolvedUser['name'] ?? ''));
        $userEmail = trim((string) ($resolvedUser['email'] ?? 'cliente@zyga.com'));

        $displayName = $rawName !== '' && strtolower($rawName) !== strtolower($userEmail)
            ? $rawName
            : 'Cliente';

        $firstNameParts = preg_split('/\s+/', trim($displayName));
        $userFirstName = $firstNameParts[0] ?? 'Cliente';

        $avatarBase = $rawName !== '' ? $rawName : ($userEmail !== '' ? $userEmail : 'C');
        $avatar = strtoupper(substr(trim($avatarBase), 0, 1));

        $currentPage = $pageKey ?? 'dashboard';
    @endphp

    <div class="client-shell">
        @include('user.partials.bottom-nav')

        <header class="client-topbar">
            <div class="client-topbar__surface">
                <div class="client-topbar__identity">
                    <div class="client-brand-mark">ZY</div>

                    <div class="client-topbar__copy">
                        <p class="client-topbar__eyebrow">Portal del cliente</p>
                        <h1>{{ $pageHeading ?? 'Portal cliente' }}</h1>
                        <p class="client-topbar__meta">Hola, {{ $userFirstName }}</p>
                        <p class="client-topbar__submeta">{{ $userEmail }}</p>
                    </div>
                </div>

                <div class="client-topbar__tools">
                    <button
                        type="button"
                        id="topActionsToggleButton"
                        class="top-actions-toggle desktop-only"
                        aria-pressed="false"
                        title="Ocultar accesos rápidos"
                    >
                        <span class="top-actions-toggle__icon">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M5 7h14M5 12h14M5 17h14" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round"/>
                            </svg>
                        </span>
                    </button>

                    <button
                        type="button"
                        id="sidebarToggleButton"
                        class="sidebar-toggle desktop-only"
                        aria-pressed="false"
                        title="Contraer menú lateral"
                    >
                        <span class="sidebar-toggle__icon sidebar-toggle__icon--open">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M4 7h16M4 12h16M4 17h16" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round"/>
                            </svg>
                        </span>

                        <span class="sidebar-toggle__icon sidebar-toggle__icon--close">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M15 6l-6 6 6 6" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                    </button>

                    <div class="avatar-circle" title="{{ $displayName }}">{{ $avatar }}</div>
                </div>
            </div>

            <details class="client-quick-menu mobile-only">
                <summary class="client-quick-menu__summary">
                    <span>Accesos rápidos</span>
                    <span class="client-quick-menu__chevron">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M7 10l5 5 5-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                </summary>

                <div class="client-quick-menu__content">
                    @if ($currentPage !== 'request')
                        <a href="{{ route('user.solicitud') }}" class="button button--primary button--compact">
                            Nueva solicitud
                        </a>
                    @endif

                    @if ($currentPage !== 'active')
                        <a href="{{ route('user.activo') }}" class="button button--secondary button--compact">
                            Servicio activo
                        </a>
                    @endif

                    @if ($currentPage !== 'payments')
                        <a href="{{ route('user.pagos') }}" class="button button--ghost button--compact">
                            Pagos
                        </a>
                    @endif

                    <button
                        type="button"
                        class="button button--ghost button--compact"
                        onclick="event.preventDefault(); document.getElementById('clientLogoutForm').submit();"
                    >
                        Cerrar sesión
                    </button>
                </div>
            </details>

            <div class="client-topbar__actions desktop-only" id="clientTopbarActions">
                @if ($currentPage !== 'request')
                    <a href="{{ route('user.solicitud') }}" class="button button--primary button--compact">
                        Nueva solicitud
                    </a>
                @endif

                @if ($currentPage !== 'active')
                    <a href="{{ route('user.activo') }}" class="button button--secondary button--compact">
                        Servicio activo
                    </a>
                @endif

                @if ($currentPage !== 'payments')
                    <a href="{{ route('user.pagos') }}" class="button button--ghost button--compact">
                        Pagos
                    </a>
                @endif

                <button
                    type="button"
                    class="button button--ghost button--compact"
                    onclick="event.preventDefault(); document.getElementById('clientLogoutForm').submit();"
                >
                    Cerrar sesión
                </button>
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const html = document.documentElement;
            const body = document.body;

            const sidebarButton = document.getElementById('sidebarToggleButton');
            const topActionsButton = document.getElementById('topActionsToggleButton');

            const syncInitialDesktopState = () => {
                if (html.classList.contains('client-sidebar-collapsed')) {
                    body.classList.add('client-sidebar-collapsed');
                } else {
                    body.classList.remove('client-sidebar-collapsed');
                }

                if (html.classList.contains('client-top-actions-collapsed')) {
                    body.classList.add('client-top-actions-collapsed');
                } else {
                    body.classList.remove('client-top-actions-collapsed');
                }
            };

            const syncSidebarButton = () => {
                if (!sidebarButton) return;

                const collapsed = body.classList.contains('client-sidebar-collapsed');

                sidebarButton.setAttribute('aria-pressed', collapsed ? 'true' : 'false');
                sidebarButton.setAttribute('title', collapsed ? 'Expandir menú lateral' : 'Contraer menú lateral');
            };

            const syncTopActionsButton = () => {
                if (!topActionsButton) return;

                const collapsed = body.classList.contains('client-top-actions-collapsed');

                topActionsButton.setAttribute('aria-pressed', collapsed ? 'true' : 'false');
                topActionsButton.setAttribute('title', collapsed ? 'Mostrar accesos rápidos' : 'Ocultar accesos rápidos');
            };

            const applyDesktopStateFromStorage = () => {
                if (window.innerWidth < 1024) {
                    body.classList.remove('client-sidebar-collapsed');
                    body.classList.remove('client-top-actions-collapsed');
                    html.classList.remove('client-sidebar-collapsed');
                    html.classList.remove('client-top-actions-collapsed');
                    syncSidebarButton();
                    syncTopActionsButton();
                    return;
                }

                try {
                    const sidebarState = localStorage.getItem('zyga-client-sidebar');
                    const topActionsState = localStorage.getItem('zyga-client-top-actions');

                    if (sidebarState === 'collapsed') {
                        body.classList.add('client-sidebar-collapsed');
                        html.classList.add('client-sidebar-collapsed');
                    } else {
                        body.classList.remove('client-sidebar-collapsed');
                        html.classList.remove('client-sidebar-collapsed');
                    }

                    if (topActionsState === 'collapsed') {
                        body.classList.add('client-top-actions-collapsed');
                        html.classList.add('client-top-actions-collapsed');
                    } else {
                        body.classList.remove('client-top-actions-collapsed');
                        html.classList.remove('client-top-actions-collapsed');
                    }
                } catch (e) {}

                syncSidebarButton();
                syncTopActionsButton();
            };

            syncInitialDesktopState();
            syncSidebarButton();
            syncTopActionsButton();

            if (sidebarButton) {
                sidebarButton.addEventListener('click', function () {
                    if (window.innerWidth < 1024) return;

                    body.classList.toggle('client-sidebar-collapsed');
                    html.classList.toggle('client-sidebar-collapsed');

                    try {
                        localStorage.setItem(
                            'zyga-client-sidebar',
                            body.classList.contains('client-sidebar-collapsed') ? 'collapsed' : 'expanded'
                        );
                    } catch (e) {}

                    syncSidebarButton();
                });
            }

            if (topActionsButton) {
                topActionsButton.addEventListener('click', function () {
                    if (window.innerWidth < 1024) return;

                    body.classList.toggle('client-top-actions-collapsed');
                    html.classList.toggle('client-top-actions-collapsed');

                    try {
                        localStorage.setItem(
                            'zyga-client-top-actions',
                            body.classList.contains('client-top-actions-collapsed') ? 'collapsed' : 'expanded'
                        );
                    } catch (e) {}

                    syncTopActionsButton();
                });
            }

            window.addEventListener('resize', applyDesktopStateFromStorage);
        });
    </script>

    <script src="{{ asset('js/user-client-portal.js') }}"></script>
    @stack('page_scripts')
    @include('partials.pwa-register')
</body>
</html>
