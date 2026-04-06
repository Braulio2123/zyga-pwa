@extends('provider.layouts.app')

@section('title', 'ZYGA | Provider')
@section('page-title', 'Inicio')

@section('content')
@php
    $profile = $profileResult['data'] ?? null;
    $serviceItems = $servicesResult['data']['services'] ?? [];
    $availableItems = $availableResult['data'] ?? [];
    $myItems = $myRequestsResult['data'] ?? [];
    $documentItems = $documentsResult['data']['documents'] ?? [];

    $displayName = $profile['display_name'] ?? (session('user.name') ?? 'Proveedor');
    $providerKind = $profile['provider_kind'] ?? 'Sin definir';

    $activeCount = collect($myItems)->whereIn('status', ['assigned', 'in_progress'])->count();
    $completedCount = collect($myItems)->where('status', 'completed')->count();
@endphp

<section class="hero-card">
    <div>
        <p class="hero-kicker">Panel operativo</p>
        <h2>{{ $displayName }}</h2>
        <p class="muted">
            {{ $profile ? 'Consulta tu operación actual, gestiona disponibilidad y atiende solicitudes desde el panel web.' : 'Tu cuenta inició sesión correctamente, pero aún no se encontró un perfil de proveedor en la API.' }}
        </p>
    </div>
    <div class="hero-badge">{{ $providerKind }}</div>
</section>

<section class="section-block">
    <div class="section-head">
        <h3>Resumen rápido</h3>
        <span class="pill">API real</span>
    </div>

    <div class="wallet-summary">
        <article class="summary-card summary-card--highlight">
            <span class="summary-label">Servicios activos</span>
            <strong class="summary-value">{{ count($serviceItems) }}</strong>
        </article>
        <article class="summary-card">
            <span class="summary-label">Solicitudes disponibles</span>
            <strong class="summary-value">{{ count($availableItems) }}</strong>
        </article>
        <article class="summary-card">
            <span class="summary-label">Atenciones en curso</span>
            <strong class="summary-value">{{ $activeCount }}</strong>
        </article>
        <article class="summary-card">
            <span class="summary-label">Documentos registrados</span>
            <strong class="summary-value">{{ count($documentItems) }}</strong>
        </article>
    </div>
</section>

<section class="section-block">
    <div class="section-head">
        <h3>Acciones rápidas</h3>
        <span class="pill">Provider</span>
    </div>

    <div class="services-grid">
        <a href="{{ route('provider.perfil') }}" class="service-card">
            <div class="service-icon">👤</div>
            <h4>Perfil</h4>
            <p>Consulta y actualiza tu información principal como proveedor.</p>
        </a>

        <a href="{{ route('provider.servicios') }}" class="service-card">
            <div class="service-icon">🛠️</div>
            <h4>Servicios</h4>
            <p>Selecciona los servicios activos que puedes atender.</p>
        </a>

        <a href="{{ route('provider.horarios') }}" class="service-card">
            <div class="service-icon">🕒</div>
            <h4>Horarios</h4>
            <p>Administra tus días y horas de atención.</p>
        </a>

        <a href="{{ route('provider.documentos') }}" class="service-card">
            <div class="service-icon">📄</div>
            <h4>Documentos</h4>
            <p>Registra y organiza los documentos ligados a tu cuenta.</p>
        </a>
    </div>
</section>

<section class="section-block">
    <div class="section-head">
        <h3>Solicitudes disponibles</h3>
        <span class="pill">{{ count($availableItems) }} abiertas</span>
    </div>

    @if(empty($availableItems))
        <div class="panel-card">
            <h4>Sin solicitudes por ahora</h4>
            <p class="muted">No hay solicitudes disponibles para los servicios que tienes asociados actualmente.</p>
        </div>
    @else
        <div class="stack-list">
            @foreach(array_slice($availableItems, 0, 3) as $item)
                <article class="list-card">
                    <div class="inline-between gap-12">
                        <div>
                            <h4>{{ $item['service']['name'] ?? 'Servicio sin nombre' }}</h4>
                            <p>{{ $item['pickup_address'] ?? 'Sin dirección registrada' }}</p>
                            <span class="meta-text">Estatus: {{ $item['status'] ?? 'Sin estado' }}</span>
                        </div>
                        <a href="{{ route('provider.asistencias') }}" class="btn-secondary">Ver asistencias</a>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</section>

<section class="section-block">
    <div class="section-head">
        <h3>Mis resultados</h3>
        <span class="pill">{{ count($myItems) }} totales</span>
    </div>

    <div class="stack-list">
        <article class="list-card">
            <h4>Atenciones completadas</h4>
            <p>{{ $completedCount }}</p>
        </article>
        <article class="list-card">
            <h4>Correo de sesión</h4>
            <p>{{ session('user.email') }}</p>
        </article>
        <article class="list-card">
            <h4>Rol autenticado</h4>
            <p>{{ session('user.role') }}</p>
        </article>
    </div>
</section>
@endsection
