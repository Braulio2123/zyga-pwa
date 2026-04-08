<nav class="client-nav">
    <a href="{{ route('user.dashboard') }}" class="client-nav__item {{ request()->routeIs('user.dashboard') ? 'is-active' : '' }}">Inicio</a>
    <a href="{{ route('user.solicitud') }}" class="client-nav__item {{ request()->routeIs('user.solicitud') ? 'is-active' : '' }}">Solicitar</a>
    <a href="{{ route('user.activo') }}" class="client-nav__item {{ request()->routeIs('user.activo') ? 'is-active' : '' }}">Seguimiento</a>
    <a href="{{ route('user.historial') }}" class="client-nav__item {{ request()->routeIs('user.historial') ? 'is-active' : '' }}">Historial</a>
    <a href="{{ route('user.cuenta') }}" class="client-nav__item {{ request()->routeIs('user.cuenta') ? 'is-active' : '' }}">Cuenta</a>
    <form method="POST" action="{{ route('logout') }}" class="client-nav__logout-form">
        @csrf
        <button type="submit" class="client-nav__logout">Salir</button>
    </form>
</nav>
