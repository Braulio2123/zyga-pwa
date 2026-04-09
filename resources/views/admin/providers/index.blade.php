@extends('adminlte::page')

@section('title', 'Proveedores')

@section('content_header')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
        <div>
            <h1 class="m-0">Proveedores</h1>
            <p class="zyga-muted mb-0">
                Administración y seguimiento operativo de proveedores registrados.
            </p>
        </div>

        <div class="mt-3 mt-md-0">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-light">
                <i class="fas fa-arrow-left mr-1"></i>
                Volver al dashboard
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

    @if($apiError)
        <div class="alert alert-warning">{{ $apiError }}</div>
    @endif

    @php
        $totalProviders = count($providers);
        $verifiedProviders = 0;
        $unverifiedProviders = 0;
        $activeProviders = 0;
        $inactiveProviders = 0;

        foreach ($providers as $provider) {
            if (!empty($provider['is_verified'])) {
                $verifiedProviders++;
            } else {
                $unverifiedProviders++;
            }

            $statusCode = $provider['status']['code'] ?? null;
            $statusName = strtolower((string) ($provider['status']['name'] ?? ''));

            if ($statusCode === 'active' || str_contains($statusName, 'activo')) {
                $activeProviders++;
            } else {
                $inactiveProviders++;
            }
        }
    @endphp

    <div class="zyga-hero mb-4">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h2 class="mb-2">Panel de proveedores</h2>
                <p class="mb-0 text-white-50">
                    Consulta rápidamente el estado operativo, validación y nivel de cobertura
                    de los proveedores registrados en la plataforma.
                </p>
            </div>
            <div class="col-lg-4 text-lg-right mt-3 mt-lg-0">
                <span class="badge badge-pill badge-soft-primary">
                    {{ $totalProviders }} proveedores
                </span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-xl-3 mb-3">
            <div class="zyga-kpi">
                <div class="kpi-label">Total</div>
                <div class="kpi-value">{{ $totalProviders }}</div>
                <div class="zyga-muted">Proveedores registrados</div>
                <div class="kpi-icon">
                    <i class="fas fa-people-carry"></i>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3 mb-3">
            <div class="zyga-kpi">
                <div class="kpi-label">Verificados</div>
                <div class="kpi-value">{{ $verifiedProviders }}</div>
                <div class="zyga-muted">Con validación administrativa</div>
                <div class="kpi-icon">
                    <i class="fas fa-user-check"></i>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3 mb-3">
            <div class="zyga-kpi">
                <div class="kpi-label">No verificados</div>
                <div class="kpi-value">{{ $unverifiedProviders }}</div>
                <div class="zyga-muted">Pendientes de validación</div>
                <div class="kpi-icon">
                    <i class="fas fa-user-clock"></i>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3 mb-3">
            <div class="zyga-kpi">
                <div class="kpi-label">Activos</div>
                <div class="kpi-value">{{ $activeProviders }}</div>
                <div class="zyga-muted">Con estatus operativo activo</div>
                <div class="kpi-icon">
                    <i class="fas fa-bolt"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center">
            <h3 class="zyga-section-title mb-2 mb-md-0">Listado de proveedores</h3>

            <span class="badge badge-pill badge-soft-dark">
                {{ $totalProviders }} registros
            </span>
        </div>

        <div class="card-body p-0">
            @if(empty($providers))
                <div class="zyga-empty">
                    No hay proveedores para mostrar.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Proveedor</th>
                                <th>Tipo</th>
                                <th>Estatus</th>
                                <th>Verificación</th>
                                <th>Servicios</th>
                                <th>Horarios</th>
                                <th>Documentos</th>
                                <th class="text-right">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($providers as $provider)
                                @php
                                    $isVerified = !empty($provider['is_verified']);
                                    $statusCode = $provider['status']['code'] ?? null;
                                    $statusName = $provider['status']['name'] ?? $provider['status']['code'] ?? '—';

                                    $statusClass = 'badge-soft-dark';
                                    if ($statusCode === 'active' || str_contains(strtolower((string) $statusName), 'activo')) {
                                        $statusClass = 'badge-soft-success';
                                    } elseif ($statusCode === 'inactive' || str_contains(strtolower((string) $statusName), 'inactivo')) {
                                        $statusClass = 'badge-soft-danger';
                                    } else {
                                        $statusClass = 'badge-soft-warning';
                                    }
                                @endphp

                                <tr>
                                    <td>{{ $provider['id'] ?? '—' }}</td>

                                    <td>
                                        <div class="font-weight-bold">
                                            {{ $provider['display_name'] ?? 'Sin nombre comercial' }}
                                        </div>
                                        <div class="zyga-muted small">
                                            {{ $provider['user']['email'] ?? 'Sin correo asociado' }}
                                        </div>
                                    </td>

                                    <td>{{ $provider['provider_kind'] ?? '—' }}</td>

                                    <td>
                                        <span class="badge badge-pill {{ $statusClass }}">
                                            {{ $statusName }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="badge badge-pill {{ $isVerified ? 'badge-soft-success' : 'badge-soft-warning' }}">
                                            {{ $isVerified ? 'Verificado' : 'Pendiente' }}
                                        </span>
                                    </td>

                                    <td>{{ count($provider['services'] ?? []) }}</td>
                                    <td>{{ count($provider['schedules'] ?? []) }}</td>
                                    <td>{{ count($provider['documents'] ?? []) }}</td>

                                    <td class="text-right">
                                        <a href="{{ route('admin.providers.show', $provider['id']) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            Detalle
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@stop
