@extends('user.layouts.app')

@section('content')
<section class="panel hero-panel">
    <div>
        <p class="hero-panel__eyebrow">Inicio</p>
        <h2>Tu asistencia vial, lista para operar desde cualquier dispositivo.</h2>
        <p>
            Desde aquí puedes validar tu preparación, revisar si ya tienes un servicio activo
            y entrar rápidamente al flujo principal de solicitud.
        </p>
    </div>

    <div class="hero-panel__actions">
        <a href="{{ route('user.solicitud') }}" class="button button--primary">Nueva asistencia</a>
        <a href="{{ route('user.activo') }}" class="button button--secondary">Ver seguimiento</a>
        <a href="{{ route('user.cuenta') }}" class="button button--ghost">Mis vehículos</a>
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
            <h3>Servicios disponibles</h3>
            <span class="section-pill">Catálogo</span>
        </div>

        <div class="helper-note">
            Este bloque muestra los servicios publicados por la operación y disponibles para generar una asistencia.
        </div>

        <div id="dashboardServicesList" class="stack-list">
            <article class="empty-state">Cargando servicios...</article>
        </div>
    </article>

    <article class="panel">
        <div class="section-head">
            <h3>Vehículo principal</h3>
            <a href="{{ route('user.cuenta') }}" class="text-link">Administrar cuenta</a>
        </div>

        <div class="helper-note">
            Verifica aquí si ya tienes un vehículo listo para usar dentro del flujo de solicitud.
        </div>

        <div id="dashboardVehicleState" class="stack-list">
            <article class="empty-state">Cargando información del vehículo...</article>
        </div>
    </article>
</section>

<section class="grid-two">
    <article class="panel">
        <div class="section-head">
            <h3>Servicio activo</h3>
            <a href="{{ route('user.activo') }}" class="text-link">Abrir seguimiento</a>
        </div>

        <div class="helper-note">
            Si tienes una asistencia abierta, aquí verás el resumen y podrás continuar hacia el seguimiento completo.
        </div>

        <div id="dashboardActiveRequest" class="stack-list">
            <article class="empty-state">Consultando estado actual...</article>
        </div>
    </article>

    <article class="panel">
        <div class="section-head">
            <h3>Validaciones del flujo</h3>
            <span class="section-pill">Preparación</span>
        </div>

        <div class="helper-note">
            El sistema te avisará si todavía hay algo que resolver antes de poder operar con normalidad.
        </div>

        <div id="dashboardBlockers" class="stack-list">
            <article class="empty-state">Revisando condiciones del flujo...</article>
        </div>
    </article>
</section>
@endsection
