<nav class="bottom-nav">
    <a href="{{ url('/user') }}" class="nav-item {{ request()->is('user') ? 'active' : '' }}">
        <span class="nav-icon">⌂</span>
        <span>Inicio</span>
    </a>

    <a href="{{ url('/user/historial') }}" class="nav-item {{ request()->is('user/historial') ? 'active' : '' }}">
        <span class="nav-icon">↺</span>
        <span>Historial</span>
    </a>

    <a href="{{ url('/user/billetera') }}" class="nav-item {{ request()->is('user/billetera') ? 'active' : '' }}">
        <span class="nav-icon">▣</span>
        <span>Billetera</span>
    </a>

    <a href="{{ url('/user/cuenta') }}" class="nav-item {{ request()->is('user/cuenta') ? 'active' : '' }}">
        <span class="nav-icon">◯</span>
        <span>Cuenta</span>
    </a>
</nav>