@extends('user.layouts.app')

@section('content')
<section class="panel hero-panel">
    <div>
        <p class="hero-panel__eyebrow">Centro de control</p>
        <h2>Gestiona tu asistencia con una vista clara y ejecutiva.</h2>
        <p>Revisa tu preparación, inicia una nueva solicitud y mantén el seguimiento del servicio desde un solo lugar.</p>
    </div>
    <div class="hero-panel__actions">
        <a href="{{ route('user.solicitud') }}" class="button button--primary">Nueva asistencia</a>
        <a href="{{ route('user.activo') }}" class="button button--secondary">Ver seguimiento</a>
    </div>
</section>

<section class="stats-grid">
    <article class="stat-card">
        <span class="stat-card__label">Servicios disponibles</span>
        <strong id="dashboardServicesCount">0</strong>
    </article>
    <article class="stat-card">
        <span class="stat-card__label">Vehículos registrados</span>
        <strong id="dashboardVehiclesCount">0</strong>
    </article>
    <article class="stat-card">
        <span class="stat-card__label">Solicitudes activas</span>
        <strong id="dashboardActiveCount">0</strong>
    </article>
</section>

<section class="grid-two">
    <article class="panel">
        <div class="section-head">
            <h3>Servicios</h3>
            <span class="section-pill">Disponibilidad</span>
        </div>
        <div id="dashboardServicesList" class="stack-list"></div>
    </article>

    <article class="panel">
        <div class="section-head">
            <h3>Vehículo principal</h3>
            <a href="{{ route('user.cuenta') }}" class="text-link">Administrar cuenta</a>
        </div>
        <div id="dashboardVehicleState" class="empty-state">Cargando información...</div>
    </article>
</section>

<section class="panel">
    <div class="section-head">
        <h3>Servicio en curso</h3>
        <a href="{{ route('user.activo') }}" class="text-link">Abrir seguimiento</a>
    </div>
    <div id="dashboardActiveRequest" class="empty-state">Consultando estado actual...</div>
</section>

<section id="dashboardBlockers" class="stack-list"></section>
@endsection
