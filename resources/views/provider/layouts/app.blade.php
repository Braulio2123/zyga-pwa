<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('partials.pwa-meta')

    <title>@yield('title', 'ZYGA | Proveedor')</title>

    <link rel="stylesheet" href="{{ asset('css/provider-portal.css') }}">
    @stack('page_styles')

    <script>
        (function () {
            try {
                if (window.innerWidth >= 1024) {
                    if (localStorage.getItem('zyga-provider-sidebar') === 'collapsed') {
                        document.documentElement.classList.add('provider-sidebar-collapsed');
                    }

                    if (localStorage.getItem('zyga-provider-top-actions') === 'collapsed') {
                        document.documentElement.classList.add('provider-top-actions-collapsed');
                    }
                }
            } catch (e) {}
        })();
    </script>
</head>
<body class="provider-body" data-page="@yield('page-key', 'dashboard')">
    @php
        $resolvedUser = is_array(session('user')) ? session('user') : [];

        $rawName = trim((string) ($resolvedUser['name'] ?? ''));
        $userEmail = trim((string) ($resolvedUser['email'] ?? 'proveedor@zyga.com'));

        $displayName = $rawName !== '' && strtolower($rawName) !== strtolower($userEmail)
            ? $rawName
            : 'Proveedor';

        $firstNameParts = preg_split('/\s+/', trim($displayName));
        $userFirstName = $firstNameParts[0] ?? 'Proveedor';

        $avatarBase = $rawName !== '' ? $rawName : ($userEmail !== '' ? $userEmail : 'P');
        $avatar = strtoupper(substr(trim($avatarBase), 0, 1));

        $currentPage = trim($__env->yieldContent('page-key')) !== '' ? trim($__env->yieldContent('page-key')) : 'dashboard';

        if ($currentPage === 'dashboard' && request()->routeIs('provider.perfil*')) {
            $currentPage = 'perfil';
        } elseif ($currentPage === 'dashboard' && request()->routeIs('provider.servicios*')) {
            $currentPage = 'servicios';
        } elseif ($currentPage === 'dashboard' && request()->routeIs('provider.horarios*')) {
            $currentPage = 'horarios';
        } elseif ($currentPage === 'dashboard' && request()->routeIs('provider.documentos*')) {
            $currentPage = 'documentos';
        } elseif ($currentPage === 'dashboard' && request()->routeIs('provider.asistencias*')) {
            $currentPage = 'asistencias';
        }

        $navItems = [
            [
                'key' => 'dashboard',
                'route' => route('provider.dashboard'),
                'label' => 'Inicio',
                'icon' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 10.8 12 3l9 7.8v9.2a1 1 0 0 1-1 1h-5.5v-6h-5v6H4a1 1 0 0 1-1-1v-9.2Z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg>',
            ],
            [
                'key' => 'perfil',
                'route' => route('provider.perfil'),
                'label' => 'Perfil',
                'icon' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M19 20a7 7 0 1 0-14 0M12 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>',
            ],
            [
                'key' => 'servicios',
                'route' => route('provider.servicios'),
                'label' => 'Servicios',
                'icon' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h10" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>',
            ],
            [
                'key' => 'horarios',
                'route' => route('provider.horarios'),
                'label' => 'Horarios',
                'icon' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M8 3v3M16 3v3M4 9h16M6 6h12a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2Z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>',
            ],
            [
                'key' => 'documentos',
                'route' => route('provider.documentos'),
                'label' => 'Documentos',
                'icon' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 3h7l5 5v13H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Zm7 0v5h5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg>',
            ],
            [
                'key' => 'asistencias',
                'route' => route('provider.asistencias'),
                'label' => 'Atención',
                'icon' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 21s-6.5-4.35-8.5-8.36C1.85 9.32 3.87 5.5 8.02 5.5c1.7 0 3.07.78 3.98 2.07.91-1.29 2.28-2.07 3.98-2.07 4.15 0 6.17 3.82 4.52 7.14C18.5 16.65 12 21 12 21Z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>',
            ],
        ];
    @endphp

    <div class="provider-shell">
        <aside class="provider-nav desktop-only" aria-label="Navegación principal del proveedor">
            <div class="provider-nav__brand">
                <div class="provider-nav__brand-head">
                    <div class="provider-nav__brand-mark">ZY</div>

                    <div class="provider-nav__brand-copy">
                        <strong class="provider-nav__brand-title">Centro del proveedor</strong>
                        <p class="provider-nav__brand-text">
                            Organiza tu cuenta, tu disponibilidad y tu atención desde un solo lugar.
                        </p>
                    </div>
                </div>
            </div>

            <div class="provider-nav__items">
                @foreach($navItems as $item)
                    <a
                        href="{{ $item['route'] }}"
                        class="provider-nav__item {{ $currentPage === $item['key'] ? 'is-active' : '' }}"
                        aria-current="{{ $currentPage === $item['key'] ? 'page' : 'false' }}"
                        title="{{ $item['label'] }}"
                    >
                        <span class="provider-nav__icon">{!! $item['icon'] !!}</span>
                        <span class="provider-nav__label">{{ $item['label'] }}</span>
                    </a>
                @endforeach
            </div>

            <div class="provider-nav__footer">
                <a href="{{ route('provider.asistencias') }}" class="provider-nav__footer-link">
                    Ver solicitudes
                </a>

                <button
                    type="button"
                    class="provider-nav__footer-link provider-nav__logout"
                    onclick="event.preventDefault(); document.getElementById('providerLogoutForm').submit();"
                >
                    Cerrar sesión
                </button>
            </div>
        </aside>

        <div class="provider-app">
            @include('provider.partials.bottom-nav')

            <header class="provider-topbar">
                <div class="provider-topbar__surface">
                    <div class="provider-topbar__identity">
                        <div class="provider-brand-mark">ZY</div>

                        <div class="provider-topbar__copy">
                            <p class="provider-topbar__eyebrow">Portal del proveedor</p>
                            <h1>@yield('page-title', 'Portal del proveedor')</h1>
                            <p class="provider-topbar__meta">Hola, {{ $userFirstName }}</p>
                            <p class="provider-topbar__submeta">{{ $userEmail }}</p>
                        </div>
                    </div>

                    <div class="provider-topbar__tools">
                        <button
                            type="button"
                            id="providerTopActionsToggleButton"
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
                            id="providerSidebarToggleButton"
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

                <details class="provider-quick-menu mobile-only">
                    <summary class="provider-quick-menu__summary">
                        <span>Accesos rápidos</span>
                        <span class="provider-quick-menu__chevron">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M7 10l5 5 5-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                    </summary>

                    <div class="provider-quick-menu__content">
                        @if ($currentPage !== 'asistencias')
                            <a href="{{ route('provider.asistencias') }}" class="button button--primary button--compact">
                                Ver solicitudes
                            </a>
                        @endif

                        @if ($currentPage !== 'perfil')
                            <a href="{{ route('provider.perfil') }}" class="button button--secondary button--compact">
                                Mi perfil
                            </a>
                        @endif

                        @if ($currentPage !== 'horarios')
                            <a href="{{ route('provider.horarios') }}" class="button button--ghost button--compact">
                                Mis horarios
                            </a>
                        @endif

                        <button
                            type="button"
                            class="button button--ghost button--compact"
                            onclick="event.preventDefault(); document.getElementById('providerLogoutForm').submit();"
                        >
                            Cerrar sesión
                        </button>
                    </div>
                </details>

                <div class="provider-topbar__actions desktop-only" id="providerTopbarActions">
                    @if ($currentPage !== 'asistencias')
                        <a href="{{ route('provider.asistencias') }}" class="button button--primary button--compact">
                            Ver solicitudes
                        </a>
                    @endif

                    @if ($currentPage !== 'perfil')
                        <a href="{{ route('provider.perfil') }}" class="button button--secondary button--compact">
                            Mi perfil
                        </a>
                    @endif

                    @if ($currentPage !== 'horarios')
                        <a href="{{ route('provider.horarios') }}" class="button button--ghost button--compact">
                            Mis horarios
                        </a>
                    @endif

                    <button
                        type="button"
                        class="button button--ghost button--compact"
                        onclick="event.preventDefault(); document.getElementById('providerLogoutForm').submit();"
                    >
                        Cerrar sesión
                    </button>
                </div>
            </header>

            <main class="provider-main">
                @if (session('success'))
                    <div class="flash flash--success">{{ session('success') }}</div>
                @endif

                @if (session('error'))
                    <div class="flash flash--error">{{ session('error') }}</div>
                @endif

                @if ($errors->any())
                    <div class="flash flash--error">{{ $errors->first() }}</div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <form id="providerLogoutForm" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>

    <div class="toast" id="providerAppToast" aria-live="polite" aria-atomic="true"></div>

    <script>
        window.ZYGA_PROVIDER_APP = {
            page: @json($currentPage),
            apiBaseUrl: @json(rtrim((string) env('URL_BASE_API', 'http://127.0.0.1:8000'), '/')),
            token: @json(session('api_token', '')),
            csrfToken: @json(csrf_token()),
            sessionUser: @json($resolvedUser),
            routes: {
                dashboard: @json(route('provider.dashboard')),
                perfil: @json(route('provider.perfil')),
                servicios: @json(route('provider.servicios')),
                horarios: @json(route('provider.horarios')),
                documentos: @json(route('provider.documentos')),
                asistencias: @json(route('provider.asistencias')),
                logout: @json(route('logout')),
            },
        };
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const html = document.documentElement;
            const body = document.body;

            const sidebarButton = document.getElementById('providerSidebarToggleButton');
            const topActionsButton = document.getElementById('providerTopActionsToggleButton');

            const syncInitialDesktopState = () => {
                if (html.classList.contains('provider-sidebar-collapsed')) {
                    body.classList.add('provider-sidebar-collapsed');
                } else {
                    body.classList.remove('provider-sidebar-collapsed');
                }

                if (html.classList.contains('provider-top-actions-collapsed')) {
                    body.classList.add('provider-top-actions-collapsed');
                } else {
                    body.classList.remove('provider-top-actions-collapsed');
                }
            };

            const syncSidebarButton = () => {
                if (!sidebarButton) return;

                const collapsed = body.classList.contains('provider-sidebar-collapsed');

                sidebarButton.setAttribute('aria-pressed', collapsed ? 'true' : 'false');
                sidebarButton.setAttribute('title', collapsed ? 'Expandir menú lateral' : 'Contraer menú lateral');
            };

            const syncTopActionsButton = () => {
                if (!topActionsButton) return;

                const collapsed = body.classList.contains('provider-top-actions-collapsed');

                topActionsButton.setAttribute('aria-pressed', collapsed ? 'true' : 'false');
                topActionsButton.setAttribute('title', collapsed ? 'Mostrar accesos rápidos' : 'Ocultar accesos rápidos');
            };

            const applyDesktopStateFromStorage = () => {
                if (window.innerWidth < 1024) {
                    body.classList.remove('provider-sidebar-collapsed');
                    body.classList.remove('provider-top-actions-collapsed');
                    html.classList.remove('provider-sidebar-collapsed');
                    html.classList.remove('provider-top-actions-collapsed');
                    syncSidebarButton();
                    syncTopActionsButton();
                    return;
                }

                try {
                    const sidebarState = localStorage.getItem('zyga-provider-sidebar');
                    const topActionsState = localStorage.getItem('zyga-provider-top-actions');

                    if (sidebarState === 'collapsed') {
                        body.classList.add('provider-sidebar-collapsed');
                        html.classList.add('provider-sidebar-collapsed');
                    } else {
                        body.classList.remove('provider-sidebar-collapsed');
                        html.classList.remove('provider-sidebar-collapsed');
                    }

                    if (topActionsState === 'collapsed') {
                        body.classList.add('provider-top-actions-collapsed');
                        html.classList.add('provider-top-actions-collapsed');
                    } else {
                        body.classList.remove('provider-top-actions-collapsed');
                        html.classList.remove('provider-top-actions-collapsed');
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

                    body.classList.toggle('provider-sidebar-collapsed');
                    html.classList.toggle('provider-sidebar-collapsed');

                    try {
                        localStorage.setItem(
                            'zyga-provider-sidebar',
                            body.classList.contains('provider-sidebar-collapsed') ? 'collapsed' : 'expanded'
                        );
                    } catch (e) {}

                    syncSidebarButton();
                });
            }

            if (topActionsButton) {
                topActionsButton.addEventListener('click', function () {
                    if (window.innerWidth < 1024) return;

                    body.classList.toggle('provider-top-actions-collapsed');
                    html.classList.toggle('provider-top-actions-collapsed');

                    try {
                        localStorage.setItem(
                            'zyga-provider-top-actions',
                            body.classList.contains('provider-top-actions-collapsed') ? 'collapsed' : 'expanded'
                        );
                    } catch (e) {}

                    syncTopActionsButton();
                });
            }

            applyDesktopStateFromStorage();
            window.addEventListener('resize', applyDesktopStateFromStorage);
        });
    </script>

    @stack('page_scripts')
    @include('partials.pwa-register')
</body>
</html>
