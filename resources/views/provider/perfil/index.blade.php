@extends('provider.layouts.app')

@section('title', 'ZYGA | Perfil proveedor')
@section('page-title', 'Perfil')

@section('content')
    @php
        $statusName = $badgeData['statusName'] ?? 'Sin estado';
        $verificationText = $badgeData['verificationText'] ?? 'Pendiente';
        $isVerified = $badgeData['isVerified'] ?? false;
    @endphp

    <section class="hero-card">
        <div>
            <p class="hero-kicker">Onboarding y cuenta operativa</p>
            <h2 style="margin:0 0 8px;">{{ $hasProfile ? 'Tu perfil de proveedor' : 'Activa tu perfil provider' }}</h2>
            <p class="muted">
                {{ $hasProfile
                    ? 'Mantén actualizada tu identidad comercial y tu tipo de servicio para operar con coherencia dentro del portal.'
                    : 'Este es el primer paso real para desbloquear el portal provider. Sin este registro la API no te permitirá operar.' }}
            </p>
        </div>

        <div class="hero-stats">
            <div class="hero-stat summary-card">
                <span class="helper-text">Estado</span>
                <strong>{{ $statusName }}</strong>
            </div>
            <div class="hero-stat summary-card">
                <span class="helper-text">Validación</span>
                <strong>{{ $verificationText }}</strong>
            </div>
        </div>
    </section>

    @if(!$hasProfile)
        <section class="section-card">
            <div class="section-head">
                <div>
                    <p class="dashboard-card__eyebrow">Paso 1 de 3</p>
                    <h3>Crear perfil de proveedor</h3>
                </div>
            </div>

            <form action="{{ route('provider.perfil.store') }}" method="POST" class="form-grid">
                @csrf
                <div class="form-field full">
                    <label for="display_name" class="label">Nombre comercial</label>
                    <input type="text" name="display_name" id="display_name" value="{{ old('display_name') }}" placeholder="Ej. Grúas Express Guadalajara" required>
                </div>

                <div class="form-field full">
                    <label for="provider_kind" class="label">Tipo de proveedor</label>
                    <input type="text" name="provider_kind" id="provider_kind" value="{{ old('provider_kind') }}" placeholder="Ej. grua, cerrajeria, bateria, gasolina">
                    <small class="helper-text">Se enviará a la API junto con el estado inicial del proveedor.</small>
                </div>

                <div class="form-field full">
                    <div class="helper-box">
                        <strong>Qué ocurrirá al guardar</strong>
                        <p class="muted" style="margin-top:6px;">
                            Se creará tu <code>provider/profile</code> en la API real. Después podrás elegir servicios, cargar horarios y operar en asistencias.
                        </p>
                    </div>
                </div>

                <div class="form-field full">
                    <button type="submit" class="btn-primary">Crear perfil y continuar</button>
                </div>
            </form>
        </section>
    @else
        <section class="profile-summary-grid">
            <article class="summary-card">
                <span class="status-chip dark">Cuenta</span>
                <strong>{{ $profile['display_name'] ?? 'Sin nombre' }}</strong>
                <p class="muted">Nombre comercial activo en el portal.</p>
            </article>
            <article class="summary-card">
                <span class="status-chip info">Tipo</span>
                <strong>{{ $profile['provider_kind'] ?? 'Sin definir' }}</strong>
                <p class="muted">Clasificación actual del proveedor.</p>
            </article>
            <article class="summary-card">
                <span class="status-chip {{ $isVerified ? 'success' : 'warning' }}">Validación</span>
                <strong>{{ $verificationText }}</strong>
                <p class="muted">Estado de revisión administrativa.</p>
            </article>
        </section>

        <section class="section-card">
            <div class="section-head">
                <div>
                    <p class="dashboard-card__eyebrow">Mantenimiento de cuenta</p>
                    <h3>Actualizar perfil</h3>
                </div>
            </div>

            <form action="{{ route('provider.perfil.update') }}" method="POST" class="form-grid">
                @csrf
                @method('PATCH')

                <div class="form-field full">
                    <label for="display_name" class="label">Nombre comercial</label>
                    <input type="text" name="display_name" id="display_name" value="{{ old('display_name', $profile['display_name'] ?? '') }}" required>
                </div>

                <div class="form-field full">
                    <label for="provider_kind" class="label">Tipo de proveedor</label>
                    <input type="text" name="provider_kind" id="provider_kind" value="{{ old('provider_kind', $profile['provider_kind'] ?? '') }}">
                </div>

                <div class="form-field full">
                    <button type="submit" class="btn-primary">Guardar cambios</button>
                </div>
            </form>
        </section>
    @endif
@endsection
