@extends('adminlte::page')

@section('title', 'Solicitudes')

@section('content_header')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
        <div>
            <h1 class="m-0">Solicitudes de asistencia</h1>
            <p class="zyga-muted mb-0">
                Monitoreo administrativo del flujo operativo de asistencia vial.
            </p>
        </div>

        <div class="mt-3 mt-md-0">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-light mr-2">
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
        $totalRequests = count($requests);

        $createdCount = 0;
        $assignedCount = 0;
        $inProgressCount = 0;
        $completedCount = 0;
        $cancelledCount = 0;

        foreach ($requests as $requestItem) {
            $status = $requestItem['status'] ?? null;

            if ($status === 'created') {
                $createdCount++;
            } elseif ($status === 'assigned') {
                $assignedCount++;
            } elseif ($status === 'in_progress') {
                $inProgressCount++;
            } elseif ($status === 'completed') {
                $completedCount++;
            } elseif ($status === 'cancelled') {
                $cancelledCount++;
            }
        }
    @endphp

    <div class="zyga-hero mb-4">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h2 class="mb-2">Panel de control de solicitudes</h2>
                <p class="mb-0 text-white-50">
                    Consulta solicitudes por folio, usuario y estado; identifica rápidamente
                    cuáles siguen abiertas y cuáles ya concluyeron o fueron canceladas.
                </p>
            </div>
            <div class="col-lg-4 text-lg-right mt-3 mt-lg-0">
                <span class="badge badge-pill badge-soft-primary">
                    {{ $totalRequests }} resultados
                </span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-xl-2 mb-3">
            <div class="zyga-kpi">
                <div class="kpi-label">Total</div>
                <div class="kpi-value">{{ $totalRequests }}</div>
                <div class="zyga-muted">Solicitudes listadas</div>
                <div class="kpi-icon">
                    <i class="fas fa-list"></i>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-2 mb-3">
            <div class="zyga-kpi">
                <div class="kpi-label">Created</div>
                <div class="kpi-value">{{ $createdCount }}</div>
                <div class="zyga-muted">Pendientes de atención</div>
                <div class="kpi-icon">
                    <i class="fas fa-plus-circle"></i>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-2 mb-3">
            <div class="zyga-kpi">
                <div class="kpi-label">Assigned</div>
                <div class="kpi-value">{{ $assignedCount }}</div>
                <div class="zyga-muted">Con proveedor asignado</div>
                <div class="kpi-icon">
                    <i class="fas fa-user-check"></i>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-2 mb-3">
            <div class="zyga-kpi">
                <div class="kpi-label">In progress</div>
                <div class="kpi-value">{{ $inProgressCount }}</div>
                <div class="zyga-muted">Actualmente en operación</div>
                <div class="kpi-icon">
                    <i class="fas fa-route"></i>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-2 mb-3">
            <div class="zyga-kpi">
                <div class="kpi-label">Completed</div>
                <div class="kpi-value">{{ $completedCount }}</div>
                <div class="zyga-muted">Servicios finalizados</div>
                <div class="kpi-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-2 mb-3">
            <div class="zyga-kpi">
                <div class="kpi-label">Cancelled</div>
                <div class="kpi-value">{{ $cancelledCount }}</div>
                <div class="zyga-muted">Solicitudes canceladas</div>
                <div class="kpi-icon">
                    <i class="fas fa-times-circle"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h3 class="zyga-section-title">Filtros de búsqueda</h3>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.assistance.index') }}">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="public_id">Folio público</label>
                        <input
                            type="text"
                            id="public_id"
                            name="public_id"
                            class="form-control"
                            placeholder="Ej. 01HXYZ..."
                            value="{{ $filters['public_id'] ?? '' }}"
                        >
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="user_id">ID de usuario</label>
                        <input
                            type="text"
                            id="user_id"
                            name="user_id"
                            class="form-control"
                            placeholder="Ej. 15"
                            value="{{ $filters['user_id'] ?? '' }}"
                        >
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="status">Estado</label>
                        <select name="status" id="status" class="form-control">
                            <option value="">Todos los estados</option>
                            @foreach(['created', 'assigned', 'in_progress', 'completed', 'cancelled'] as $status)
                                <option value="{{ $status }}" @selected(($filters['status'] ?? '') === $status)>
                                    {{ $status }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="d-flex flex-column flex-md-row">
                    <button type="submit" class="btn btn-primary mr-md-2 mb-2 mb-md-0">
                        <i class="fas fa-filter mr-1"></i>
                        Aplicar filtros
                    </button>

                    <a href="{{ route('admin.assistance.index') }}" class="btn btn-light">
                        <i class="fas fa-eraser mr-1"></i>
                        Limpiar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center">
            <h3 class="zyga-section-title mb-2 mb-md-0">Listado de solicitudes</h3>

            <span class="badge badge-pill badge-soft-dark">
                {{ $totalRequests }} registros
            </span>
        </div>

        <div class="card-body p-0">
            @if(empty($requests))
                <div class="zyga-empty">
                    No hay solicitudes para los filtros seleccionados.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Folio</th>
                                <th>Cliente</th>
                                <th>Servicio</th>
                                <th>Proveedor</th>
                                <th>Estado</th>
                                <th>Última actualización</th>
                                <th class="text-right">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($requests as $request)
                                @php
                                    $status = $request['status'] ?? 'sin_estado';
                                    $statusClass = 'badge-soft-dark';

                                    if ($status === 'completed') {
                                        $statusClass = 'badge-soft-success';
                                    } elseif ($status === 'cancelled') {
                                        $statusClass = 'badge-soft-danger';
                                    } elseif (in_array($status, ['created', 'assigned', 'in_progress'], true)) {
                                        $statusClass = 'badge-soft-warning';
                                    }
                                @endphp

                                <tr>
                                    <td>{{ $request['id'] ?? '—' }}</td>

                                    <td class="zyga-code">
                                        {{ $request['public_id'] ?? '—' }}
                                    </td>

                                    <td>
                                        <div class="font-weight-bold">
                                            {{ $request['user']['email'] ?? '—' }}
                                        </div>
                                        <div class="zyga-muted small">
                                            User ID: {{ $request['user_id'] ?? '—' }}
                                        </div>
                                    </td>

                                    <td>
                                        {{ $request['service']['name'] ?? 'Sin servicio' }}
                                    </td>

                                    <td>
                                        @if(!empty($request['provider']['display_name']))
                                            <div class="font-weight-bold">
                                                {{ $request['provider']['display_name'] }}
                                            </div>
                                            <div class="zyga-muted small">
                                                ID: {{ $request['provider_id'] ?? '—' }}
                                            </div>
                                        @else
                                            <span class="zyga-muted">Sin asignar</span>
                                        @endif
                                    </td>

                                    <td>
                                        <span class="badge badge-pill {{ $statusClass }}">
                                            {{ $status }}
                                        </span>
                                    </td>

                                    <td>
                                        {{ $request['updated_at'] ?? '—' }}
                                    </td>

                                    <td class="text-right">
                                        <a href="{{ route('admin.assistance.show', $request['id']) }}"
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
