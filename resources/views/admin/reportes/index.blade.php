@extends('adminlte::page')

@section('title', 'Reportes')

@section('content_header')
    <div>
        <h1 class="m-0">Reportes ejecutivos</h1>
        <p class="zyga-muted mb-0">Resumen de volumen, estado operativo, pagos y auditoría para lectura gerencial.</p>
    </div>
@stop

@section('content')
    @if(!empty($apiErrors))
        <div class="alert alert-warning">
            <ul class="mb-0">
                @foreach($apiErrors as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row mb-4">
        <div class="col-md-6 col-xl-3 mb-3">
            <div class="zyga-kpi">
                <div class="kpi-label">Usuarios</div>
                <div class="kpi-value">{{ $summary['total_users'] }}</div>
                <div class="zyga-muted">Base total</div>
                <div class="kpi-icon"><i class="fas fa-users"></i></div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3 mb-3">
            <div class="zyga-kpi">
                <div class="kpi-label">Providers</div>
                <div class="kpi-value">{{ $summary['total_providers'] }}</div>
                <div class="zyga-muted">Red total</div>
                <div class="kpi-icon"><i class="fas fa-people-carry"></i></div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3 mb-3">
            <div class="zyga-kpi">
                <div class="kpi-label">Solicitudes</div>
                <div class="kpi-value">{{ $summary['total_requests'] }}</div>
                <div class="zyga-muted">Casos registrados</div>
                <div class="kpi-icon"><i class="fas fa-route"></i></div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3 mb-3">
            <div class="zyga-kpi">
                <div class="kpi-label">Ingresos</div>
                <div class="kpi-value">${{ number_format($summary['completed_revenue'], 2) }}</div>
                <div class="zyga-muted">Pagos completados</div>
                <div class="kpi-icon"><i class="fas fa-wallet"></i></div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-7 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="zyga-section-title mb-0">Estado de solicitudes</h3>
                    <span class="zyga-muted small">Gráfica de barras</span>
                </div>
                <div class="card-body">
                    <div style="height: 320px;">
                        <canvas id="reportStatusBarChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="zyga-section-title mb-0">Métodos de pago</h3>
                    <span class="zyga-muted small">Gráfica de dona</span>
                </div>
                <div class="card-body">
                    <div style="height: 320px;">
                        <canvas id="paymentMethodsDoughnutChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-5 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="zyga-section-title mb-0">Resumen general</h3>
                    <span class="zyga-muted small">Gráfica de dona</span>
                </div>
                <div class="card-body">
                    <div style="height: 320px;">
                        <canvas id="globalSummaryDoughnutChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-7 mb-4">
            <div class="card h-100">
                <div class="card-header"><h3 class="zyga-section-title mb-0">Lectura rápida</h3></div>
                <div class="card-body">
                    <div class="row">
                        @forelse($statusCounts as $status => $count)
                            <div class="col-md-6 mb-3">
                                <div class="zyga-stat-item">
                                    <span>{{ ucfirst(str_replace('_', ' ', $status)) }}</span>
                                    <strong>{{ $count }}</strong>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="zyga-empty">Sin datos de solicitudes.</div>
                            </div>
                        @endforelse
                    </div>

                    <hr>

                    <div class="row">
                        @forelse($methodCounts as $method => $count)
                            <div class="col-md-6 mb-3">
                                <div class="zyga-stat-item">
                                    <span>{{ $method }}</span>
                                    <strong>{{ $count }}</strong>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="zyga-empty">Sin datos de pago.</div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h3 class="zyga-section-title">Últimos eventos de auditoría</h3></div>
        <div class="card-body p-0">
            @if(empty($latestAudits))
                <div class="zyga-empty">No hay eventos de auditoría recientes.</div>
            @else
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Acción</th>
                                <th>Usuario</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($latestAudits as $audit)
                                <tr>
                                    <td>{{ $audit['id'] ?? '—' }}</td>
                                    <td>{{ $audit['action'] ?? '—' }}</td>
                                    <td>{{ $audit['user']['email'] ?? $audit['user_id'] ?? '—' }}</td>
                                    <td>{{ $audit['created_at'] ?? '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        (() => {
            const charts = @json($charts);

            const css = getComputedStyle(document.documentElement);
            const primary = (css.getPropertyValue('--primary') || '#0d6efd').trim();
            const success = (css.getPropertyValue('--success') || '#198754').trim();
            const warning = (css.getPropertyValue('--warning') || '#ffc107').trim();
            const danger = (css.getPropertyValue('--danger') || '#dc3545').trim();
            const info = (css.getPropertyValue('--info') || '#0dcaf0').trim();
            const secondary = '#6c757d';
            const palette = [primary, success, warning, danger, info, secondary, '#6610f2', '#fd7e14'];

            const barCanvas = document.getElementById('reportStatusBarChart');
            if (barCanvas) {
                new Chart(barCanvas, {
                    type: 'bar',
                    data: {
                        labels: charts.statusLabels,
                        datasets: [{
                            label: 'Solicitudes',
                            data: charts.statusValues,
                            backgroundColor: charts.statusLabels.map((_, index) => palette[index % palette.length]),
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

            const methodsCanvas = document.getElementById('paymentMethodsDoughnutChart');
            if (methodsCanvas) {
                new Chart(methodsCanvas, {
                    type: 'doughnut',
                    data: {
                        labels: charts.methodLabels,
                        datasets: [{
                            data: charts.methodValues,
                            backgroundColor: charts.methodLabels.map((_, index) => palette[index % palette.length]),
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

            const summaryCanvas = document.getElementById('globalSummaryDoughnutChart');
            if (summaryCanvas) {
                new Chart(summaryCanvas, {
                    type: 'doughnut',
                    data: {
                        labels: charts.globalSummaryLabels,
                        datasets: [{
                            data: charts.globalSummaryValues,
                            backgroundColor: charts.globalSummaryLabels.map((_, index) => palette[index % palette.length]),
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