@extends('user.layouts.app')

@section('content')
@php
    $initialServices = is_array($requestServices ?? null) ? $requestServices : [];
    $initialVehicles = is_array($requestVehicles ?? null) ? $requestVehicles : [];
    $activeRequest = is_array($requestActiveRequest ?? null) ? $requestActiveRequest : [];
    $apiErrors = is_array($requestApiErrors ?? null) ? $requestApiErrors : [];
    $requestCanCreate = (bool) ($requestCanCreate ?? false);

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

    $vehicleLabel = function (array $vehicle): string {
        $brand = trim((string) ($vehicle['brand'] ?? ''));
        $model = trim((string) ($vehicle['model'] ?? ''));
        $plate = trim((string) ($vehicle['plate'] ?? ''));

        $base = trim($brand . ' ' . $model);

        return $plate !== ''
            ? trim($base . ' · ' . $plate)
            : ($base !== '' ? $base : 'Vehículo sin datos');
    };

    $activeServiceName = data_get($activeRequest, 'service.name', 'Servicio no identificado');
    $activeVehicleName = !empty($activeRequest['vehicle']) && is_array($activeRequest['vehicle'])
        ? $vehicleLabel($activeRequest['vehicle'])
        : 'Vehículo no identificado';
    $activePublicId = data_get($activeRequest, 'public_id')
        ?: (!empty($activeRequest['id']) ? '#' . $activeRequest['id'] : 'Sin folio');

    $blockingMessages = [];

    foreach ($apiErrors as $error) {
        $blockingMessages[] = [
            'tone' => 'danger',
            'message' => $error,
        ];
    }

    if (empty($initialVehicles)) {
        $blockingMessages[] = [
            'tone' => 'warning',
            'message' => 'No tienes vehículos disponibles. Debes registrar al menos uno antes de solicitar asistencia.',
        ];
    }

    if (empty($initialServices)) {
        $blockingMessages[] = [
            'tone' => 'warning',
            'message' => 'No hay servicios activos disponibles para generar una solicitud en este momento.',
        ];
    }

    if (!empty($activeRequest)) {
        $blockingMessages[] = [
            'tone' => 'warning',
            'message' => 'Ya existe una solicitud activa para este cliente. Debes darle seguimiento o cancelarla antes de crear otra.',
        ];
    }

    $formHelperMessage = $requestCanCreate
        ? 'El formulario está listo. Completa dirección, coordenadas y servicio para registrar la asistencia.'
        : 'El formulario está bloqueado hasta que el flujo cumpla las condiciones mínimas de operación.';
@endphp

<section class="panel hero-panel hero-panel--compact">
    <div>
        <p class="hero-panel__eyebrow">Nueva asistencia</p>
        <h2>Genera tu solicitud de forma clara y rápida.</h2>
        <p>
            Selecciona el servicio, el vehículo involucrado y la ubicación donde necesitas apoyo.
            Una vez creada la solicitud, podrás darle seguimiento desde la sección de servicio activo.
        </p>
    </div>
</section>

<section id="requestBlockingState" class="stack-list">
    @forelse($blockingMessages as $message)
        @php
            $isDanger = ($message['tone'] ?? 'warning') === 'danger';
        @endphp

        <article
            class="notice-card"
            style="border-color: {{ $isDanger ? 'rgba(220,38,38,0.18)' : 'rgba(161,98,7,0.18)' }}; background: {{ $isDanger ? 'rgba(220,38,38,0.05)' : 'rgba(161,98,7,0.06)' }}; color: {{ $isDanger ? '#b91c1c' : '#92400e' }};"
        >
            {{ $message['message'] }}
        </article>
    @empty
        <article
            class="notice-card"
            style="border-color: rgba(22,163,74,0.18); background: rgba(22,163,74,0.05); color: #166534;"
        >
            El flujo está listo para crear una nueva asistencia.
        </article>
    @endforelse
</section>

