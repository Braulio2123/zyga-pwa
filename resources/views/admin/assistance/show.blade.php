@extends('adminlte::page')

@section('title', 'Caso de asistencia')

@section('content_header')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
        <div>
            <h1 class="m-0">Caso {{ $requestData['public_id'] ?? ('#' . ($requestData['id'] ?? '—')) }}</h1>
            <p class="zyga-muted mb-0">Revisión detallada del caso para seguimiento, reasignación o cierre administrativo.</p>
        </div>
        <div class="mt-3 mt-md-0">
            <a href="{{ route('admin.assistance.index') }}" class="btn btn-light"><i class="fas fa-arrow-left mr-1"></i>Volver a operación</a>
        </div>
    </div>
@stop

@section('content')
    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif
    @if($errors->any())
        <div class="alert alert-danger"><strong>Corrige los siguientes errores:</strong><ul class="mb-0 mt-2">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif

    @php
        $status = $requestData['status'] ?? 'sin_estado';
        $statusLabel = ucfirst(str_replace('_', ' ', $status));
        $statusClass = 'badge-soft-dark';
        if ($status === 'completed') $statusClass = 'badge-soft-success';
        elseif ($status === 'cancelled') $statusClass = 'badge-soft-danger';
        elseif (in_array($status, ['created', 'assigned', 'in_progress'], true)) $statusClass = 'badge-soft-warning';
    @endphp

    <div class="row">
        <div class="col-xl-4 mb-4">
            <div class="card h-100">
                <div class="card-header"><h3 class="zyga-section-title">Acción administrativa</h3></div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.assistance.update', $requestData['id']) }}">
                        @csrf
                        @method('PATCH')

                        <div class="form-group">
                            <label for="status">Estado</label>
                            <select name="status" id="status" class="form-control" required>
                                @foreach(['created' => 'En cola', 'assigned' => 'Asignada', 'in_progress' => 'En curso', 'completed' => 'Completada', 'cancelled' => 'Cancelada'] as $key => $label)
                                    <option value="{{ $key }}" @selected(old('status', $requestData['status'] ?? '') === $key)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="provider_id">Provider ID</label>
                            <input type="number" name="provider_id" id="provider_id" class="form-control" value="{{ old('provider_id', $requestData['provider_id'] ?? '') }}" placeholder="Opcional, solo si vas a reasignar manualmente">
                        </div>

                        <div class="form-group mb-4">
                            <label for="cancel_reason">Motivo de cancelación</label>
                            <textarea name="cancel_reason" id="cancel_reason" rows="4" class="form-control" placeholder="Usa este campo solo si el caso se cancelará o ya fue cancelado.">{{ old('cancel_reason', $requestData['cancel_reason'] ?? '') }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-save mr-1"></i>Guardar decisión</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-xl-8 mb-4">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="zyga-section-title mb-0">Resumen del caso</h3>
                    <span class="badge badge-pill {{ $statusClass }}">{{ $statusLabel }}</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3"><label>Cliente</label><div>{{ $requestData['user']['email'] ?? '—' }}</div></div>
                        <div class="col-md-6 mb-3"><label>Servicio</label><div>{{ $requestData['service']['name'] ?? 'Sin servicio' }}</div></div>
                        <div class="col-md-6 mb-3"><label>Proveedor</label><div>{{ $requestData['provider']['display_name'] ?? 'Sin asignar' }}</div></div>
                        <div class="col-md-6 mb-3"><label>Vehículo</label><div>{{ $requestData['vehicle']['plate'] ?? ($requestData['vehicle']['id'] ?? '—') }}</div></div>
                        <div class="col-md-6 mb-3"><label>Creación</label><div>{{ $requestData['created_at'] ?? '—' }}</div></div>
                        <div class="col-md-6 mb-3"><label>Última actualización</label><div>{{ $requestData['updated_at'] ?? '—' }}</div></div>
                        <div class="col-12 mb-3"><label>Punto de atención</label><div>{{ $requestData['pickup_address'] ?? '—' }}</div></div>
                        @if(!empty($requestData['cancel_reason']))
                            <div class="col-12"><label>Motivo de cancelación</label><div>{{ $requestData['cancel_reason'] }}</div></div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h3 class="zyga-section-title">Lectura operativa</h3></div>
                <div class="card-body">
                    <div class="zyga-stat-list">
                        <div class="zyga-stat-item"><div><div class="font-weight-bold">Cliente asociado</div><div class="zyga-muted small">ID {{ $requestData['user_id'] ?? '—' }}</div></div><span class="badge badge-pill badge-soft-primary">{{ $requestData['user']['email'] ?? '—' }}</span></div>
                        <div class="zyga-stat-item"><div><div class="font-weight-bold">Proveedor actual</div><div class="zyga-muted small">{{ $requestData['provider']['display_name'] ?? 'Aún no se ha tomado el caso' }}</div></div><span class="badge badge-pill {{ !empty($requestData['provider_id']) ? 'badge-soft-success' : 'badge-soft-warning' }}">{{ $requestData['provider_id'] ?? 'Pendiente' }}</span></div>
                        <div class="zyga-stat-item"><div><div class="font-weight-bold">Servicio solicitado</div><div class="zyga-muted small">{{ $requestData['service']['name'] ?? 'Sin información' }}</div></div><span class="badge badge-pill badge-soft-accent">{{ $requestData['service_id'] ?? '—' }}</span></div>
                        <div class="zyga-stat-item"><div><div class="font-weight-bold">Caso trazable</div><div class="zyga-muted small">Usa el folio para seguimiento con cliente, provider o soporte</div></div><span class="badge badge-pill badge-soft-dark">{{ $requestData['public_id'] ?? '—' }}</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
