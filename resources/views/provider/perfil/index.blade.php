@extends('provider.layouts.app')

@section('title', 'Zyga | Perfil del proveedor')
@section('page-title', 'Perfil')

@section('content')

    @php
        $data = $perfil['data'] ?? [];

        $displayName = $data['display_name'] ?? 'Proveedor sin nombre';
        $providerKind = $data['provider_kind'] ?? 'No definido';
        $statusId = $data['status_id'] ?? null;
        $isVerified = $data['is_verified'] ?? null;
        $email = $data['email'] ?? session('user.email') ?? 'Sin correo';

        $statusText = match((int) $statusId) {
            1 => 'Activo',
            2 => 'En revisión',
            3 => 'Suspendido',
            default => 'Sin estado',
        };

        $verificationText = is_null($isVerified)
            ? 'Sin validar'
            : ($isVerified ? 'Verificado' : 'Pendiente');

        $verificationClass = is_null($isVerified)
            ? 'pill'
            : ($isVerified ? 'pill pill-success' : 'pill pill-warning');
    @endphp

    @if(!empty($fallback))
        <section class="section-block">
            <div class="panel-card">
                <h3>Modo temporal</h3>
                <p>{{ $apiError['message'] ?? 'No se pudo cargar la información desde la API.' }}</p>

                @if(!empty($apiError['details']))
                    <p class="muted">{{ $apiError['details'] }}</p>
                @endif
            </div>
        </section>
    @endif

    <section class="hero-card">
        <div>
            <p class="hero-kicker">Perfil del proveedor</p>
            <h2>{{ $displayName }}</h2>
            <p class="muted">
                Consulta la información principal de tu cuenta de proveedor dentro de Zyga.
            </p>
        </div>

        <div class="{{ $verificationClass }}">
            {{ $verificationText }}
        </div>
    </section>

    <section class="section-block">
        <div class="section-head">
            <h3>Información general</h3>
            <span class="pill">{{ $statusText }}</span>
        </div>

        <div class="panel-card form-grid">
            <div class="form-field">
                <label>Nombre comercial</label>
                <input type="text" value="{{ $displayName }}" readonly>
            </div>

            <div class="form-field">
                <label>Tipo de proveedor</label>
                <input type="text" value="{{ $providerKind }}" readonly>
            </div>

            <div class="form-field">
                <label>Estado</label>
                <input type="text" value="{{ $statusText }}" readonly>
            </div>

            <div class="form-field">
                <label>Verificación</label>
                <input type="text" value="{{ $verificationText }}" readonly>
            </div>

            <div class="form-field form-field-full">
                <label>Correo electrónico</label>
                <input type="text" value="{{ $email }}" readonly>
            </div>
        </div>
    </section>

    <section class="section-block">
        <div class="section-head">
            <h3>Resumen de cuenta</h3>
            <span class="pill">Solo lectura</span>
        </div>

        <div class="stack-list">
            <article class="list-card">
                <h4>Nombre del usuario</h4>
                <p>{{ session('user.name') }}</p>
            </article>

            <article class="list-card">
                <h4>Rol actual</h4>
                <p>{{ session('user.role') }}</p>
            </article>

            <article class="list-card">
                <h4>Proveedor registrado como</h4>
                <p>{{ $providerKind }}</p>
            </article>
        </div>
    </section>
@endsection