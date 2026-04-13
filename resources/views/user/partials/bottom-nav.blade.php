@php
    $currentPage = $pageKey ?? 'dashboard';
@endphp

<nav class="client-nav" aria-label="Navegación principal del cliente">
    <a
        href="{{ route('user.dashboard') }}"
        class="client-nav__item {{ $currentPage === 'dashboard' ? 'is-active' : '' }}"
        aria-current="{{ $currentPage === 'dashboard' ? 'page' : 'false' }}"
    >
        <span>Inicio</span>
    </a>

    <a
        href="{{ route('user.solicitud') }}"
        class="client-nav__item {{ $currentPage === 'request' ? 'is-active' : '' }}"
        aria-current="{{ $currentPage === 'request' ? 'page' : 'false' }}"
    >
        <span>Solicitar</span>
    </a>

    <a
        href="{{ route('user.activo') }}"
        class="client-nav__item {{ $currentPage === 'active' ? 'is-active' : '' }}"
        aria-current="{{ $currentPage === 'active' ? 'page' : 'false' }}"
    >
        <span>Seguimiento</span>
    </a>

    <a
        href="{{ route('user.historial') }}"
        class="client-nav__item {{ $currentPage === 'history' ? 'is-active' : '' }}"
        aria-current="{{ $currentPage === 'history' ? 'page' : 'false' }}"
    >
        <span>Historial</span>
    </a>

    <a
        href="{{ route('user.cuenta') }}"
        class="client-nav__item {{ $currentPage === 'account' ? 'is-active' : '' }}"
        aria-current="{{ $currentPage === 'account' ? 'page' : 'false' }}"
    >
        <span>Cuenta</span>
    </a>
</nav>
