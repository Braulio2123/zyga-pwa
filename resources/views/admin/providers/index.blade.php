@extends('adminlte::page')

@section('title', 'Red de proveedores')

@section('content_header')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
    <div>
        <h1 class="m-0">Red de provedores</h1>
        <p class="zyga-muted mb-0">Supervisión de disponibilidad, verificación y preparación operativa de los providers.
        </p>
    </div>
    <div class="mt-3 mt-md-0"><a href="{{ route('admin.dashboard') }}" class="btn btn-light"><i
                class="fas fa-arrow-left mr-1"></i>Volver al dashboard</a></div>
</div>
@stop

@section('content')
@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div> @endif
@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}</div> @endif
@if($apiError)
<div class="alert alert-warning">{{ $apiError }}</div> @endif

@php
    $totalProviders = count($providers);
    $verifiedProviders = count(array_filter($providers, fn($provider) => !empty($provider['is_verified'])));
    $readyProviders = count(array_filter($providers, fn($provider) => !empty($provider['is_verified']) && count($provider['services'] ?? []) > 0 && count($provider['schedules'] ?? []) > 0 && count($provider['documents'] ?? []) > 0));
    $pendingProviders = $totalProviders - $readyProviders;
@endphp

<div class="row mb-4">
    <div class="col-md-6 col-xl-3 mb-3">
        <div class="zyga-kpi">
            <div class="kpi-label">Total</div>
            <div class="kpi-value">{{ $totalProviders }}</div>
            <div class="zyga-muted">Providers registrados</div>
            <div class="kpi-icon"><i class="fas fa-users-cog"></i></div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3 mb-3">
        <div class="zyga-kpi">
            <div class="kpi-label">Verificados</div>
            <div class="kpi-value">{{ $verifiedProviders }}</div>
            <div class="zyga-muted">Con validación administrativa</div>
            <div class="kpi-icon"><i class="fas fa-shield-check"></i></div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3 mb-3">
        <div class="zyga-kpi">
            <div class="kpi-label">Listos para operar</div>
            <div class="kpi-value">{{ $readyProviders }}</div>
            <div class="zyga-muted">Con servicios, horarios y documentos</div>
            <div class="kpi-icon"><i class="fas fa-bolt"></i></div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3 mb-3">
        <div class="zyga-kpi">
            <div class="kpi-label">Pendientes</div>
            <div class="kpi-value">{{ $pendingProviders }}</div>
            <div class="zyga-muted">Requieren acción administrativa</div>
            <div class="kpi-icon"><i class="fas fa-user-clock"></i></div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center">
        <div class="mb-2 mb-md-0">
            <h3 class="zyga-section-title mb-1">Listado de providers</h3>
            <span class="badge badge-pill badge-soft-dark">{{ $totalProviders }} registros</span>
        </div>

        <div class="d-flex flex-column flex-md-row">
            <a href="{{ route('admin.exportaciones.providers.excel') }}"
                class="btn btn-success btn-sm mr-md-2 mb-2 mb-md-0">
                <i class="fas fa-file-excel mr-1"></i>Exportar Excel
            </a>
            <a href="{{ route('admin.exportaciones.providers.pdf') }}" class="btn btn-danger btn-sm">
                <i class="fas fa-file-pdf mr-1"></i>Exportar PDF
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        @if(empty($providers))
            <div class="zyga-empty">No hay proveedores para mostrar.</div>
        @else
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Proveedor</th>
                            <th>Tipo</th>
                            <th>Estatus</th>
                            <th>Verificación</th>
                            <th>Readiness</th>
                            <th class="text-right">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($providers as $provider)
                            @php
                                $isVerified = !empty($provider['is_verified']);
                                $hasServices = count($provider['services'] ?? []) > 0;
                                $hasSchedules = count($provider['schedules'] ?? []) > 0;
                                $hasDocuments = count($provider['documents'] ?? []) > 0;
                                $isReady = $isVerified && $hasServices && $hasSchedules && $hasDocuments;
                                $statusCode = $provider['status']['code'] ?? null;
                                $statusName = $provider['status']['name'] ?? $provider['status']['code'] ?? '—';
                                $statusClass = 'badge-soft-dark';
                                if ($statusCode === 'active' || str_contains(strtolower((string) $statusName), 'activo'))
                                    $statusClass = 'badge-soft-success';
                                elseif ($statusCode === 'inactive' || str_contains(strtolower((string) $statusName), 'inactivo'))
                                    $statusClass = 'badge-soft-danger';
                                else
                                    $statusClass = 'badge-soft-warning';
                            @endphp
                            <tr>
                                <td>
                                    <div class="font-weight-bold">{{ $provider['display_name'] ?? 'Sin nombre comercial' }}
                                    </div>
                                    <div class="zyga-muted small">{{ $provider['user']['email'] ?? 'Sin correo asociado' }}
                                    </div>
                                </td>
                                <td>{{ $provider['provider_kind'] ?? '—' }}</td>
                                <td><span class="badge badge-pill {{ $statusClass }}">{{ $statusName }}</span></td>
                                <td><span
                                        class="badge badge-pill {{ $isVerified ? 'badge-soft-success' : 'badge-soft-warning' }}">{{ $isVerified ? 'Verificado' : 'Pendiente' }}</span>
                                </td>
                                <td><span
                                        class="badge badge-pill {{ $isReady ? 'badge-soft-success' : 'badge-soft-danger' }}">{{ $isReady ? 'Listo para operar' : 'No listo' }}</span>
                                </td>
                                <td class="text-right"><a href="{{ route('admin.providers.show', $provider['id']) }}"
                                        class="btn btn-sm btn-outline-primary">Abrir ficha</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@stop