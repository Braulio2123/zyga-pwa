@extends('user.layouts.app')

@push('page_styles')
<style>
    .account-hero {
        display: grid;
        gap: 14px;
    }

    .account-hero__copy {
        display: grid;
        gap: 6px;
    }

    .account-hero__copy h2 {
        margin: 0;
        color: #0f172a;
        line-height: 1.08;
        font-size: clamp(1.35rem, 2vw, 1.9rem);
    }

    .account-hero__copy p {
        margin: 0;
        color: #475569;
        line-height: 1.55;
        font-size: 0.95rem;
    }

    .account-error-card {
        border-color: rgba(220, 38, 38, 0.18);
        background: rgba(220, 38, 38, 0.05);
        color: #b91c1c;
    }

    .account-ready-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 12px;
    }

    .account-ready-card {
        padding: 16px;
        border-radius: 20px;
        border: 1px solid rgba(148, 163, 184, 0.16);
        background: #ffffff;
        display: grid;
        gap: 6px;
    }

    .account-ready-card.is-ready {
        border-color: rgba(22, 163, 74, 0.18);
        background: rgba(22, 163, 74, 0.05);
    }

    .account-ready-card span {
        color: #64748b;
        font-size: 0.8rem;
    }

    .account-ready-card strong {
        color: #0f172a;
        line-height: 1.45;
        font-size: 1rem;
    }

    .account-profile-card {
        display: grid;
        gap: 14px;
        padding: 18px;
        border-radius: 24px;
        border: 1px solid rgba(15, 23, 42, 0.08);
        background:
            radial-gradient(circle at top right, rgba(249, 115, 22, 0.10), transparent 38%),
            radial-gradient(circle at bottom left, rgba(37, 99, 235, 0.10), transparent 45%),
            #ffffff;
    }

    .account-profile-card__top {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .account-profile-card__avatar {
        width: 58px;
        height: 58px;
        border-radius: 18px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 1.2rem;
        color: #fff;
        background: linear-gradient(135deg, #f97316, #ea580c);
        box-shadow: 0 12px 26px rgba(249, 115, 22, 0.22);
        flex: 0 0 auto;
    }

    .account-profile-card__copy {
        min-width: 0;
        display: grid;
        gap: 4px;
    }

    .account-profile-card__copy h3 {
        margin: 0;
        color: #0f172a;
        line-height: 1.2;
    }

    .account-profile-card__copy p {
        margin: 0;
        color: #64748b;
        line-height: 1.45;
        word-break: break-word;
    }

    .account-profile-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
    }

    .account-profile-item {
        padding: 14px 16px;
        border-radius: 18px;
        border: 1px solid rgba(148, 163, 184, 0.16);
        background: #f8fafc;
        display: grid;
        gap: 6px;
    }

    .account-profile-item span {
        color: #64748b;
        font-size: 0.8rem;
    }

    .account-profile-item strong {
        color: #0f172a;
        line-height: 1.45;
        word-break: break-word;
    }

    .account-vehicles-head {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        align-items: center;
        justify-content: space-between;
    }

    .account-vehicles-grid {
        display: grid;
        gap: 12px;
    }

    .account-vehicles-grid--spaced,
    .account-form-grid--spaced,
    .account-note--spaced {
        margin-top: 14px;
    }

    .account-section-head--compact {
        margin-bottom: 0;
    }

    .account-vehicle-card {
        padding: 16px;
        border-radius: 20px;
        border: 1px solid rgba(148, 163, 184, 0.16);
        background: #ffffff;
        display: grid;
        gap: 8px;
    }

    .account-vehicle-card__title {
        margin: 0;
        color: #0f172a;
        line-height: 1.3;
        font-size: 1rem;
    }

    .account-vehicle-card__meta {
        margin: 0;
        color: #64748b;
        line-height: 1.5;
        font-size: 0.9rem;
    }

    .account-note {
        padding: 14px 16px;
        border-radius: 18px;
        background: rgba(15, 23, 42, 0.04);
        border: 1px solid rgba(148, 163, 184, 0.14);
        color: #475569;
        line-height: 1.55;
        font-size: 0.92rem;
    }

    @media (max-width: 900px) {
        .account-ready-grid,
        .account-profile-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 560px) {
        .account-profile-card,
        .account-ready-card,
        .account-vehicle-card {
            padding: 14px;
        }

        .account-profile-card__avatar {
            width: 50px;
            height: 50px;
            border-radius: 16px;
            font-size: 1.05rem;
        }

        .account-hero__copy h2 {
            font-size: 1.25rem;
        }

        .account-hero__copy p,
        .account-note,
        .account-vehicle-card__meta {
            font-size: 0.88rem;
        }
    }
</style>
@endpush

@section('content')
@php
    $initialProfile = is_array($accountProfile ?? null) ? $accountProfile : [];
    $initialUser = is_array($initialProfile['user'] ?? null) ? $initialProfile['user'] : [];
    $initialRoles = collect($initialProfile['roles'] ?? [])
        ->map(fn ($role) => $role['name'] ?? $role['code'] ?? null)
        ->filter()
        ->implode(', ');

    $initialVehicles = is_array($accountVehicles ?? null) ? $accountVehicles : [];
    $accountError = trim((string) ($accountLoadError ?? ''));

    $resolveVehicleTypeName = function (array $vehicle): string {
        $vehicleTypeName = data_get($vehicle, 'vehicle_type.name')
            ?? data_get($vehicle, 'vehicleType.name');

        if ($vehicleTypeName) {
            return (string) $vehicleTypeName;
        }

        $vehicleTypeId = data_get($vehicle, 'vehicle_type_id');

        return $vehicleTypeId ? 'Tipo #' . $vehicleTypeId : 'Sin tipo';
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

    $userEmail = trim((string) ($initialUser['email'] ?? ''));
    $userName = trim((string) ($initialUser['name'] ?? 'Cliente'));
    $avatarLetter = strtoupper(substr($userName !== '' ? $userName : ($userEmail !== '' ? $userEmail : 'C'), 0, 1));

    $profileReady = $userEmail !== '';
    $hasVehicle = !empty($initialVehicles);
    $rolesLabel = $initialRoles !== '' ? $initialRoles : 'Cliente';
@endphp

<section class="panel account-hero">
    <div class="account-hero__copy">
        <p class="hero-panel__eyebrow">Mi cuenta</p>
        <h2>Tu cuenta y tus vehículos</h2>
        <p>Desde aquí puedes revisar tu información, actualizar tus datos de acceso y dejar listo tu vehículo para pedir ayuda cuando lo necesites.</p>
    </div>
</section>

@if($accountError !== '')
    <section class="stack-list">
        <article class="notice-card account-error-card">
            {{ $accountError }}
        </article>
    </section>
@endif

<section class="account-ready-grid">
    <article class="account-ready-card {{ $profileReady ? 'is-ready' : '' }}">
        <span>Correo</span>
        <strong>{{ $profileReady ? 'Listo' : 'Falta revisar' }}</strong>
    </article>

    <article class="account-ready-card {{ $hasVehicle ? 'is-ready' : '' }}">
        <span>Vehículo</span>
        <strong>{{ $hasVehicle ? 'Ya registrado' : 'Agrega uno' }}</strong>
    </article>

    <article class="account-ready-card is-ready">
        <span>Acceso</span>
        <strong>{{ $rolesLabel }}</strong>
    </article>
</section>

<section class="grid-two">
    <article class="panel">
        <div class="section-head">
            <h3>Tu información</h3>
            <span class="section-pill">Cuenta</span>
        </div>

        <div id="accountProfileCard">
            @if(!empty($initialUser))
                <article class="account-profile-card">
                    <div class="account-profile-card__top">
                        <div class="account-profile-card__avatar">{{ $avatarLetter }}</div>

                        <div class="account-profile-card__copy">
                            <h3>{{ $userName !== '' ? $userName : 'Cliente' }}</h3>
                            <p>{{ $userEmail !== '' ? $userEmail : 'Sin correo disponible' }}</p>
                        </div>
                    </div>

                    <div class="account-profile-grid">
                        <article class="account-profile-item">
                            <span>Correo</span>
                            <strong>{{ $userEmail !== '' ? $userEmail : 'Sin correo disponible' }}</strong>
                        </article>

                        <article class="account-profile-item">
                            <span>Tipo de cuenta</span>
                            <strong>{{ $rolesLabel }}</strong>
                        </article>

                        <article class="account-profile-item">
                            <span>ID de usuario</span>
                            <strong>{{ $initialUser['id'] ?? 'No disponible' }}</strong>
                        </article>

                        <article class="account-profile-item">
                            <span>Estado</span>
                            <strong>{{ $profileReady ? 'Cuenta lista para usarse' : 'Requiere revisión' }}</strong>
                        </article>
                    </div>
                </article>
            @else
                <article class="empty-state">
                    No pudimos cargar tu información en este momento.
                </article>
            @endif
        </div>
    </article>

    <article class="panel">
        <div class="section-head">
            <h3>Mis vehículos</h3>
            <span class="section-pill">Importante</span>
        </div>

        <div class="account-note">
            Para pedir ayuda necesitas tener al menos un vehículo registrado. Puedes agregarlo abajo o editar uno existente.
        </div>

        <div id="accountVehiclesList" class="account-vehicles-grid account-vehicles-grid--spaced">
            @forelse($initialVehicles as $vehicle)
                <article class="account-vehicle-card">
                    <h4 class="account-vehicle-card__title">{{ $vehicleLabel($vehicle) }}</h4>
                    <p class="account-vehicle-card__meta">Tipo: {{ $resolveVehicleTypeName($vehicle) }}</p>
                    <p class="account-vehicle-card__meta">Año: {{ $vehicle['year'] ?? 'No disponible' }}</p>

                    <div class="actions-inline">
                        <button type="button" class="button button--ghost" data-edit-vehicle="{{ $vehicle['id'] }}">Editar</button>
                        <button type="button" class="button button--danger" data-delete-vehicle="{{ $vehicle['id'] }}">Eliminar</button>
                    </div>
                </article>
            @empty
                <article class="empty-state">
                    Aún no registras vehículos.
                </article>
            @endforelse
        </div>
    </article>
</section>

<section class="grid-two">
    <article class="panel">
        <div class="section-head">
            <h3>Cambiar correo</h3>
            <span class="section-pill">Acceso</span>
        </div>

        <div class="account-note">
            Usa un correo que revises con frecuencia para no perder información importante de tu cuenta.
        </div>

        <form id="accountEmailForm" class="form-grid account-form-grid--spaced" autocomplete="off">
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
            <h3>Cambiar contraseña</h3>
            <span class="section-pill">Seguridad</span>
        </div>

        <div class="account-note">
            Elige una contraseña segura que puedas recordar y no compartas con otras personas.
        </div>

        <form id="accountPasswordForm" class="form-grid account-form-grid--spaced" autocomplete="off">
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
    <div class="account-vehicles-head">
        <div class="section-head account-section-head--compact">
            <h3>Agregar o editar vehículo</h3>
            <span class="section-pill">Formulario</span>
        </div>

        <button type="button" id="vehicleFormReset" class="button button--ghost">Nuevo</button>
    </div>

    <div class="account-note account-note--spaced">
        Si tocas “Editar” en uno de tus vehículos, este formulario se llenará automáticamente.
    </div>

    <form id="vehicleForm" class="form-grid account-form-grid--spaced" autocomplete="off">
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
