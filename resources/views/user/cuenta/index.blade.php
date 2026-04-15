@extends('user.layouts.app')

@section('content')
@php
    $initialProfile = is_array($accountProfile ?? null) ? $accountProfile : [];
    $initialUser = is_array($initialProfile['user'] ?? null) ? $initialProfile['user'] : [];
    $initialRoles = collect($initialProfile['roles'] ?? [])
        ->map(fn ($role) => $role['name'] ?? $role['code'] ?? null)
        ->filter()
        ->implode(', ');

    $initialVehicles = is_array($accountVehicles ?? null) ? $accountVehicles : [];

    $resolveVehicleTypeName = function (array $vehicle): string {
        return (string) (
            data_get($vehicle, 'vehicle_type.name')
            ?? data_get($vehicle, 'vehicleType.name')
            ?? data_get($vehicle, 'vehicle_type_id')
            ? 'Tipo #' . (data_get($vehicle, 'vehicle_type_id') ?? 'N/D')
            : 'Sin tipo'
        );
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
@endphp

<section class="panel hero-panel hero-panel--compact">
    <div>
        <p class="hero-panel__eyebrow">Cuenta</p>
        <h2>Perfil, seguridad y vehículos en un solo lugar.</h2>
        <p>
            Mantén tu información actualizada y deja al menos un vehículo listo para que el flujo de solicitud
            de asistencia sea más rápido y sin fricciones.
        </p>
    </div>
</section>

<section class="grid-two">
    <article class="panel">
        <div class="section-head">
            <h3>Perfil del cliente</h3>
            <span class="section-pill">Identidad</span>
        </div>
        <div class="helper-note">
            Aquí verás el correo y la información básica recuperada desde tu sesión y la API.
        </div>

        <div id="accountProfileCard" class="stack-list">
            @if(!empty($initialUser))
                <article class="profile-card">
                    <h4 class="card-row__title">{{ $initialUser['email'] ?? 'Sin correo' }}</h4>
                    <p class="card-row__meta">Roles: {{ $initialRoles !== '' ? $initialRoles : 'Cliente' }}</p>
                    <p class="card-row__meta">ID de usuario: {{ $initialUser['id'] ?? 'N/D' }}</p>
                </article>
            @elseif(!empty($accountLoadError))
                <article class="empty-state">{{ $accountLoadError }}</article>
            @else
                <article class="empty-state">No fue posible cargar el perfil del cliente.</article>
            @endif
        </div>
    </article>

    <article class="panel">
        <div class="section-head">
            <h3>Vehículos registrados</h3>
            <span class="section-pill">Movilidad</span>
        </div>
        <div class="helper-note">
            Mantén al menos un vehículo disponible para poder generar solicitudes sin bloquear el flujo.
        </div>

        <div id="accountVehiclesList" class="stack-list">
            @forelse($initialVehicles as $vehicle)
                <article class="vehicle-card">
                    <h4 class="vehicle-card__title">{{ $vehicleLabel($vehicle) }}</h4>
                    <p class="card-row__meta">Tipo: {{ $resolveVehicleTypeName($vehicle) }}</p>
                    <p class="card-row__meta">Año: {{ $vehicle['year'] ?? 'N/D' }}</p>
                    <div class="actions-inline">
                        <button type="button" class="button button--ghost" data-edit-vehicle="{{ $vehicle['id'] }}">Editar</button>
                        <button type="button" class="button button--danger" data-delete-vehicle="{{ $vehicle['id'] }}">Eliminar</button>
                    </div>
                </article>
            @empty
                <article class="empty-state">
                    {{ !empty($accountLoadError) ? $accountLoadError : 'Aún no registras vehículos.' }}
                </article>
            @endforelse
        </div>
    </article>
</section>

<section class="grid-two">
    <article class="panel">
        <div class="section-head">
            <h3>Actualizar correo electrónico</h3>
            <span class="section-pill">Cuenta</span>
        </div>
        <form id="accountEmailForm" class="form-grid" autocomplete="off">
            <label class="form-field form-field--full">
                <span>Correo electrónico</span>
                <input
                    type="email"
                    id="accountEmailInput"
                    name="email"
                    value="{{ $initialUser['email'] ?? '' }}"
                    placeholder="cliente@zyga.com"
                    required
                >
            </label>

            <div class="form-actions form-field--full">
                <button type="submit" class="button button--primary">Guardar correo</button>
            </div>
        </form>
    </article>

    <article class="panel">
        <div class="section-head">
            <h3>Actualizar contraseña</h3>
            <span class="section-pill">Seguridad</span>
        </div>
        <form id="accountPasswordForm" class="form-grid" autocomplete="off">
            <label class="form-field form-field--full">
                <span>Nueva contraseña</span>
                <input
                    type="password"
                    name="password"
                    id="accountPasswordInput"
                    minlength="8"
                    placeholder="Mínimo 8 caracteres"
                    required
                >
            </label>

            <div class="form-actions form-field--full">
                <button type="submit" class="button button--secondary">Actualizar contraseña</button>
            </div>
        </form>
    </article>
</section>

<section class="panel">
    <div class="section-head">
        <h3>Registrar o editar vehículo</h3>
        <button type="button" id="vehicleFormReset" class="button button--ghost">Nuevo</button>
    </div>

    <div class="helper-note">
        Usa este formulario para registrar un vehículo nuevo o editar uno existente. Cuando pulses
        <strong>Editar</strong> en un vehículo, los campos se llenarán automáticamente aquí.
    </div>

    <form id="vehicleForm" class="form-grid" autocomplete="off">
        <input type="hidden" name="vehicle_id" id="vehicleIdInput">

        <label class="form-field">
            <span>Tipo de vehículo</span>
            <select name="vehicle_type_id" id="vehicleTypeInput" required>
                <option value="">Selecciona tipo</option>
            </select>
        </label>

        <label class="form-field">
            <span>Placas</span>
            <input
                type="text"
                name="plate"
                id="vehiclePlateInput"
                placeholder="JAL-458-B"
                required
            >
        </label>

        <label class="form-field">
            <span>Marca</span>
            <input
                type="text"
                name="brand"
                id="vehicleBrandInput"
                placeholder="Nissan"
                required
            >
        </label>

        <label class="form-field">
            <span>Modelo</span>
            <input
                type="text"
                name="model"
                id="vehicleModelInput"
                placeholder="Versa"
                required
            >
        </label>

        <label class="form-field form-field--full">
            <span>Año</span>
            <input
                type="number"
                name="year"
                id="vehicleYearInput"
                min="1900"
                max="2100"
                placeholder="2021"
            >
        </label>

        <div class="form-actions form-field--full">
            <button type="submit" id="vehicleSubmitButton" class="button button--primary">
                Guardar vehículo
            </button>
        </div>
    </form>
</section>
@endsection
