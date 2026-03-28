@extends('provider.layouts.app')

@section('title', 'Zyga | Servicios del proveedor')
@section('page-title', 'Servicios')

@section('content')

    @php
        $hasApiError = !empty($servicios['error']);
        $items = $servicios['data']['services'] ?? $servicios['data'] ?? [];

        if (!is_array($items)) {
            $items = [];
        }

        $totalServicios = count($items);
    @endphp

    @if($hasApiError)
        <section class="section-block">
            <div class="panel-card">
                <h3>Error de conexión</h3>
                <p>{{ $servicios['message'] ?? 'No se pudieron cargar los servicios.' }}</p>

                @if(!empty($servicios['details']))
                    <p class="muted">{{ $servicios['details'] }}</p>
                @endif
            </div>
        </section>
    @endif

    <section class="hero-card">
        <div>
            <p class="hero-kicker">Servicios del proveedor</p>
            <h2>Administra tus servicios</h2>
            <p class="muted">
                Consulta los servicios registrados en tu cuenta y verifica cuáles están disponibles para ofrecer en Zyga.
            </p>
        </div>

        <div class="hero-badge">
            {{ $totalServicios }} {{ $totalServicios === 1 ? 'servicio' : 'servicios' }}
        </div>
    </section>

    <section class="section-block">
        <div class="section-head">
            <h3>Servicios registrados</h3>
            <span class="pill">
                {{ $totalServicios }} activos
            </span>
        </div>

        @if(empty($items))
            <div class="panel-card">
                <h4>Sin servicios disponibles</h4>
                <p class="muted">
                    Aún no hay servicios asociados a este proveedor o no fue posible recuperarlos desde la API.
                </p>
            </div>
        @else
            <div class="services-grid">
                @foreach($items as $servicio)
                    @php
                        $nombre = $servicio['name'] ?? $servicio['service_name'] ?? 'Servicio sin nombre';
                        $descripcion = $servicio['description'] ?? 'Sin descripción disponible.';
                        $activo = $servicio['is_active'] ?? true;
                        $codigo = $servicio['code'] ?? null;
                    @endphp

                    <article class="service-card">
                        <div class="service-icon">
                            🛠️
                        </div>

                        <h4>{{ $nombre }}</h4>
                        <p>{{ $descripcion }}</p>

                        <div style="margin-top: 12px;">
                            <span class="{{ $activo ? 'pill pill-success' : 'pill pill-warning' }}">
                                {{ $activo ? 'Activo' : 'Inactivo' }}
                            </span>

                            @if($codigo)
                                <span class="pill" style="margin-left: 8px;">
                                    {{ $codigo }}
                                </span>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>

    <section class="section-block">
        <div class="section-head">
            <h3>Resumen</h3>
            <span class="pill">Solo lectura</span>
        </div>

        <div class="stack-list">
            <article class="list-card">
                <h4>Total de servicios</h4>
                <p>{{ $totalServicios }}</p>
            </article>

            <article class="list-card">
                <h4>Proveedor</h4>
                <p>{{ session('user.name') }}</p>
            </article>

            <article class="list-card">
                <h4>Correo</h4>
                <p>{{ session('user.email') }}</p>
            </article>
        </div>
    </section>

@endsection