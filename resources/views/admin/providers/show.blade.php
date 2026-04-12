@extends('adminlte::page')

@section('title', 'Ficha de provider')

@section('content_header')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
        <div>
            <h1 class="m-0">Ficha del provider #{{ $provider['id'] ?? '—' }}</h1>
            <p class="zyga-muted mb-0">Control administrativo del perfil, validación y preparación para entrar a operación.</p>
        </div>
        <div class="mt-3 mt-md-0"><a href="{{ route('admin.providers.index') }}" class="btn btn-light"><i class="fas fa-arrow-left mr-1"></i>Volver a providers</a></div>
    </div>
@stop

@section('content')
    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif
    @if($errors->any())
        <div class="alert alert-danger"><strong>Corrige los siguientes errores:</strong><ul class="mb-0 mt-2">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif

    @php
        $statusCode = $provider['status']['code'] ?? null;
        $statusName = $provider['status']['name'] ?? $provider['status']['code'] ?? '—';
        $isVerified = !empty($provider['is_verified']);
        $hasServices = count($provider['services'] ?? []) > 0;
        $hasSchedules = count($provider['schedules'] ?? []) > 0;
        $hasDocuments = count($provider['documents'] ?? []) > 0;
        $isReady = $isVerified && $hasServices && $hasSchedules && $hasDocuments;
        $statusClass = 'badge-soft-dark';
        if ($statusCode === 'active' || str_contains(strtolower((string) $statusName), 'activo')) $statusClass = 'badge-soft-success';
        elseif ($statusCode === 'inactive' || str_contains(strtolower((string) $statusName), 'inactivo')) $statusClass = 'badge-soft-danger';
        else $statusClass = 'badge-soft-warning';
    @endphp

    <div class="zyga-hero mb-4">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h2 class="mb-2">{{ $provider['display_name'] ?? 'Proveedor sin nombre comercial' }}</h2>
                <p class="mb-0 text-white-50">Usa esta ficha para validar si el provider puede operar de manera segura y coherente dentro del MVP.</p>
            </div>
            <div class="col-lg-4 text-lg-right mt-3 mt-lg-0">
                <div class="mb-2"><span class="badge badge-pill {{ $statusClass }}">{{ $statusName }}</span></div>
                <span class="badge badge-pill {{ $isReady ? 'badge-soft-success' : 'badge-soft-danger' }}">{{ $isReady ? 'Listo para operar' : 'No listo para operar' }}</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4 mb-4">
            <div class="card h-100">
                <div class="card-header"><h3 class="zyga-section-title">Acción administrativa</h3></div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.providers.update', $provider['id']) }}">
                        @csrf
                        @method('PATCH')
                        <div class="form-group">
                            <label for="display_name">Nombre comercial</label>
                            <input type="text" name="display_name" id="display_name" class="form-control" value="{{ old('display_name', $provider['display_name'] ?? '') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="provider_kind">Tipo de provider</label>
                            <input type="text" name="provider_kind" id="provider_kind" class="form-control" value="{{ old('provider_kind', $provider['provider_kind'] ?? '') }}" placeholder="Ej. grua, gasolina, cerrajeria">
                        </div>
                        <div class="form-group">
                            <label for="status_id">Status ID</label>
                            <input type="number" name="status_id" id="status_id" class="form-control" value="{{ old('status_id', $provider['status_id'] ?? '') }}" placeholder="Opcional. Solo si sabes el ID correcto del estatus.">
                            <small class="form-text text-muted">Mientras la API siga esperando <code>status_id</code>, este campo se mantiene técnico. Conviene abstraerlo después con catálogos.</small>
                        </div>
                        <div class="form-group mb-4">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="is_verified" name="is_verified" value="1" @checked(old('is_verified', $isVerified))>
                                <label class="custom-control-label" for="is_verified">Marcar como verificado</label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-save mr-1"></i>Guardar cambios</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-xl-8 mb-4">
            <div class="row">
                <div class="col-md-6 mb-3"><div class="zyga-kpi"><div class="kpi-label">Servicios</div><div class="kpi-value">{{ count($provider['services'] ?? []) }}</div><div class="zyga-muted">Cobertura declarada</div><div class="kpi-icon"><i class="fas fa-tools"></i></div></div></div>
                <div class="col-md-6 mb-3"><div class="zyga-kpi"><div class="kpi-label">Horarios</div><div class="kpi-value">{{ count($provider['schedules'] ?? []) }}</div><div class="zyga-muted">Disponibilidad cargada</div><div class="kpi-icon"><i class="fas fa-clock"></i></div></div></div>
                <div class="col-md-6 mb-3"><div class="zyga-kpi"><div class="kpi-label">Documentos</div><div class="kpi-value">{{ count($provider['documents'] ?? []) }}</div><div class="zyga-muted">Archivos asociados</div><div class="kpi-icon"><i class="fas fa-folder-open"></i></div></div></div>
                <div class="col-md-6 mb-3"><div class="zyga-kpi"><div class="kpi-label">Verificación</div><div class="kpi-value">{{ $isVerified ? 'Sí' : 'No' }}</div><div class="zyga-muted">Estado administrativo</div><div class="kpi-icon"><i class="fas fa-id-badge"></i></div></div></div>
            </div>

            <div class="card mb-4">
                <div class="card-header"><h3 class="zyga-section-title">Checklist de readiness</h3></div>
                <div class="card-body">
                    <div class="zyga-stat-list">
                        <div class="zyga-stat-item"><div><div class="font-weight-bold">Perfil verificado</div><div class="zyga-muted small">Validación administrativa</div></div><span class="badge badge-pill {{ $isVerified ? 'badge-soft-success' : 'badge-soft-danger' }}">{{ $isVerified ? 'Correcto' : 'Pendiente' }}</span></div>
                        <div class="zyga-stat-item"><div><div class="font-weight-bold">Servicios cargados</div><div class="zyga-muted small">Debe indicar qué asistencias puede atender</div></div><span class="badge badge-pill {{ $hasServices ? 'badge-soft-success' : 'badge-soft-danger' }}">{{ $hasServices ? 'Correcto' : 'Faltante' }}</span></div>
                        <div class="zyga-stat-item"><div><div class="font-weight-bold">Horarios configurados</div><div class="zyga-muted small">Necesarios para criterio operativo</div></div><span class="badge badge-pill {{ $hasSchedules ? 'badge-soft-success' : 'badge-soft-danger' }}">{{ $hasSchedules ? 'Correcto' : 'Faltante' }}</span></div>
                        <div class="zyga-stat-item"><div><div class="font-weight-bold">Documentos disponibles</div><div class="zyga-muted small">Soporte mínimo de validación</div></div><span class="badge badge-pill {{ $hasDocuments ? 'badge-soft-success' : 'badge-soft-danger' }}">{{ $hasDocuments ? 'Correcto' : 'Faltante' }}</span></div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h3 class="zyga-section-title">Resumen del provider</h3></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3"><label>Email</label><div>{{ $provider['user']['email'] ?? '—' }}</div></div>
                        <div class="col-md-6 mb-3"><label>Tipo</label><div>{{ $provider['provider_kind'] ?? '—' }}</div></div>
                        <div class="col-md-6 mb-3"><label>Status actual</label><div>{{ $statusName }}</div></div>
                        <div class="col-md-6 mb-3"><label>Provider ID</label><div>{{ $provider['id'] ?? '—' }}</div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
