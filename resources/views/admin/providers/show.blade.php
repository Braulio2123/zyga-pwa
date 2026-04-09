@extends('adminlte::page')

@section('title', 'Detalle de proveedor')

@section('content_header')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
        <div>
            <h1 class="m-0">Proveedor #{{ $provider['id'] ?? '—' }}</h1>
            <p class="zyga-muted mb-0">
                Revisión administrativa del perfil, validación y cobertura operativa del proveedor.
            </p>
        </div>

        <div class="mt-3 mt-md-0">
            <a href="{{ route('admin.providers.index') }}" class="btn btn-light">
                <i class="fas fa-arrow-left mr-1"></i>
                Volver a proveedores
            </a>
        </div>
    </div>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Corrige los siguientes errores:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @php
        $statusCode = $provider['status']['code'] ?? null;
        $statusName = $provider['status']['name'] ?? $provider['status']['code'] ?? '—';
        $isVerified = !empty($provider['is_verified']);

        $statusClass = 'badge-soft-dark';
        if ($statusCode === 'active' || str_contains(strtolower((string) $statusName), 'activo')) {
            $statusClass = 'badge-soft-success';
        } elseif ($statusCode === 'inactive' || str_contains(strtolower((string) $statusName), 'inactivo')) {
            $statusClass = 'badge-soft-danger';
        } else {
            $statusClass = 'badge-soft-warning';
        }
    @endphp

    <div class="zyga-hero mb-4">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h2 class="mb-2">{{ $provider['display_name'] ?? 'Proveedor sin nombre comercial' }}</h2>
                <p class="mb-0 text-white-50">
                    Administra los datos clave del proveedor, su verificación, tipo de operación y
                    los elementos asociados a su perfil.
                </p>
            </div>

            <div class="col-lg-4 text-lg-right mt-3 mt-lg-0">
                <div class="mb-2">
                    <span class="badge badge-pill {{ $statusClass }}">
                        {{ $statusName }}
                    </span>
                </div>
                <span class="badge badge-pill {{ $isVerified ? 'badge-soft-success' : 'badge-soft-warning' }}">
                    {{ $isVerified ? 'Verificado' : 'Pendiente de validación' }}
                </span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h3 class="zyga-section-title">Editar proveedor</h3>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('admin.providers.update', $provider['id']) }}">
                        @csrf
                        @method('PATCH')

                        <div class="form-group">
                            <label for="display_name">Nombre comercial</label>
                            <input
                                type="text"
                                name="display_name"
                                id="display_name"
                                class="form-control"
                                value="{{ old('display_name', $provider['display_name'] ?? '') }}"
                                required
                            >
                        </div>

                        <div class="form-group">
                            <label for="provider_kind">Tipo de proveedor</label>
                            <input
                                type="text"
                                name="provider_kind"
                                id="provider_kind"
                                class="form-control"
                                value="{{ old('provider_kind', $provider['provider_kind'] ?? '') }}"
                                placeholder="Ej. grua, mecanica, gasolina, cerrajeria"
                            >
                        </div>

                        <div class="form-group">
                            <label for="status_id">Status ID</label>
                            <input
                                type="number"
                                name="status_id"
                                id="status_id"
                                class="form-control"
                                value="{{ old('status_id', $provider['status_id'] ?? '') }}"
                                placeholder="Opcional"
                            >
                        </div>

                        <div class="form-group mb-4">
                            <div class="custom-control custom-switch">
                                <input
                                    type="checkbox"
                                    class="custom-control-input"
                                    id="is_verified"
                                    name="is_verified"
                                    value="1"
                                    @checked(old('is_verified', $isVerified))
                                >
                                <label class="custom-control-label" for="is_verified">
                                    Marcar proveedor como verificado
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-save mr-1"></i>
                            Guardar cambios
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-xl-8 mb-4">
            <div class="row">
                <div class="col-md-6 col-xl-3 mb-3">
                    <div class="zyga-kpi">
                        <div class="kpi-label">Servicios</div>
                        <div class="kpi-value">{{ count($provider['services'] ?? []) }}</div>
                        <div class="zyga-muted">Servicios asociados</div>
                        <div class="kpi-icon">
                            <i class="fas fa-concierge-bell"></i>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-xl-3 mb-3">
                    <div class="zyga-kpi">
                        <div class="kpi-label">Horarios</div>
                        <div class="kpi-value">{{ count($provider['schedules'] ?? []) }}</div>
                        <div class="zyga-muted">Bloques disponibles</div>
                        <div class="kpi-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-xl-3 mb-3">
                    <div class="zyga-kpi">
                        <div class="kpi-label">Documentos</div>
                        <div class="kpi-value">{{ count($provider['documents'] ?? []) }}</div>
                        <div class="zyga-muted">Archivos vinculados</div>
                        <div class="kpi-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-xl-3 mb-3">
                    <div class="zyga-kpi">
                        <div class="kpi-label">Validación</div>
                        <div class="kpi-value">{{ $isVerified ? 'Sí' : 'No' }}</div>
                        <div class="zyga-muted">Estatus administrativo</div>
                        <div class="kpi-icon">
                            <i class="fas fa-user-check"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="zyga-section-title">Resumen general</h3>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>ID interno</label>
                            <div class="zyga-code">{{ $provider['id'] ?? '—' }}</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Correo asociado</label>
                            <div>{{ $provider['user']['email'] ?? '—' }}</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Nombre comercial</label>
                            <div>{{ $provider['display_name'] ?? '—' }}</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Tipo de proveedor</label>
                            <div>{{ $provider['provider_kind'] ?? '—' }}</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Estatus</label>
                            <div>
                                <span class="badge badge-pill {{ $statusClass }}">
                                    {{ $statusName }}
                                </span>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Verificación</label>
                            <div>
                                <span class="badge badge-pill {{ $isVerified ? 'badge-soft-success' : 'badge-soft-warning' }}">
                                    {{ $isVerified ? 'Verificado' : 'Pendiente' }}
                                </span>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Creado</label>
                            <div>{{ $provider['created_at'] ?? '—' }}</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Última actualización</label>
                            <div>{{ $provider['updated_at'] ?? '—' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="zyga-section-title">Servicios asociados</h3>
                </div>

                <div class="card-body">
                    @forelse(($provider['services'] ?? []) as $service)
                        <span class="badge badge-pill badge-soft-primary mr-1 mb-1">
                            {{ $service['name'] ?? $service['code'] ?? 'Servicio' }}
                        </span>
                    @empty
                        <div class="zyga-empty py-3">
                            No tiene servicios asociados actualmente.
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="zyga-section-title">Lectura operativa</h3>
                </div>

                <div class="card-body">
                    <div class="zyga-stat-list">
                        <div class="zyga-stat-item">
                            <div>
                                <div class="font-weight-bold">Cobertura de servicios</div>
                                <div class="zyga-muted small">
                                    Total de servicios que puede atender este proveedor
                                </div>
                            </div>
                            <span class="badge badge-pill badge-soft-accent">
                                {{ count($provider['services'] ?? []) }}
                            </span>
                        </div>

                        <div class="zyga-stat-item">
                            <div>
                                <div class="font-weight-bold">Disponibilidad horaria</div>
                                <div class="zyga-muted small">
                                    Horarios registrados para operación
                                </div>
                            </div>
                            <span class="badge badge-pill badge-soft-primary">
                                {{ count($provider['schedules'] ?? []) }}
                            </span>
                        </div>

                        <div class="zyga-stat-item">
                            <div>
                                <div class="font-weight-bold">Documentación</div>
                                <div class="zyga-muted small">
                                    Archivos o comprobantes asociados al proveedor
                                </div>
                            </div>
                            <span class="badge badge-pill badge-soft-dark">
                                {{ count($provider['documents'] ?? []) }}
                            </span>
                        </div>

                        <div class="zyga-stat-item">
                            <div>
                                <div class="font-weight-bold">Validación administrativa</div>
                                <div class="zyga-muted small">
                                    Indica si el proveedor ya fue aprobado para operar
                                </div>
                            </div>
                            <span class="badge badge-pill {{ $isVerified ? 'badge-soft-success' : 'badge-soft-warning' }}">
                                {{ $isVerified ? 'Aprobado' : 'Pendiente' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
