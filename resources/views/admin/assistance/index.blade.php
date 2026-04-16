@extends('adminlte::page')

@section('title', 'Operación de asistencias')

@section('content_header')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
        <div>
            <h1 class="m-0">Operación de asistencias</h1>
            <p class="zyga-muted mb-0">Vista operativa para priorizar solicitudes, validar estado, detectar tracking y escalar intervención administrativa.</p>
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
    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif
    @if($apiError) <div class="alert alert-warning">{{ $apiError }}</div> @endif

    @php
        $totalRequests = count($requests);
        $queueCount = count(array_filter($requests, fn ($item) => ($item['status'] ?? null) === 'created'));
        $assignedCount = count(array_filter($requests, fn ($item) => ($item['status'] ?? null) === 'assigned'));
        $inProgressCount = count(array_filter($requests, fn ($item) => ($item['status'] ?? null) === 'in_progress'));
        $completedCount = count(array_filter($requests, fn ($item) => ($item['status'] ?? null) === 'completed'));
        $cancelledCount = count(array_filter($requests, fn ($item) => ($item['status'] ?? null) === 'cancelled'));
        $trackingCount = count(array_filter($requests, fn ($item) => !empty($item['latest_provider_location'])));

        $formatTrackingTime = function ($value) {
            if (!$value) {
                return 'Sin registro';
            }

            try {
                return \Carbon\Carbon::parse($value)->format('d/m/Y H:i');
            } catch (\Throwable $e) {
                return $value;
            }
        };
    @endphp

    <div class="row mb-4">
        <div class="col-md-6 col-xl-2 mb-3"><div class="zyga-kpi"><div class="kpi-label">Total</div><div class="kpi-value">{{ $totalRequests }}</div><div class="zyga-muted">Solicitudes listadas</div><div class="kpi-icon"><i class="fas fa-list"></i></div></div></div>
        <div class="col-md-6 col-xl-2 mb-3"><div class="zyga-kpi"><div class="kpi-label">En cola</div><div class="kpi-value">{{ $queueCount }}</div><div class="zyga-muted">Esperando proveedor</div><div class="kpi-icon"><i class="fas fa-hourglass-half"></i></div></div></div>
        <div class="col-md-6 col-xl-2 mb-3"><div class="zyga-kpi"><div class="kpi-label">Asignadas</div><div class="kpi-value">{{ $assignedCount }}</div><div class="zyga-muted">Con proveedor tomado</div><div class="kpi-icon"><i class="fas fa-user-check"></i></div></div></div>
        <div class="col-md-6 col-xl-2 mb-3"><div class="zyga-kpi"><div class="kpi-label">En curso</div><div class="kpi-value">{{ $inProgressCount }}</div><div class="zyga-muted">Atención en proceso</div><div class="kpi-icon"><i class="fas fa-truck-pickup"></i></div></div></div>
        <div class="col-md-6 col-xl-2 mb-3"><div class="zyga-kpi"><div class="kpi-label">Tracking</div><div class="kpi-value">{{ $trackingCount }}</div><div class="zyga-muted">Con ubicación reciente</div><div class="kpi-icon"><i class="fas fa-map-marker-alt"></i></div></div></div>
        <div class="col-md-6 col-xl-2 mb-3"><div class="zyga-kpi"><div class="kpi-label">Completadas/Canceladas</div><div class="kpi-value">{{ $completedCount + $cancelledCount }}</div><div class="zyga-muted">Casos cerrados</div><div class="kpi-icon"><i class="fas fa-check-circle"></i></div></div></div>
    </div>

    <div class="card mb-4">
        <div class="card-header"><h3 class="zyga-section-title">Filtros operativos</h3></div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.assistance.index') }}">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="public_id">Folio</label>
                        <input type="text" id="public_id" name="public_id" class="form-control" placeholder="Ej. ZYG-2026-000001" value="{{ $filters['public_id'] ?? '' }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="user_id">ID de cliente</label>
                        <input type="text" id="user_id" name="user_id" class="form-control" placeholder="Ej. 15" value="{{ $filters['user_id'] ?? '' }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="provider_id">ID de proveedor</label>
                        <input type="text" id="provider_id" name="provider_id" class="form-control" placeholder="Ej. 7" value="{{ $filters['provider_id'] ?? '' }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="status">Estado</label>
                        <select name="status" id="status" class="form-control">
                            <option value="">Todos los estados</option>
                            @foreach(['created' => 'En cola', 'assigned' => 'Asignada', 'in_progress' => 'En curso', 'completed' => 'Completada', 'cancelled' => 'Cancelada'] as $statusValue => $statusLabel)
                                <option value="{{ $statusValue }}" @selected(($filters['status'] ?? '') === $statusValue)>{{ $statusLabel }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="d-flex flex-column flex-md-row">
                    <button type="submit" class="btn btn-primary mr-md-2 mb-2 mb-md-0"><i class="fas fa-filter mr-1"></i>Aplicar filtros</button>
                    <a href="{{ route('admin.assistance.index') }}" class="btn btn-light"><i class="fas fa-eraser mr-1"></i>Limpiar</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center">
            <h3 class="zyga-section-title mb-2 mb-md-0">Bandeja operativa</h3>
            <span class="badge badge-pill badge-soft-dark">{{ $totalRequests }} registros</span>
        </div>
        <div class="card-body p-0">
            @if(empty($requests))
                <div class="zyga-empty">No hay solicitudes para los filtros seleccionados.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Folio</th>
                                <th>Cliente</th>
                                <th>Servicio</th>
                                <th>Proveedor</th>
                                <th>Estado</th>
                                <th>Tracking</th>
                                <th>Última actualización</th>
                                <th class="text-right">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($requests as $request)
                                @php
                                    $status = $request['status'] ?? 'sin_estado';
                                    $statusLabel = ucfirst(str_replace('_', ' ', $status));
                                    $statusClass = 'badge-soft-dark';

                                    if ($status === 'completed') $statusClass = 'badge-soft-success';
                                    elseif ($status === 'cancelled') $statusClass = 'badge-soft-danger';
                                    elseif (in_array($status, ['created', 'assigned', 'in_progress'], true)) $statusClass = 'badge-soft-warning';

                                    $provider = $request['provider'] ?? [];
                                    $providerUser = $provider['user'] ?? [];
                                    $tracking = $request['latest_provider_location'] ?? null;
                                @endphp
                                <tr>
                                    <td>
                                        <div class="zyga-code">{{ $request['public_id'] ?? ('#' . ($request['id'] ?? '—')) }}</div>
                                        <div class="zyga-muted small">ID {{ $request['id'] ?? '—' }}</div>
                                    </td>
                                    <td>
                                        <div>{{ $request['user']['email'] ?? '—' }}</div>
                                        <div class="zyga-muted small">Cliente ID {{ $request['user_id'] ?? '—' }}</div>
                                    </td>
                                    <td>{{ $request['service']['name'] ?? 'Sin servicio' }}</td>
                                    <td>
                                        <div>{{ $provider['display_name'] ?? 'Sin asignar' }}</div>
                                        <div class="zyga-muted small">{{ $providerUser['email'] ?? 'Sin correo visible' }}</div>
                                    </td>
                                    <td><span class="badge badge-pill {{ $statusClass }}">{{ $statusLabel }}</span></td>
                                    <td>
                                        @if($tracking)
                                            <div class="badge badge-pill badge-soft-success mb-1">Ubicación recibida</div>
                                            <div class="small">
                                                {{ $tracking['lat'] ?? '—' }}, {{ $tracking['lng'] ?? '—' }}
                                            </div>
                                            <div class="zyga-muted small">
                                                {{ $formatTrackingTime($tracking['recorded_at'] ?? ($tracking['created_at'] ?? null)) }}
                                            </div>
                                        @else
                                            <span class="badge badge-pill badge-soft-warning">Sin tracking</span>
                                        @endif
                                    </td>
                                    <td>{{ $request['updated_at'] ?? ($request['created_at'] ?? '—') }}</td>
                                    <td class="text-right">
                                        <a href="{{ route('admin.assistance.show', $request['id']) }}" class="btn btn-sm btn-outline-primary">
                                            Abrir caso
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
