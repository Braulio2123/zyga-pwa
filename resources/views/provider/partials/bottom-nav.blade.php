<nav class="provider-bottom-nav">
    <a href="{{ route('provider.dashboard') }}" class="{{ request()->routeIs('provider.dashboard') ? 'active' : '' }}">
        <span>Inicio</span>
    </a>

    <a href="{{ route('provider.perfil') }}" class="{{ request()->routeIs('provider.perfil') ? 'active' : '' }}">
        <span>Perfil</span>
    </a>

    <a href="{{ route('provider.servicios') }}" class="{{ request()->routeIs('provider.servicios') ? 'active' : '' }}">
        <span>Servicios</span>
    </a>

    <a href="{{ route('provider.horarios') }}" class="{{ request()->routeIs('provider.horarios*') ? 'active' : '' }}">
        <span>Horarios</span>
    </a>

    <a href="{{ route('provider.documentos') }}" class="{{ request()->routeIs('provider.documentos*') ? 'active' : '' }}">
        <span>Docs</span>
    </a>

    <a href="{{ route('provider.asistencias') }}" class="{{ request()->routeIs('provider.asistencias*') ? 'active' : '' }}">
        <span>Asistencias</span>
    </a>
</nav>
