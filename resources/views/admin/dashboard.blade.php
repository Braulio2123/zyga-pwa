@extends('adminlte::page')

@section('title', 'Dashboard ZYGA')

@section('content_header')
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center">
        <div>
            <h1 class="m-0">Dashboard administrativo</h1>
            <p class="zyga-muted mb-0">
                Supervisión general de usuarios, proveedores, solicitudes, servicios y pagos.
            </p>
        </div>

        <div class="mt-3 mt-lg-0 d-flex flex-wrap">
            <a href="{{ route('admin.reportes.index') }}" class="btn btn-light mr-2 mb-2 mb-lg-0">
                <i class="fas fa-chart-pie mr-1"></i>
                Reportes
            </a>

            <a href="{{ route('admin.assistance.index') }}" class="btn btn-primary mb-2 mb-lg-0">
                <i class="fas fa-truck-pickup mr-1"></i>
                Ver solicitudes
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

    @if(!empty($apiErrors))
        <div class="alert alert-warning">
            <strong>Se detectaron incidencias parciales al consultar la API:</strong>
            <ul class="mb-0 mt-2">
                @foreach($apiErrors as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="zyga-hero mb-4">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h2 class="mb-2">Centro de control ZYGA</h2>
                <p class="mb-0 text-white-50">
                    Desde aquí puedes monitorear la operación general del sistema y detectar rápidamente
                    solicitudes activas, proveedores verificados, servicios habilitados e ingresos registrados.
                </p>
            </div>
            <div class="col-lg-4 text-lg-right mt-3 mt-lg-0">
                <span class="badge badge-pill badge-soft-primary">Administrador</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-xl-3 mb-3">
            <div class="zyga-kpi">
                <div class="kpi-label">Usuarios</div>
                <div class="kpi-value">{{ number_format($metrics['users']) }}</div>
                <div class="zyga-muted">Cuentas registradas en la plataforma</div>
                <div class="kpi-icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3 mb-3">
            <div class="zyga-kpi">
                <div class="kpi-label">Proveedores</div>
                <div class="kpi-value">{{ number_format($metrics['providers']) }}</div>
                <div class="zyga-muted">
                    {{ number_format($metrics['verified_providers']) }} verificados
                </div>
                <div class="kpi-icon">
                    <i class="fas fa-people-carry"></i>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3 mb-3">
            <div class="zyga-kpi">
                <div class="kpi-label">Solicitudes</div>
                <div class="kpi-value">{{ number_format($metrics['requests']) }}</div>
                <div class="zyga-muted">
                    {{ number_format($metrics['pending_requests']) }} en curso
                </div>
                <div class="kpi-icon">
                    <i class="fas fa-route"></i>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3 mb-3">
            <div class="zyga-kpi">
                <div class="kpi-label">Ingresos completados</div>
                <div class="kpi-value">${{ number_format($metrics['completed_revenue'], 2) }}</div>
                <div class="zyga-muted">Pagos confirmados como completed</div>
                <div class="kpi-icon">
                    <i class="fas fa-wallet"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h3 class="zyga-section-title">Resumen operativo</h3>
                </div>
                <div class="card-body">
                    <div class="zyga-stat-list">
                        <div class="zyga-stat-item">
                            <div>
                                <div class="font-weight-bold">Proveedores verificados</div>
                                <div class="zyga-muted small">Listos para participar en la operación</div>
                            </div>
                            <span class="badge badge-pill badge-soft-success">
                                {{ number_format($metrics['verified_providers']) }}
                            </span>
                        </div>

                        <div class="zyga-stat-item">
                            <div>
                                <div class="font-weight-bold">Servicios activos</div>
                                <div class="zyga-muted small">Catálogo habilitado para solicitudes</div>
                            </div>
                            <span class="badge badge-pill badge-soft-accent">
                                {{ number_format($metrics['active_services']) }}
                            </span>
                        </div>

                        <div class="zyga-stat-item">
                            <div>
                                <div class="font-weight-bold">Solicitudes en operación</div>
                                <div class="zyga-muted small">Solicitudes creadas, asignadas o en progreso</div>
                            </div>
                            <span class="badge badge-pill badge-soft-warning">
                                {{ number_format($metrics['pending_requests']) }}
                            </span>
                        </div>

                        <div class="zyga-stat-item">
                            <div>
                                <div class="font-weight-bold">Pagos registrados</div>
                                <div class="zyga-muted small">Pagos detectados por el módulo financiero</div>
                            </div>
                            <span class="badge badge-pill badge-soft-primary">
                                {{ number_format($metrics['payments']) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="zyga-section-title">Solicitudes recientes</h3>
                    <a href="{{ route('admin.assistance.index') }}" class="btn btn-sm btn-outline-primary">
                        Ver todas
                    </a>
                </div>

                <div class="card-body p-0">
                    @if(empty($recentRequests))
                        <div class="zyga-empty">
                            No hay solicitudes disponibles en este momento.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Folio</th>
                                        <th>Servicio</th>
                                        <th>Estado</th>
                                        <th>Cliente</th>
                                        <th class="text-right">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentRequests as $request)
                                        @php
                                            $status = $request['status'] ?? 'sin_estado';

                                            $statusClass = 'badge-soft-dark';

                                            if (in_array($status, ['completed'], true)) {
                                                $statusClass = 'badge-soft-success';
                                            } elseif (in_array($status, ['cancelled'], true)) {
                                                $statusClass = 'badge-soft-danger';
                                            } elseif (in_array($status, ['created', 'assigned', 'in_progress'], true)) {
                                                $statusClass = 'badge-soft-warning';
                                            }
                                        @endphp

                                        <tr>
                                            <td>{{ $request['id'] ?? '—' }}</td>
                                            <td class="zyga-code">{{ $request['public_id'] ?? '—' }}</td>
                                            <td>{{ $request['service']['name'] ?? 'Sin servicio' }}</td>
                                            <td>
                                                <span class="badge badge-pill {{ $statusClass }}">
                                                    {{ $status }}
                                                </span>
                                            </td>
                                            <td>{{ $request['user']['email'] ?? '—' }}</td>
                                            <td class="text-right">
                                                <a href="{{ route('admin.assistance.show', $request['id']) }}"
                                                   class="btn btn-sm btn-light">
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
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="zyga-section-title">Pagos recientes</h3>
                    <a href="{{ route('admin.finance.index') }}" class="btn btn-sm btn-outline-primary">
                        Ver finanzas
                    </a>
                </div>

                <div class="card-body">
                    @if(empty($recentPayments))
                        <div class="zyga-empty">
                            Todavía no hay pagos registrados.
                        </div>
                    @else
                        <div class="zyga-stat-list">
                            @foreach($recentPayments as $payment)
                                <div class="zyga-stat-item">
                                    <div>
                                        <div class="font-weight-bold">
                                            ${{ number_format((float) ($payment['amount'] ?? 0), 2) }}
                                        </div>
                                        <div class="zyga-muted small">
                                            Método: {{ $payment['payment_method'] ?? '—' }}
                                            · Estado: {{ $payment['status'] ?? '—' }}
                                        </div>
                                    </div>

                                    <a href="{{ route('admin.finance.show-payment', $payment['id']) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        Ver
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h3 class="zyga-section-title">Accesos rápidos</h3>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('admin.providers.index') }}" class="btn btn-light btn-block py-3">
                                <i class="fas fa-people-carry d-block mb-2"></i>
                                Administrar proveedores
                            </a>
                        </div>

                        <div class="col-md-6 mb-3">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-light btn-block py-3">
                                <i class="fas fa-users d-block mb-2"></i>
                                Administrar usuarios
                            </a>
                        </div>

                        <div class="col-md-6 mb-3">
                            <a href="{{ route('admin.services.index') }}" class="btn btn-light btn-block py-3">
                                <i class="fas fa-concierge-bell d-block mb-2"></i>
                                Administrar servicios
                            </a>
                        </div>

                        <div class="col-md-6 mb-3">
                            <a href="{{ route('admin.reportes.index') }}" class="btn btn-light btn-block py-3">
                                <i class="fas fa-chart-line d-block mb-2"></i>
                                Revisar reportes
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
