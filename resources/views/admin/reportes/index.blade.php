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
        <ul class="mb-0">@foreach($apiErrors as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
@endif

<div class="card mb-4">
    <div class="card-body">
        <div class="d-flex flex-column flex-lg-row flex-wrap">
            <a href="{{ route('admin.exportaciones.users.excel') }}" class="btn btn-success mr-lg-2 mb-2">
                <i class="fas fa-file-excel mr-1"></i>Usuarios Excel
            </a>
            <a href="{{ route('admin.exportaciones.users.pdf') }}" class="btn btn-danger mr-lg-2 mb-2">
                <i class="fas fa-file-pdf mr-1"></i>Usuarios PDF
            </a>

            <a href="{{ route('admin.exportaciones.providers.excel') }}" class="btn btn-success mr-lg-2 mb-2">
                <i class="fas fa-file-excel mr-1"></i>Providers Excel
            </a>
            <a href="{{ route('admin.exportaciones.providers.pdf') }}" class="btn btn-danger mr-lg-2 mb-2">
                <i class="fas fa-file-pdf mr-1"></i>Providers PDF
            </a>

            <a href="{{ route('admin.exportaciones.assistance.excel') }}" class="btn btn-success mr-lg-2 mb-2">
                <i class="fas fa-file-excel mr-1"></i>Solicitudes Excel
            </a>
            <a href="{{ route('admin.exportaciones.assistance.pdf') }}" class="btn btn-danger mr-lg-2 mb-2">
                <i class="fas fa-file-pdf mr-1"></i>Solicitudes PDF
            </a>

            <a href="{{ route('admin.exportaciones.payments.excel') }}" class="btn btn-success mr-lg-2 mb-2">
                <i class="fas fa-file-excel mr-1"></i>Pagos Excel
            </a>
            <a href="{{ route('admin.exportaciones.payments.pdf') }}" class="btn btn-danger mb-2">
                <i class="fas fa-file-pdf mr-1"></i>Pagos PDF
            </a>
        </div>
    </div>
</div>

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
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h3 class="zyga-section-title">Distribución por estado de solicitud</h3>
            </div>
            <div class="card-body">
                @forelse($statusCounts as $status => $count)
                    <div class="zyga-stat-item mb-2">
                        <span>{{ ucfirst(str_replace('_', ' ', $status)) }}</span><strong>{{ $count }}</strong></div>
                @empty
                    <div class="zyga-empty">Sin datos de solicitudes.</div>
                @endforelse
            </div>
        </div>
    </div>
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h3 class="zyga-section-title">Métodos de pago más usados</h3>
            </div>
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
    <div class="card-header">
        <h3 class="zyga-section-title">Últimos eventos de auditoría</h3>
    </div>
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