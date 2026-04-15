@extends('user.layouts.app')

@section('content')
@php
    $initialServices = is_array($dashboardServices ?? null) ? $dashboardServices : [];
    $initialVehicles = is_array($dashboardVehicles ?? null) ? $dashboardVehicles : [];
    $initialActiveRequest = is_array($dashboardActiveRequest ?? null) ? $dashboardActiveRequest : [];
    $dashboardErrors = is_array($dashboardApiErrors ?? null) ? $dashboardApiErrors : [];

    $servicesCount = count($initialServices);
    $vehiclesCount = count($initialVehicles);
    $activeCount = !empty($initialActiveRequest) ? 1 : 0;

    $vehicleLabel = function (array $vehicle): string {
        $brand = trim((string) ($vehicle['brand'] ?? ''));
        $model = trim((string) ($vehicle['model'] ?? ''));
        $plate = trim((string) ($vehicle['plate'] ?? ''));

        $base = trim($brand . ' ' . $model);

        return $plate !== ''
            ? trim($base . ' · ' . $plate)
            : ($base !== '' ? $base : 'Vehículo sin datos');
    };

    $statusLabel = function (?string $status): string {
        $map = [
            'created' => 'Creada',
            'accepted' => 'Aceptada',
            'assigned' => 'Asignada',
            'in_progress' => 'En progreso',
            'arrived' => 'En sitio',
            'completed' => 'Completada',
            'cancelled' => 'Cancelada',
            'quoted' => 'Cotizada',
            'pending' => 'Pendiente',
        ];

        $key = strtolower(trim((string) $status));

        return $map[$key] ?? ($status ?: 'Sin estado');
    };

    $primaryVehicle = $initialVehicles[0] ?? [];
    $servicePreview = array_slice($initialServices, 0, 4);

    $dashboardNotices = [];

    foreach ($dashboardErrors as $error) {
        $dashboardNotices[] = ['tone' => 'danger', 'message' => $error];
    }

    if (empty($initialVehicles)) {
        $dashboardNotices[] = [
            'tone' => 'warning',
            'message' => 'Aún no tienes vehículos registrados. Debes cargar al menos uno para poder solicitar asistencia.',
        ];
    }

    if (!empty($initialActiveRequest)) {
        $dashboardNotices[] = [
            'tone' => 'warning',
            'message' => 'Ya tienes una solicitud activa. Antes de crear otra, continúa el seguimiento de la actual.',
        ];
    }

    $activeServiceName = data_get($initialActiveRequest, 'service.name', 'Servicio no identificado');
    $activeAddress = data_get($initialActiveRequest, 'pickup_address', 'Sin dirección registrada');
    $activePublicId = data_get($initialActiveRequest, 'public_id')
        ?: (!empty($initialActiveRequest['id']) ? '#' . $initialActiveRequest['id'] : 'Sin folio');
@endphp

<section class="panel hero-panel hero-panel--compact">
    <div>
        <p class="hero-panel__eyebrow">Inicio</p>
        <h2>Tu asistencia vial, lista para operar desde cualquier dispositivo.</h2>
        <p>
            Desde aquí puedes iniciar una nueva asistencia, revisar el estado de tu servicio actual
            y validar rápidamente si tu cuenta está lista para operar.
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
        <strong id="dashboardServicesCount">{{ $servicesCount }}</strong>
    </article>

    <article class="stat-card">
        <span class="stat-card__label">Vehículos registrados</span>
        <strong id="dashboardVehiclesCount">{{ $vehiclesCount }}</strong>
    </article>

    <article class="stat-card">
        <span class="stat-card__label">Solicitudes activas</span>
        <strong id="dashboardActiveCount">{{ $activeCount }}</strong>
    </article>
</section>

<section class="grid-two">
    <article class="panel">
        <div class="section-head">
            <h3>Acciones rápidas</h3>
            <span class="section-pill">Operación</span>
        </div>

        <div class="stack-list">
            <article class="card-row">
                <h4 class="card-row__title">Solicitar asistencia</h4>
                <p class="card-row__meta">
                    Inicia el flujo principal con servicio, vehículo y ubicación.
                </p>
                <div class="actions-inline" style="margin-top: 10px;">
                    <a href="{{ route('user.solicitud') }}" class="button button--primary button--compact">
                        Abrir solicitud
                    </a>
                </div>
            </article>

            <article class="card-row">
                <h4 class="card-row__title">Dar seguimiento</h4>
                <p class="card-row__meta">
                    Consulta timeline, estado y cambios de tu asistencia activa.
                </p>
                <div class="actions-inline" style="margin-top: 10px;">
                    <a href="{{ route('user.activo') }}" class="button button--secondary button--compact">
                        Ir a seguimiento
                    </a>
                </div>
            </article>

            <article class="card-row">
                <h4 class="card-row__title">Actualizar cuenta</h4>
                <p class="card-row__meta">
                    Gestiona vehículos, correo y contraseña desde un solo módulo.
                </p>
                <div class="actions-inline" style="margin-top: 10px;">
                    <a href="{{ route('user.cuenta') }}" class="button button--ghost button--compact">
                        Abrir cuenta
                    </a>
                </div>
            </article>
        </div>
    </article>

    <article class="panel">
        <div class="section-head">
            <h3>Estado operativo</h3>
            <span class="section-pill">Resumen</span>
        </div>

        <div id="dashboardBlockers" class="stack-list">
            @forelse($dashboardNotices as $notice)
                @php
                    $isDanger = ($notice['tone'] ?? 'warning') === 'danger';
                @endphp

                <article
                    class="notice-card"
                    style="border-color: {{ $isDanger ? 'rgba(220,38,38,0.18)' : 'rgba(161,98,7,0.18)' }}; background: {{ $isDanger ? 'rgba(220,38,38,0.05)' : 'rgba(161,98,7,0.06)' }}; color: {{ $isDanger ? '#b91c1c' : '#92400e' }};"
                >
                    {{ $notice['message'] }}
                </article>
            @empty
                <article
                    class="notice-card"
                    style="border-color: rgba(22,163,74,0.18); background: rgba(22,163,74,0.05); color: #166534;"
                >
                    Tu cuenta está lista para operar: tienes vehículo disponible y no hay bloqueos visibles.
                </article>
            @endforelse
        </div>
    </article>
