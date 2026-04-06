<nav class="bottom-nav">
    <a href="{{ route('provider.dashboard') }}" class="nav-item {{ request()->routeIs('provider.dashboard') ? 'active' : '' }}">
        <span class="nav-icon">🏠</span>
        <span>Inicio</span>
    </a>
    <a href="{{ route('provider.perfil') }}" class="nav-item {{ request()->routeIs('provider.perfil*') ? 'active' : '' }}">
        <span class="nav-icon">👤</span>
        <span>Perfil</span>
    </a>
    <a href="{{ route('provider.documentos') }}" class="nav-item {{ request()->routeIs('provider.documentos*') ? 'active' : '' }}">
        <span class="nav-icon">📄</span>
        <span>Documentos</span>
    </a>
    <a href="{{ route('provider.asistencias') }}" class="nav-item {{ request()->routeIs('provider.asistencias*') ? 'active' : '' }}">
        <span class="nav-icon">🚗</span>
        <span>Asistencias</span>
    </a>
</nav>
