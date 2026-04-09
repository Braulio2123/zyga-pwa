@extends('adminlte::page')

@section('title', 'Detalle de solicitud')

@section('content_header')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
        <div>
            <h1 class="m-0">Solicitud #{{ $requestData['id'] ?? '—' }}</h1>
            <p class="zyga-muted mb-0">
                Revisión administrativa, actualización de estado y ajuste de proveedor.
            </p>
        </div>

        <div class="mt-3 mt-md-0">
            <a href="{{ route('admin.assistance.index') }}" class="btn btn-light">
                <i class="fas fa-arrow-left mr-1"></i>
                Volver a solicitudes
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

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Corrige los siguientes errores:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @php
        $status = $requestData['status'] ?? 'sin_estado';
        $statusClass = 'badge-soft-dark';

        if ($status === 'completed') {
            $statusClass = 'badge-soft-success';
        } elseif ($status === 'cancelled') {
            $statusClass = 'badge-soft-danger';
        } elseif (in_array($status, ['created', 'assigned', 'in_progress'], true)) {
            $statusClass = 'badge-soft-warning';
        }
    @endphp

    <div class="zyga-hero mb-4">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h2 class="mb-2">Detalle operativo de la solicitud</h2>
                <p class="mb-0 text-white-50">
                    Aquí puedes revisar la información principal del servicio, el cliente, el proveedor asignado
                    y realizar cambios administrativos controlados.
                </p>
            </div>
            <div class="col-lg-4 text-lg-right mt-3 mt-lg-0">
                <span class="badge badge-pill {{ $statusClass }}">
                    {{ $status }}
                </span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h3 class="zyga-section-title">Actualizar solicitud</h3>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('admin.assistance.update', $requestData['id']) }}">
                        @csrf
                        @method('PATCH')

                        <div class="form-group">
                            <label for="status">Estado</label>
                            <select name="status" id="status" class="form-control" required>
                                @foreach(['created', 'assigned', 'in_progress', 'completed', 'cancelled'] as $itemStatus)
                                    <option value="{{ $itemStatus }}" @selected(old('status', $requestData['status'] ?? '') === $itemStatus)>
                                        {{ $itemStatus }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="provider_id">Provider ID</label>
                            <input
                                type="number"
                                name="provider_id"
                                id="provider_id"
                                class="form-control"
                                value="{{ old('provider_id', $requestData['provider_id'] ?? '') }}"
                                placeholder="Opcional"
                            >
                        </div>

                        <div class="form-group">
                            <label for="cancel_reason">Motivo de cancelación</label>
                            <textarea
                                name="cancel_reason"
                                id="cancel_reason"
                                class="form-control"
                                rows="4"
                                placeholder="Solo si la solicitud será cancelada"
                            >{{ old('cancel_reason', $requestData['cancel_reason'] ?? '') }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-save mr-1"></i>
                            Guardar cambios
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-xl-8 mb-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="zyga-section-title">Resumen general</h3>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>ID interno</label>
                            <div class="zyga-code">{{ $requestData['id'] ?? '—' }}</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Folio público</label>
                            <div class="zyga-code">{{ $requestData['public_id'] ?? '—' }}</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Estado actual</label>
                            <div>
                                <span class="badge badge-pill {{ $statusClass }}">
                                    {{ $status }}
                                </span>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Servicio</label>
                            <div>{{ $requestData['service']['name'] ?? 'Sin servicio' }}</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Cliente</label>
                            <div>{{ $requestData['user']['email'] ?? '—' }}</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>User ID</label>
                            <div>{{ $requestData['user_id'] ?? '—' }}</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Proveedor</label>
                            <div>{{ $requestData['provider']['display_name'] ?? 'Sin asignar' }}</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Provider ID</label>
                            <div>{{ $requestData['provider_id'] ?? '—' }}</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Vehículo</label>
                            <div>
                                {{ $requestData['vehicle']['plate'] ?? ($requestData['vehicle']['id'] ?? '—') }}
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Vehicle ID</label>
                            <div>{{ $requestData['vehicle_id'] ?? '—' }}</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Creada</label>
                            <div>{{ $requestData['created_at'] ?? '—' }}</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Última actualización</label>
                            <div>{{ $requestData['updated_at'] ?? '—' }}</div>
                        </div>

                        <div class="col-12 mb-3">
                            <label>Dirección / punto de atención</label>
                            <div>{{ $requestData['pickup_address'] ?? '—' }}</div>
                        </div>

                        @if(!empty($requestData['cancel_reason']))
                            <div class="col-12">
                                <label>Motivo de cancelación</label>
                                <div>{{ $requestData['cancel_reason'] }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="zyga-section-title">Lectura operativa</h3>
                </div>

                <div class="card-body">
                    <div class="zyga-stat-list">
                        <div class="zyga-stat-item">
                            <div>
                                <div class="font-weight-bold">Cliente asociado</div>
                                <div class="zyga-muted small">
                                    {{ $requestData['user']['email'] ?? 'No disponible' }}
                                </div>
                            </div>
                            <span class="badge badge-pill badge-soft-accent">
                                {{ $requestData['user_id'] ?? '—' }}
                            </span>
                        </div>

                        <div class="zyga-stat-item">
                            <div>
                                <div class="font-weight-bold">Proveedor asignado</div>
                                <div class="zyga-muted small">
                                    {{ $requestData['provider']['display_name'] ?? 'Todavía no asignado' }}
                                </div>
                            </div>
                            <span class="badge badge-pill {{ !empty($requestData['provider_id']) ? 'badge-soft-success' : 'badge-soft-warning' }}">
                                {{ $requestData['provider_id'] ?? 'Pendiente' }}
                            </span>
                        </div>

                        <div class="zyga-stat-item">
                            <div>
                                <div class="font-weight-bold">Servicio solicitado</div>
                                <div class="zyga-muted small">
                                    {{ $requestData['service']['name'] ?? 'No disponible' }}
                                </div>
                            </div>
                            <span class="badge badge-pill badge-soft-primary">
                                {{ $requestData['service_id'] ?? '—' }}
                            </span>
                        </div>

                        <div class="zyga-stat-item">
                            <div>
                                <div class="font-weight-bold">Estado operativo</div>
                                <div class="zyga-muted small">
                                    Estado actual controlado desde administración
                                </div>
                            </div>
                            <span class="badge badge-pill {{ $statusClass }}">
                                {{ $status }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
