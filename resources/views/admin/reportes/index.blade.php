@extends('adminlte::page')

@section('title', 'Reportes')

@section('content_header')
    <div>
        <h1 class="m-0">Reportes ejecutivos</h1>
        <p class="zyga-muted mb-0">Lectura rápida del comportamiento operativo usando datos administrativos reales.</p>
    </div>
@stop

@section('content')
    @if(!empty($apiErrors))
        <div class="alert alert-warning"><ul class="mb-0">@foreach($apiErrors as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif

    <div class="row">
        <div class="col-md-6 col-xl-3 mb-3"><div class="zyga-kpi"><div class="kpi-label">Usuarios</div><div class="kpi-value">{{ number_format($summary['total_users']) }}</div><div class="kpi-icon"><i class="fas fa-users"></i></div></div></div>
        <div class="col-md-6 col-xl-3 mb-3"><div class="zyga-kpi"><div class="kpi-label">Proveedores</div><div class="kpi-value">{{ number_format($summary['total_providers']) }}</div><div class="kpi-icon"><i class="fas fa-people-carry"></i></div></div></div>
        <div class="col-md-6 col-xl-3 mb-3"><div class="zyga-kpi"><div class="kpi-label">Solicitudes</div><div class="kpi-value">{{ number_format($summary['total_requests']) }}</div><div class="kpi-icon"><i class="fas fa-route"></i></div></div></div>
        <div class="col-md-6 col-xl-3 mb-3"><div class="zyga-kpi"><div class="kpi-label">Ingresos</div><div class="kpi-value">${{ number_format($summary['completed_revenue'],2) }}</div><div class="kpi-icon"><i class="fas fa-wallet"></i></div></div></div>
    </div>

    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header"><h3 class="zyga-section-title">Distribución por estado de solicitud</h3></div>
                <div class="card-body">
                    @forelse($statusCounts as $status => $count)
                        <div class="zyga-stat-item mb-2"><span>{{ $status }}</span><strong>{{ $count }}</strong></div>
                    @empty
                        <div class="zyga-empty">Sin datos de solicitudes.</div>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header"><h3 class="zyga-section-title">Métodos de pago más usados</h3></div>
                <div class="card-body">
                    @forelse($methodCounts as $method => $count)
                        <div class="zyga-stat-item mb-2"><span>{{ $method }}</span><strong>{{ $count }}</strong></div>
                    @empty
                        <div class="zyga-empty">Sin datos de pago.</div>
                    @endforelse
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
                        <thead><tr><th>ID</th><th>Acción</th><th>Usuario</th><th>Fecha</th></tr></thead>
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