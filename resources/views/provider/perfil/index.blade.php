@extends('provider.layouts.app')

@section('title', 'ZYGA | Perfil provider')
@section('page-title', 'Perfil')

@section('content')
@php
    $data = $perfilResult['data'] ?? null;
    $hasProfile = is_array($data) && !empty($data);
    $statusName = $data['status']['name'] ?? 'Sin estado';
    $isVerified = $data['is_verified'] ?? false;
@endphp

@if(!$hasProfile)
    <section class="section-block">
        <div class="panel-card">
            <h3>Perfil de proveedor no disponible</h3>
            <p>{{ $perfilResult['message'] ?? 'No se encontró un perfil de proveedor para la cuenta autenticada.' }}</p>
            <p class="muted">Este panel ya quedó alineado con la API real. Para continuar, la cuenta debe tener un perfil registrado en <code>/api/v1/provider/profile</code>.</p>
        </div>
    </section>
@else
    <section class="hero-card">
        <div>
            <p class="hero-kicker">Información principal</p>
            <h2>{{ $data['display_name'] ?? 'Proveedor' }}</h2>
            <p class="muted">Actualiza únicamente los campos que la API provider permite modificar desde el panel web.</p>
        </div>
        <div class="{{ $isVerified ? 'pill pill-success' : 'pill pill-warning' }}">
            {{ $isVerified ? 'Verificado' : 'Pendiente de validación' }}
        </div>
    </section>

    <section class="section-block">
        <div class="section-head">
            <h3>Editar perfil</h3>
            <span class="pill">PATCH /provider/profile</span>
        </div>

        <form action="{{ route('provider.perfil.update') }}" method="POST" class="panel-card form-grid">
            @csrf
            @method('PATCH')

            <div class="form-field">
                <label for="display_name">Nombre comercial</label>
                <input type="text" id="display_name" name="display_name" value="{{ old('display_name', $data['display_name'] ?? '') }}" required>
            </div>

            <div class="form-field">
                <label for="provider_kind">Tipo de proveedor</label>
                <input type="text" id="provider_kind" name="provider_kind" value="{{ old('provider_kind', $data['provider_kind'] ?? '') }}" placeholder="Ej. grua, paso_corriente, cerrajeria">
            </div>

            <div class="form-actions form-field-full">
                <button type="submit" class="btn-primary">Guardar cambios</button>
            </div>
        </form>
    </section>

    <section class="section-block">
        <div class="section-head">
            <h3>Estado de la cuenta</h3>
            <span class="pill">Solo lectura</span>
        </div>

        <div class="stack-list">
            <article class="list-card">
                <h4>Estatus del proveedor</h4>
                <p>{{ $statusName }}</p>
            </article>
            <article class="list-card">
                <h4>Servicios asociados</h4>
                <p>{{ count($data['services'] ?? []) }}</p>
            </article>
            <article class="list-card">
                <h4>Horarios registrados</h4>
                <p>{{ count($data['schedules'] ?? []) }}</p>
            </article>
        </div>
    </section>
@endif
@endsection
