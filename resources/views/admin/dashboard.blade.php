@extends('adminlte::page')

@section('title', 'Dashboard ZYGA')

@section('content_header')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
        <div>
            <h1 class="m-0">Panel de administración</h1>
            <p class="zyga-muted mb-0">Visión general operativa del ecosistema ZYGA consumiendo la API real.</p>
        </div>
        <div class="mt-3 mt-md-0">
            <a href="{{ route('admin.reportes.index') }}" class="btn btn-light mr-2">
                <i class="fas fa-chart-pie mr-1"></i> Ver reportes
            </a>
            <a href="{{ route('admin.assistance.index') }}" class="btn btn-primary">
                <i class="fas fa-truck-pickup mr-1"></i> Gestionar solicitudes
            </a>
        </div>
    </div>
@stop

@section('content')
    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif
    @if(!empty($apiErrors))
        <div class="alert alert-warning">
            <strong>Se detectaron incidencias parciales al cargar el dashboard:</strong>
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
                <h2 class="mb-2">Operación centralizada para usuarios, proveedores, solicitudes y pagos</h2>
                <p class="mb-0 text-white-50">Este panel ya no muestra datos de relleno: resume información obtenida desde los módulos administrativos reales de la API consolidada.</p>
            </div>
            <div class="col-lg-4 text-lg-right mt-3 mt-lg-0">
                <span class="badge badge-pill badge-soft-primary">Admin web</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-xl-3 mb-3">
            <div class="zyga-kpi">
                <div class="kpi-label">Usuarios</div>
                <div class="kpi-value">{{ number_format($metrics['users']) }}</div>
                <div class="zyga-muted">Cuentas registradas en la plataforma</div>
                <div class="kpi-icon"><i class="fas fa-users"></i></div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3 mb-3">
            <div class="zyga-kpi">
                <div class="kpi-label">Proveedores</div>
                <div class="kpi-value">{{ number_format($metrics['providers']) }}</div>
                <div class="zyga-muted">{{ number_format($metrics['verified_providers']) }} verificados</div>
                <div class="kpi-icon"><i class="fas fa-people-carry"></i></div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3 mb-3">
            <div class="zyga-kpi">
                <div class="kpi-label">Solicitudes</div>
                <div class="kpi-value">{{ number_format($metrics['requests']) }}</div>
                <div class="zyga-muted">{{ number_format($metrics['pending_requests']) }} en curso</div>
                <div class="kpi-icon"><i class="fas fa-route"></i></div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3 mb-3">
            <div class="zyga-kpi">
                <div class="kpi-label">Ingresos completados</div>
                <div class="kpi-value">${{ number_format($metrics['completed_revenue'], 2) }}</div>
                <div class="zyga-muted">Pagos con estado completed</div>
                <div class="kpi-icon"><i class="fas fa-wallet"></i></div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-7 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="zyga-section-title">Solicitudes recientes</h3>
                    <a href="{{ route('admin.assistance.index') }}" class="btn btn-sm btn-outline-primary">Ver todas</a>
                </div>
                <div class="card-body p-0">
                    @if(empty($recentRequests))
                        <div class="zyga-empty">No hay solicitudes disponibles en este momento.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Folio público</th>
                                        <th>Servicio</th>
                                        <th>Estado</th>
                                        <th>Cliente</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentRequests as $request)
                                        @php $status = $request['status'] ?? 'sin_estado'; @endphp
                                        <tr>
                                            <td>{{ $request['id'] ?? '—' }}</td>
                                            <td class="zyga-code">{{ $request['public_id'] ?? '—' }}</td>
                                            <td>{{ $request['service']['name'] ?? 'Sin servicio' }}</td>
                                            <td>
                                                <span class="badge badge-pill {{ in_array($status, ['completed'], true) ? 'badge-soft-success' : (in_array($status, ['cancelled'], true) ? 'badge-soft-danger' : 'badge-soft-warning') }}">
                                                    {{ $status }}
                                                </span>
                                            </td>
                                            <td>{{ $request['user']['email'] ?? '—' }}</td>
                                            <td class="text-right">
                                                <a href="{{ route('admin.assistance.show', $request['id']) }}" class="btn btn-sm btn-light">Detalle</a>
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
        <div class="col-lg-5 mb-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="zyga-section-title">Pagos recientes</h3>
                </div>
                <div class="card-body">
                    @if(empty($recentPayments))
                        <div class="zyga-empty">Todavía no hay pagos registrados.</div>
                    @else
                        <div class="zyga-stat-list">
                            @foreach($recentPayments as $payment)
                                <div class="zyga-stat-item">
                                    <div>
                                        <div class="font-weight-bold">${{ number_format((float) ($payment['amount'] ?? 0), 2) }}</div>
                                        <div class="zyga-muted small">Método: {{ $payment['payment_method'] ?? '—' }} · Estado: {{ $payment['status'] ?? '—' }}</div>
                                    </div>
                                    <a href="{{ route('admin.finance.show-payment', $payment['id']) }}" class="btn btn-sm btn-outline-primary">Ver</a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop