@extends('adminlte::page')

@section('title', 'Centro operativo ZYGA')

@section('content_header')
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center">
        <div>
            <h1 class="m-0">Centro operativo ZYGA</h1>
            <p class="zyga-muted mb-0">Monitoreo ejecutivo del MVP: operación, red de proveedores, pagos y capacidad de respuesta.</p>
        </div>

        <div class="mt-3 mt-lg-0 d-flex flex-wrap">
            <a href="{{ route('admin.assistance.index') }}" class="btn btn-primary mr-2 mb-2 mb-lg-0">
                <i class="fas fa-route mr-1"></i>
                Revisar operación
            </a>
            <a href="{{ route('admin.providers.index') }}" class="btn btn-light mb-2 mb-lg-0">
                <i class="fas fa-people-carry mr-1"></i>
                Revisar proveedores
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
            <strong>La lectura del tablero es parcial.</strong>
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
                <h2 class="mb-2">Visión general del sistema</h2>
                <p class="mb-0 text-white-50">
                    Este panel prioriza lo que más importa para operar el MVP: solicitudes en cola, servicios en curso,
                    proveedores que todavía no están listos y comportamiento del módulo financiero.
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
                <div class="kpi-label">Solicitudes activas</div>
                <div class="kpi-value">{{ number_format($metrics['queue_requests'] + $metrics['assigned_requests'] + $metrics['in_progress_requests']) }}</div>
                <div class="zyga-muted">En cola, asignadas o en curso</div>
                <div class="kpi-icon"><i class="fas fa-broadcast-tower"></i></div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3 mb-3">
            <div class="zyga-kpi">
                <div class="kpi-label">Proveedores no listos</div>
                <div class="kpi-value">{{ number_format($metrics['not_ready_providers']) }}</div>
                <div class="zyga-muted">Pendientes de verificación o configuración</div>
                <div class="kpi-icon"><i class="fas fa-user-clock"></i></div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3 mb-3">
            <div class="zyga-kpi">
                <div class="kpi-label">Ingresos completados</div>
                <div class="kpi-value">${{ number_format($metrics['completed_revenue'], 2) }}</div>
                <div class="zyga-muted">Pagos confirmados</div>
                <div class="kpi-icon"><i class="fas fa-wallet"></i></div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3 mb-3">
            <div class="zyga-kpi">
                <div class="kpi-label">Pagos fallidos</div>
                <div class="kpi-value">{{ number_format($metrics['failed_payments']) }}</div>
                <div class="zyga-muted">Casos a revisar</div>
                <div class="kpi-icon"><i class="fas fa-exclamation-triangle"></i></div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-7 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="zyga-section-title mb-0">Solicitudes por estado</h3>
                    <span class="zyga-muted small">Gráfica de barras</span>
                </div>
                <div class="card-body">
                    <div style="height: 320px;">
                        <canvas id="requestsStatusBarChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="zyga-section-title mb-0">Salud operativa</h3>
                    <span class="zyga-muted small">Gráfica de dona</span>
                </div>
                <div class="card-body">
                    <div style="height: 320px;">
                        <canvas id="operationsDoughnutChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header"><h3 class="zyga-section-title">Lectura operativa</h3></div>
                <div class="card-body">
                    <div class="zyga-stat-list">
                        <div class="zyga-stat-item">
                            <div>
                                <div class="font-weight-bold">Solicitudes en cola</div>
                                <div class="zyga-muted small">Esperando atención por proveedor</div>
                            </div>
                            <span class="badge badge-pill badge-soft-warning">{{ $metrics['queue_requests'] }}</span>
                        </div>
                        <div class="zyga-stat-item">
                            <div>
                                <div class="font-weight-bold">Solicitudes asignadas</div>
                                <div class="zyga-muted small">Con proveedor asignado</div>
                            </div>
                            <span class="badge badge-pill badge-soft-accent">{{ $metrics['assigned_requests'] }}</span>
                        </div>
                        <div class="zyga-stat-item">
                            <div>
                                <div class="font-weight-bold">En progreso</div>
                                <div class="zyga-muted small">Servicios ya operándose</div>
                            </div>
                            <span class="badge badge-pill badge-soft-primary">{{ $metrics['in_progress_requests'] }}</span>
                        </div>
                        <div class="zyga-stat-item">
                            <div>
                                <div class="font-weight-bold">Servicios activos</div>
                                <div class="zyga-muted small">Catálogo disponible para solicitar</div>
                            </div>
                            <span class="badge badge-pill badge-soft-success">{{ $metrics['active_services'] }}</span>
                        </div>
                        <div class="zyga-stat-item">
                            <div>
                                <div class="font-weight-bold">Proveedores verificados</div>
                                <div class="zyga-muted small">Disponibles para operar</div>
                            </div>
                            <span class="badge badge-pill badge-soft-success">{{ $metrics['verified_providers'] }}</span>
                        </div>
                        <div class="zyga-stat-item">
                            <div>
                                <div class="font-weight-bold">Usuarios registrados</div>
                                <div class="zyga-muted small">Base total del sistema</div>
                            </div>
                            <span class="badge badge-pill badge-soft-dark">{{ $metrics['users'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="zyga-section-title mb-0">Distribución por estado</h3>
                    <a href="{{ route('admin.reportes.index') }}" class="btn btn-sm btn-outline-primary">Ver reportes</a>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($statusBreakdown as $status => $count)
                            <div class="col-md-6 mb-3">
                                <div class="zyga-stat-item">
                                    <div>
                                        <div class="font-weight-bold">{{ ucfirst(str_replace('_', ' ', $status)) }}</div>
                                        <div class="zyga-muted small">Solicitudes en este estado</div>
                                    </div>
                                    <strong>{{ $count }}</strong>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-7 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="zyga-section-title mb-0">Últimas solicitudes</h3>
                    <a href="{{ route('admin.assistance.index') }}" class="btn btn-sm btn-outline-primary">Ver todas</a>
                </div>
                <div class="card-body p-0">
                    @if(empty($recentRequests))
                        <div class="zyga-empty">No hay solicitudes disponibles.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Folio</th>
                                        <th>Servicio</th>
                                        <th>Cliente</th>
                                        <th>Estado</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentRequests as $request)
                                        @php
                                            $status = $request['status'] ?? 'sin_estado';
                                            $statusClass = 'badge-soft-dark';
                                            if ($status === 'completed') $statusClass = 'badge-soft-success';
                                            elseif ($status === 'cancelled') $statusClass = 'badge-soft-danger';
                                            elseif (in_array($status, ['created', 'assigned', 'in_progress'], true)) $statusClass = 'badge-soft-warning';
                                        @endphp
                                        <tr>
                                            <td class="zyga-code">{{ $request['public_id'] ?? ('#' . ($request['id'] ?? '—')) }}</td>
                                            <td>{{ $request['service']['name'] ?? 'Sin servicio' }}</td>
                                            <td>{{ $request['user']['email'] ?? '—' }}</td>
                                            <td><span class="badge badge-pill {{ $statusClass }}">{{ ucfirst(str_replace('_', ' ', $status)) }}</span></td>
                                            <td class="text-right"><a href="{{ route('admin.assistance.show', $request['id']) }}" class="btn btn-sm btn-outline-primary">Abrir</a></td>
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
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="zyga-section-title mb-0">Últimos pagos</h3>
                    <a href="{{ route('admin.finance.index') }}" class="btn btn-sm btn-outline-primary">Ver finanzas</a>
                </div>
                <div class="card-body p-0">
                    @if(empty($recentPayments))
                        <div class="zyga-empty">No hay pagos registrados.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Monto</th>
                                        <th>Estado</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentPayments as $payment)
                                        @php
                                            $paymentStatus = $payment['status'] ?? 'sin_estado';
                                            $paymentClass = $paymentStatus === 'completed' ? 'badge-soft-success' : ($paymentStatus === 'failed' ? 'badge-soft-danger' : 'badge-soft-warning');
                                        @endphp
                                        <tr>
                                            <td>#{{ $payment['id'] ?? '—' }}</td>
                                            <td>${{ number_format((float) ($payment['amount'] ?? 0), 2) }}</td>
                                            <td><span class="badge badge-pill {{ $paymentClass }}">{{ ucfirst($paymentStatus) }}</span></td>
                                            <td class="text-right"><a href="{{ route('admin.finance.show-payment', $payment['id']) }}" class="btn btn-sm btn-outline-primary">Abrir</a></td>
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
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        (() => {
            const statusBarLabels = @json($statusBarChart['labels']);
            const statusBarValues = @json($statusBarChart['values']);
            const doughnutLabels = @json($operationalDoughnut['labels']);
            const doughnutValues = @json($operationalDoughnut['values']);

            const css = getComputedStyle(document.documentElement);
            const primary = (css.getPropertyValue('--primary') || '#0d6efd').trim();
            const success = (css.getPropertyValue('--success') || '#198754').trim();
            const warning = (css.getPropertyValue('--warning') || '#ffc107').trim();
            const danger = (css.getPropertyValue('--danger') || '#dc3545').trim();
            const info = (css.getPropertyValue('--info') || '#0dcaf0').trim();
            const muted = '#6c757d';

            const barCanvas = document.getElementById('requestsStatusBarChart');
            if (barCanvas) {
                new Chart(barCanvas, {
                    type: 'bar',
                    data: {
                        labels: statusBarLabels,
                        datasets: [{
                            label: 'Solicitudes',
                            data: statusBarValues,
                            backgroundColor: [warning, info, primary, success, danger],
                            borderRadius: 8,
                            maxBarThickness: 56
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                }
                            }
                        }
                    }
                });
            }

            const doughnutCanvas = document.getElementById('operationsDoughnutChart');
            if (doughnutCanvas) {
                new Chart(doughnutCanvas, {
                    type: 'doughnut',
                    data: {
                        labels: doughnutLabels,
                        datasets: [{
                            data: doughnutValues,
                            backgroundColor: [primary, success, danger],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '68%',
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    boxWidth: 12,
                                    usePointStyle: true
                                }
                            }
                        }
                    }
                });
            }
        })();
    </script>
@stop