</section>

<section class="grid-two">
    <article class="panel">
        <div class="section-head">
            <h3>Vehículo principal</h3>
            <a href="{{ route('user.cuenta') }}" class="text-link">Administrar</a>
        </div>

        <div id="dashboardVehicleState" class="stack-list">
            @if(!empty($primaryVehicle))
                <article class="vehicle-card">
                    <h4 class="vehicle-card__title">{{ $vehicleLabel($primaryVehicle) }}</h4>
                    <p class="card-row__meta">
                        Tipo:
                        {{ data_get($primaryVehicle, 'vehicle_type.name') ?? data_get($primaryVehicle, 'vehicleType.name') ?? 'Sin tipo identificado' }}
                    </p>
                    <p class="card-row__meta">Total registrados: {{ $vehiclesCount }}</p>
                </article>
            @else
                <article class="empty-state">
                    Aún no registras vehículos. Ve a Cuenta para cargar al menos uno antes de solicitar asistencia.
                </article>
            @endif
        </div>
    </article>

    <article class="panel">
        <div class="section-head">
            <h3>Servicios disponibles</h3>
            <span class="section-pill">Catálogo</span>
        </div>

        <div id="dashboardServicesList" class="stack-list">
            @forelse($servicePreview as $service)
                <article class="card-row">
                    <h4 class="card-row__title">{{ $service['name'] ?? 'Servicio' }}</h4>
                    <p class="card-row__meta">
                        {{ $service['description'] ?? 'Servicio activo disponible para solicitudes del cliente.' }}
                    </p>
                </article>
            @empty
                <article class="empty-state">
                    No hay servicios activos publicados por la API.
                </article>
            @endforelse
        </div>
    </article>
</section>

<section class="grid-two">
    <article class="panel">
        <div class="section-head">
            <h3>Servicio activo</h3>
            <a href="{{ route('user.activo') }}" class="text-link">Abrir seguimiento</a>
        </div>

        <div id="dashboardActiveRequest" class="stack-list">
            @if(!empty($initialActiveRequest))
                <article class="request-card">
                    <h4 class="request-card__title">{{ $activeServiceName }}</h4>
                    <p class="request-card__meta">Folio: {{ $activePublicId }}</p>
                    <p class="request-card__meta">Estado: {{ $statusLabel(data_get($initialActiveRequest, 'status')) }}</p>
                    <p class="request-card__meta">Ubicación: {{ $activeAddress }}</p>

                    <div class="actions-inline" style="margin-top: 10px;">
                        <a href="{{ route('user.activo') }}" class="button button--secondary button--compact">
                            Abrir timeline
                        </a>
                    </div>
                </article>
            @else
                <article class="empty-state">
                    No tienes una solicitud activa. Cuando generes una asistencia, verás aquí el folio,
                    servicio y dirección.
                </article>
            @endif
        </div>
    </article>

    <article class="panel">
        <div class="section-head">
            <h3>Qué puedes hacer hoy</h3>
            <span class="section-pill">Guía rápida</span>
        </div>

        <div class="stack-list">
            <article class="card-row">
                <h4 class="card-row__title">1. Verifica tu vehículo</h4>
                <p class="card-row__meta">
                    Confirma que tus placas, marca y modelo estén listos antes de solicitar apoyo.
                </p>
            </article>

            <article class="card-row">
                <h4 class="card-row__title">2. Crea una nueva asistencia</h4>
                <p class="card-row__meta">
                    Usa la sección Solicitar para registrar servicio, dirección y coordenadas.
                </p>
            </article>

            <article class="card-row">
                <h4 class="card-row__title">3. Revisa el seguimiento</h4>
                <p class="card-row__meta">
                    Cuando un proveedor acepte, consulta avances desde Servicio activo.
                </p>
            </article>
        </div>
    </article>
</section>
@endsection