<section class="grid-two">
    <article class="panel">
        <div class="section-head">
            <h3>Antes de solicitar</h3>
            <span class="section-pill">Revisión</span>
        </div>

        <div class="stack-list">
            <article class="card-row">
                <h4 class="card-row__title">1. Verifica tu vehículo</h4>
                <p class="card-row__meta">
                    Debes tener al menos un vehículo registrado en tu cuenta para poder continuar.
                </p>
            </article>

            <article class="card-row">
                <h4 class="card-row__title">2. Confirma tu ubicación</h4>
                <p class="card-row__meta">
                    Puedes capturar latitud y longitud manualmente o usar la ubicación actual del dispositivo.
                </p>
            </article>

            <article class="card-row">
                <h4 class="card-row__title">3. Describe bien el punto de atención</h4>
                <p class="card-row__meta">
                    Entre más precisa sea la referencia, más fácil será para el proveedor llegar al sitio correcto.
                </p>
            </article>
        </div>

        <div class="actions-inline" style="margin-top: 1rem;">
            @if(empty($initialVehicles))
                <a href="{{ route('user.cuenta') }}" class="button button--secondary">Ir a Cuenta</a>
            @endif

            @if(!empty($activeRequest))
                <a href="{{ route('user.activo') }}" class="button button--primary">Abrir servicio activo</a>
            @endif
        </div>
    </article>

    <article class="panel">
        <div class="section-head">
            <h3>Estado del flujo</h3>
            <span class="section-pill">MVP</span>
        </div>

        @if(!empty($activeRequest))
            <div class="stack-list">
                <article class="card-row">
                    <h4 class="card-row__title">Solicitud activa detectada</h4>
                    <p class="card-row__meta">Folio: {{ $activePublicId }}</p>
                    <p class="card-row__meta">Servicio: {{ $activeServiceName }}</p>
                    <p class="card-row__meta">Vehículo: {{ $activeVehicleName }}</p>
                    <p class="card-row__meta">Estado: {{ $statusLabel(data_get($activeRequest, 'status')) }}</p>
                </article>
            </div>
        @else
            <div class="helper-note">
                Si ya tienes una solicitud activa, el sistema bloqueará la creación de otra hasta que la actual
                termine o sea cancelada.
            </div>

            <div class="stack-list">
                <article class="card-row">
                    <h4 class="card-row__title">Solicitud única activa</h4>
                    <p class="card-row__meta">
                        Esto evita duplicidades y mantiene ordenado el proceso entre cliente, proveedor y administración.
                    </p>
                </article>

                <article class="card-row">
                    <h4 class="card-row__title">Seguimiento posterior</h4>
                    <p class="card-row__meta">
                        Después de crear la asistencia, el control principal pasa a la vista de servicio activo.
                    </p>
                </article>

                <article class="card-row">
                    <h4 class="card-row__title">Servicios disponibles</h4>
                    <p class="card-row__meta">
                        {{ count($initialServices) }} servicio(s) activos detectados desde la API.
                    </p>
                </article>

                <article class="card-row">
                    <h4 class="card-row__title">Vehículos disponibles</h4>
                    <p class="card-row__meta">
                        {{ count($initialVehicles) }} vehículo(s) detectados para este cliente.
                    </p>
                </article>
            </div>
        @endif
    </article>
</section>

<section class="panel">
    <div class="section-head">
        <h3>Formulario de solicitud</h3>
        <span class="section-pill">Operación</span>
    </div>

    <div class="helper-note">
        {{ $formHelperMessage }}
    </div>

    <form id="assistanceRequestForm" class="form-grid" autocomplete="off">
        <label class="form-field form-field--full">
            <span>Servicio</span>
            <select id="requestServiceId" name="service_id" required @disabled(!$requestCanCreate)>
                <option value="">
                    {{ count($initialServices) ? 'Selecciona un servicio' : 'No hay servicios disponibles' }}
                </option>
                @foreach($initialServices as $service)
                    <option value="{{ $service['id'] }}" @selected(old('service_id') == ($service['id'] ?? null))>
                        {{ $service['name'] ?? 'Servicio' }}
                    </option>
                @endforeach
            </select>
        </label>

        <label class="form-field form-field--full">
            <span>Vehículo</span>
            <select id="requestVehicleId" name="vehicle_id" required @disabled(!$requestCanCreate)>
                <option value="">
                    {{ count($initialVehicles) ? 'Selecciona un vehículo' : 'No hay vehículos disponibles' }}
                </option>
                @foreach($initialVehicles as $vehicle)
                    <option value="{{ $vehicle['id'] }}" @selected(old('vehicle_id') == ($vehicle['id'] ?? null))>
                        {{ $vehicleLabel($vehicle) }}
                    </option>
                @endforeach
            </select>
        </label>

        <label class="form-field form-field--full">
            <span>Dirección o referencia</span>
            <textarea
                id="requestPickupAddress"
                name="pickup_address"
                rows="4"
                placeholder="Ej. Av. Juárez 123, frente a la farmacia, Guadalajara, Jalisco"
                required
                @disabled(!$requestCanCreate)
            >{{ old('pickup_address') }}</textarea>
        </label>

        <label class="form-field">
            <span>Latitud</span>
            <input
                type="number"
                step="0.000001"
                id="requestLat"
                name="lat"
                placeholder="20.673600"
                value="{{ old('lat') }}"
                required
                @disabled(!$requestCanCreate)
            >
        </label>

        <label class="form-field">
            <span>Longitud</span>
            <input
                type="number"
                step="0.000001"
                id="requestLng"
                name="lng"
                placeholder="-103.344000"
                value="{{ old('lng') }}"
                required
                @disabled(!$requestCanCreate)
            >
        </label>

        <div class="form-actions form-field--full">
            <button
                type="button"
                id="requestGeoButton"
                class="button button--secondary"
                @disabled(!$requestCanCreate)
            >
                Usar mi ubicación
            </button>

            <button
                type="submit"
                id="requestSubmitButton"
                class="button button--primary"
                @disabled(!$requestCanCreate)
            >
                Crear asistencia
            </button>
        </div>
    </form>
</section>
@endsection
