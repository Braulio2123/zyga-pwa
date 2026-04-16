@php
    $currentPage = $pageKey ?? 'dashboard';

    $navItems = [
        [
            'key' => 'dashboard',
            'route' => route('user.dashboard'),
            'label' => 'Inicio',
            'icon' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 10.8 12 3l9 7.8v9.2a1 1 0 0 1-1 1h-5.5v-6h-5v6H4a1 1 0 0 1-1-1v-9.2Z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg>',
        ],
        [
            'key' => 'request',
            'route' => route('user.solicitud'),
            'label' => 'Pedir ayuda',
            'icon' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 12h16M12 4v16" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>',
        ],
        [
            'key' => 'active',
            'route' => route('user.activo'),
            'label' => 'Seguimiento',
            'icon' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 18c2.2-3.5 4.8-5.2 8-5.2s5.8 1.7 8 5.2M6.5 9.5a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Zm11 0a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5ZM12 14V8" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        ],
        [
            'key' => 'history',
            'route' => route('user.historial'),
            'label' => 'Historial',
            'icon' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 7v5l3 2.2M21 12a9 9 0 1 1-2.64-6.36M21 4v5h-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        ],
        [
            'key' => 'account',
            'route' => route('user.cuenta'),
            'label' => 'Mi perfil',
            'icon' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M19 20a7 7 0 1 0-14 0M12 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        ],
    ];
@endphp

<nav class="client-nav" id="clientDesktopNav" aria-label="Navegación principal del cliente">
    <div class="client-nav__brand desktop-only">
        <div class="client-nav__brand-head">
            <div class="client-nav__brand-mark">ZYGA</div>

            <div class="client-nav__brand-copy">
                <strong class="client-nav__brand-title">Tu espacio</strong>
                <p class="client-nav__brand-text">
                    Pide ayuda, da seguimiento y revisa tu cuenta desde aquí.
                </p>
            </div>
        </div>
    </div>

    <div class="client-nav__items">
        @foreach($navItems as $item)
            <a
                href="{{ $item['route'] }}"
                class="client-nav__item {{ $currentPage === $item['key'] ? 'is-active' : '' }}"
                aria-current="{{ $currentPage === $item['key'] ? 'page' : 'false' }}"
                title="{{ $item['label'] }}"
            >
                <span class="client-nav__icon">{!! $item['icon'] !!}</span>
                <span class="client-nav__label">{{ $item['label'] }}</span>
            </a>
        @endforeach
    </div>

    <div class="client-nav__footer desktop-only">
        <a href="{{ route('user.pagos') }}" class="client-nav__support-link">
            Pagos
        </a>

        <button
            type="button"
            class="client-nav__logout"
            onclick="event.preventDefault(); document.getElementById('clientLogoutForm').submit();"
        >
            Cerrar sesión
        </button>
    </div>
</nav>
