<nav class="bottom-nav">
    <a href="{{ route('provider.dashboard') }}" class="{{ request()->routeIs('provider.dashboard') ? 'active' : '' }}">Inicio</a>
    <a href="{{ route('provider.perfil') }}" class="{{ request()->routeIs('provider.perfil*') ? 'active' : '' }}">Perfil</a>
    <a href="{{ route('provider.servicios') }}" class="{{ request()->routeIs('provider.servicios*') ? 'active' : '' }}">Servicios</a>
    <a href="{{ route('provider.horarios') }}" class="{{ request()->routeIs('provider.horarios*') ? 'active' : '' }}">Agenda</a>
    <a href="{{ route('provider.documentos') }}" class="{{ request()->routeIs('provider.documentos*') ? 'active' : '' }}">Docs</a>
    <a href="{{ route('provider.asistencias') }}" class="{{ request()->routeIs('provider.asistencias*') ? 'active' : '' }}">Atención</a>
</nav>
