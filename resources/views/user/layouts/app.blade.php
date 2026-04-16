<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('partials.pwa-meta')

    <title>{{ $pageTitle ?? 'ZYGA Cliente' }}</title>

    <link rel="stylesheet" href="{{ asset('css/user-client-portal.css') }}">

    <style>
        .client-alerts {
            position: relative;
        }

        .client-alerts > summary {
            list-style: none;
        }

        .client-alerts > summary::-webkit-details-marker {
            display: none;
        }

        .client-alerts__button {
            position: relative;
            width: 48px;
            height: 48px;
            padding: 0;
            border: 1px solid rgba(12, 26, 63, 0.08);
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.92);
            color: var(--zyga-blue);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.08);
            transition: transform var(--transition), box-shadow var(--transition);
        }

        .client-alerts__button:hover {
            transform: translateY(-1px);
            box-shadow: 0 14px 28px rgba(15, 23, 42, 0.12);
        }

        .client-alerts__button svg {
            width: 22px;
            height: 22px;
        }

        .client-alerts__badge {
            position: absolute;
            top: 7px;
            right: 6px;
            min-width: 18px;
            height: 18px;
            padding: 0 5px;
            border-radius: 999px;
            background: #dc2626;
            color: #fff;
            font-size: 0.68rem;
            font-weight: 800;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 6px 14px rgba(220, 38, 38, 0.28);
        }

        .client-alerts__panel {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            width: min(360px, calc(100vw - 32px));
            max-height: min(68vh, 520px);
            overflow: auto;
            padding: 14px;
            border-radius: 22px;
            background: rgba(255, 255, 255, 0.98);
            border: 1px solid rgba(12, 26, 63, 0.08);
            box-shadow: 0 24px 56px rgba(15, 23, 42, 0.16);
            display: grid;
            gap: 12px;
            z-index: 90;
        }

        .client-alerts__head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .client-alerts__title {
            margin: 0;
            font-size: 1rem;
            color: var(--text-dark);
        }

        .client-alerts__meta {
            color: var(--text-soft);
            font-size: 0.82rem;
            margin: 0;
        }

        .client-alerts__list {
            display: grid;
            gap: 10px;
        }

        .client-alerts__item {
            padding: 12px 14px;
            border-radius: 16px;
            border: 1px solid rgba(148, 163, 184, 0.16);
            background: #ffffff;
            display: grid;
            gap: 6px;
        }

        .client-alerts__item.is-unread {
            border-color: rgba(37, 99, 235, 0.18);
            background: rgba(37, 99, 235, 0.05);
        }

        .client-alerts__item-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .client-alerts__item-title {
            margin: 0;
            color: var(--text-dark);
            font-size: 0.92rem;
            font-weight: 800;
        }

        .client-alerts__item-state {
            color: var(--text-soft);
            font-size: 0.76rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .client-alerts__item-message {
            margin: 0;
            color: var(--text-main);
            font-size: 0.88rem;
            line-height: 1.55;
        }

        .client-alerts__empty {
            padding: 18px;
            border-radius: 16px;
            background: rgba(15, 23, 42, 0.04);
            color: var(--text-soft);
            text-align: center;
            line-height: 1.55;
        }

        .client-topbar__status-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 999px;
            background: rgba(249, 115, 22, 0.12);
            color: #c2410c;
            font-size: 0.78rem;
            font-weight: 800;
            white-space: nowrap;
        }

        .client-topbar__status-pill::before {
            content: "";
            width: 8px;
            height: 8px;
            border-radius: 999px;
            background: currentColor;
            box-shadow: 0 0 0 4px rgba(249, 115, 22, 0.12);
        }

        .client-topbar__service-strip {
            display: none;
            margin-top: 10px;
            padding: 12px 14px;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.84);
            border: 1px solid rgba(255, 255, 255, 0.42);
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.08);
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .client-topbar__service-copy {
            display: grid;
            gap: 2px;
            min-width: 0;
        }

        .client-topbar__service-copy strong {
            color: var(--text-dark);
            font-size: 0.92rem;
            line-height: 1.3;
        }

        .client-topbar__service-copy span {
            color: var(--text-soft);
            font-size: 0.8rem;
            line-height: 1.35;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .client-quick-menu__summary {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            cursor: pointer;
        }

        .client-quick-menu__summary span:first-child {
            font-weight: 800;
            color: var(--text-dark);
        }

        .client-quick-menu__chevron {
            width: 18px;
            height: 18px;
            color: var(--text-soft);
        }

        .client-quick-menu__content {
            display: grid;
            gap: 10px;
            margin-top: 12px;
        }

        body.client-body.client-alerts-open {
            overflow: hidden;
        }

        @media (max-width: 767px) {
            .client-shell {
                padding: 10px 10px calc(var(--mobile-nav-height) + 22px);
            }

            .client-topbar {
                margin-bottom: 14px;
            }

            .client-topbar__surface {
                padding: 12px;
                gap: 10px;
                border-radius: 22px;
            }

            .client-topbar__identity {
                gap: 10px;
                align-items: flex-start;
            }

            .client-topbar__copy {
                gap: 1px;
                padding-top: 2px;
            }

            .client-topbar h1 {
                font-size: 0.98rem;
                line-height: 1.08;
            }

            .client-topbar__eyebrow {
                font-size: 0.7rem;
                letter-spacing: 0.08em;
            }

            .client-topbar__meta {
                font-size: 0.76rem;
                line-height: 1.3;
            }

            .client-topbar__status-pill.desktop-only {
                display: none !important;
            }

            .client-topbar__service-strip {
                display: flex;
            }

            .client-alerts__panel {
                position: fixed;
                left: 10px;
                right: 10px;
                top: calc(env(safe-area-inset-top, 0px) + 86px);
                width: auto;
                max-height: min(68vh, 560px);
                z-index: 120;
                padding: 12px;
                border-radius: 20px;
            }

            .client-alerts__head {
                align-items: flex-start;
            }

            .client-alerts__head .button {
                flex: 0 0 auto;
            }

            .client-alerts__item {
                padding: 12px;
            }

            .client-alerts__item-title {
                font-size: 0.88rem;
            }

            .client-alerts__item-message {
                font-size: 0.84rem;
                line-height: 1.5;
            }

            .client-quick-menu {
                margin-top: 10px;
                border-radius: 20px;
            }

            .client-quick-menu__summary {
                padding: 14px 16px;
            }

            .client-main {
                gap: 12px;
            }

            .panel,
            .flash,
            .stat-card,
            .notice-card {
                padding: 14px;
                border-radius: 20px;
            }

            .client-nav {
                width: calc(100% - 18px);
                padding: 7px 6px;
                border-radius: 22px;
                bottom: max(10px, env(safe-area-inset-bottom, 0px));
            }

            .client-nav__items {
                gap: 2px;
            }

            .client-nav__item {
                min-height: 56px;
                padding: 6px 2px;
                gap: 3px;
                font-size: 0.66rem;
            }

            .client-nav__icon {
                width: 20px;
                height: 20px;
            }
        }
    </style>

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

        $notificationPreview = is_array($globalNotificationsPreview ?? null) ? $globalNotificationsPreview : [];
        $unreadNotificationCount = (int) ($globalUnreadNotificationCount ?? 0);
        $globalActiveRequestData = is_array($globalActiveRequest ?? null) ? $globalActiveRequest : [];

        $notificationTypeLabel = function (mixed $value): string {
            $key = strtolower(trim((string) $value));

            return match ($key) {
                'assistance_request' => 'Solicitud de ayuda',
                'payment' => 'Pago',
                'provider' => 'Proveedor',
                'system' => 'Aviso',
                default => $key !== '' ? ucfirst(str_replace('_', ' ', $key)) : 'Aviso',
            };
        };

        $statusLabel = function (?string $status): string {
            $key = strtolower(trim((string) $status));

            return match ($key) {
                'created' => 'Solicitud enviada',
                'accepted' => 'Solicitud aceptada',
                'assigned' => 'Proveedor asignado',
                'in_progress' => 'En camino',
                'arrived' => 'Llegó al punto',
                'completed' => 'Servicio completado',
                'cancelled' => 'Servicio cancelado',
                'pending_validation' => 'Pago en revisión',
                'paid' => 'Pago confirmado',
                default => $status ?: 'En proceso',
            };
        };

        $headerPrimaryRoute = !empty($globalActiveRequestData)
            ? route('user.activo')
            : route('user.solicitud');

        $headerPrimaryLabel = !empty($globalActiveRequestData)
            ? 'Ver seguimiento'
            : 'Pedir ayuda';
    @endphp

    <div class="client-shell">
        @include('user.partials.bottom-nav')

        <header class="client-topbar">
            <div class="client-topbar__surface">
                <div class="client-topbar__identity">
                    <div class="client-brand-mark">ZY</div>

                    <div class="client-topbar__copy">
                        <p class="client-topbar__eyebrow">ZYGA</p>
                        <h1>{{ $pageHeading ?? 'Inicio' }}</h1>
                        <p class="client-topbar__meta">Hola, {{ $userFirstName }}</p>
                    </div>
                </div>

                <div class="client-topbar__tools">
                    @if(!empty($globalActiveRequestData))
                        <span class="client-topbar__status-pill desktop-only">
                            {{ $statusLabel(data_get($globalActiveRequestData, 'status')) }}
                        </span>
                    @endif

                    <details class="client-alerts" id="clientAlertsMenu">
                        <summary class="client-alerts__button" title="Notificaciones">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M6 9a6 6 0 1 1 12 0v3.6c0 .8.2 1.58.58 2.28L20 17H4l1.42-2.12c.38-.7.58-1.48.58-2.28V9" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M10 20a2 2 0 0 0 4 0" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                            </svg>

                            @if($unreadNotificationCount > 0)
                                <span class="client-alerts__badge">
                                    {{ $unreadNotificationCount > 99 ? '99+' : $unreadNotificationCount }}
                                </span>
                            @endif
                        </summary>

                        <div class="client-alerts__panel">
                            <div class="client-alerts__head">
                                <div>
                                    <h3 class="client-alerts__title">Notificaciones</h3>
                                    <p class="client-alerts__meta">
                                        {{ $unreadNotificationCount > 0 ? $unreadNotificationCount . ' nueva(s)' : 'Sin notificaciones nuevas' }}
                                    </p>
                                </div>

                                <div style="display:flex; gap:8px; flex-wrap:wrap; justify-content:flex-end;">
                                    @if(!empty($globalActiveRequestData))
                                        <a href="{{ route('user.activo') }}" class="button button--ghost button--compact">Seguimiento</a>
                                    @endif

                                    <a href="{{ route('user.notificaciones') }}" class="button button--ghost button--compact">Ver todas</a>
                                </div>
                            </div>

                            @if(!empty($notificationPreview))
                                <div class="client-alerts__list">
                                    @foreach($notificationPreview as $notification)
                                        <article class="client-alerts__item {{ empty($notification['is_read']) ? 'is-unread' : '' }}">
                                            <div class="client-alerts__item-head">
                                                <h4 class="client-alerts__item-title">
                                                    {{ $notificationTypeLabel($notification['type'] ?? null) }}
                                                </h4>

                                                <span class="client-alerts__item-state">
                                                    {{ empty($notification['is_read']) ? 'Nueva' : 'Leída' }}
                                                </span>
                                            </div>

                                            <p class="client-alerts__item-message">
                                                {{ $notification['message'] ?? 'Sin mensaje disponible.' }}
                                            </p>
                                        </article>
                                    @endforeach
                                </div>
                            @else
                                <div class="client-alerts__empty">
                                    No tienes notificaciones recientes.
                                </div>
                            @endif
                        </div>
                    </details>

                    <div class="avatar-circle" title="{{ $displayName }}">{{ $avatar }}</div>
                </div>
            </div>

            @if(!empty($globalActiveRequestData))
                <div class="client-topbar__service-strip mobile-only">
                    <div class="client-topbar__service-copy">
                        <strong>{{ $statusLabel(data_get($globalActiveRequestData, 'status')) }}</strong>
                        <span>{{ data_get($globalActiveRequestData, 'service.name', 'Servicio en curso') }}</span>
                    </div>

                    <a href="{{ route('user.activo') }}" class="button button--ghost button--compact">
                        Ver
                    </a>
                </div>
            @endif

            <details class="client-quick-menu mobile-only">
                <summary class="client-quick-menu__summary">
                    <span>Menú rápido</span>
                    <span class="client-quick-menu__chevron">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M7 10l5 5 5-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                </summary>

                <div class="client-quick-menu__content">
                    <a href="{{ $headerPrimaryRoute }}" class="button button--primary button--compact">
                        {{ $headerPrimaryLabel }}
                    </a>

                    @if ($currentPage !== 'history')
                        <a href="{{ route('user.historial') }}" class="button button--ghost button--compact">
                            Historial
                        </a>
                    @endif

                    @if ($currentPage !== 'payments')
                        <a href="{{ route('user.pagos') }}" class="button button--ghost button--compact">
                            Pagos
                        </a>
                    @endif

                    @if ($currentPage !== 'account')
                        <a href="{{ route('user.cuenta') }}" class="button button--secondary button--compact">
                            Mi perfil
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
                @if ($currentPage !== 'request' && empty($globalActiveRequestData))
                    <a href="{{ route('user.solicitud') }}" class="button button--primary button--compact">
                        Pedir ayuda
                    </a>
                @endif

                @if ($currentPage !== 'active' && !empty($globalActiveRequestData))
                    <a href="{{ route('user.activo') }}" class="button button--primary button--compact">
                        Ver seguimiento
                    </a>
                @endif

                @if ($currentPage !== 'history')
                    <a href="{{ route('user.historial') }}" class="button button--ghost button--compact">
                        Historial
                    </a>
                @endif

                @if ($currentPage !== 'payments')
                    <a href="{{ route('user.pagos') }}" class="button button--ghost button--compact">
                        Pagos
                    </a>
                @endif

                @if ($currentPage !== 'account')
                    <a href="{{ route('user.cuenta') }}" class="button button--secondary button--compact">
                        Mi perfil
                    </a>
                @endif
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
            unreadNotificationCount: @json($unreadNotificationCount),
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
            const alertsMenu = document.getElementById('clientAlertsMenu');

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

                syncInitialDesktopState();
                syncSidebarButton();
                syncTopActionsButton();
            };

            if (sidebarButton) {
                sidebarButton.addEventListener('click', function () {
                    const collapsed = body.classList.toggle('client-sidebar-collapsed');

                    if (collapsed) {
                        html.classList.add('client-sidebar-collapsed');
                    } else {
                        html.classList.remove('client-sidebar-collapsed');
                    }

                    try {
                        localStorage.setItem('zyga-client-sidebar', collapsed ? 'collapsed' : 'expanded');
                    } catch (e) {}

                    syncSidebarButton();
                });
            }

            if (topActionsButton) {
                topActionsButton.addEventListener('click', function () {
                    const collapsed = body.classList.toggle('client-top-actions-collapsed');

                    if (collapsed) {
                        html.classList.add('client-top-actions-collapsed');
                    } else {
                        html.classList.remove('client-top-actions-collapsed');
                    }

                    try {
                        localStorage.setItem('zyga-client-top-actions', collapsed ? 'collapsed' : 'expanded');
                    } catch (e) {}

                    syncTopActionsButton();
                });
            }

            if (alertsMenu) {
                alertsMenu.addEventListener('toggle', function () {
                    body.classList.toggle('client-alerts-open', alertsMenu.open);
                });

                document.addEventListener('click', function (event) {
                    if (!alertsMenu.open) {
                        return;
                    }

                    if (!alertsMenu.contains(event.target)) {
                        alertsMenu.open = false;
                        body.classList.remove('client-alerts-open');
                    }
                });

                document.addEventListener('keydown', function (event) {
                    if (event.key === 'Escape' && alertsMenu.open) {
                        alertsMenu.open = false;
                        body.classList.remove('client-alerts-open');
                    }
                });
            }

            applyDesktopStateFromStorage();
            window.addEventListener('resize', applyDesktopStateFromStorage);
        });
    </script>

    @stack('page_scripts')
</body>
</html>
