@extends('adminlte::page')

@section('title', 'Detalle de solicitud')

@section('content_header')
    <div>
        <h1 class="m-0">Solicitud #{{ $requestData['id'] ?? '—' }}</h1>
        <p class="zyga-muted mb-0">Ajustes administrativos sobre estado, proveedor y cancelación.</p>
    </div>
@stop

@section('content')
    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

    <div class="row">
        <div class="col-lg-5 mb-4">
            <div class="card">
                <div class="card-header"><h3 class="zyga-section-title">Actualizar solicitud</h3></div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.assistance.update', $requestData['id']) }}">
                        @csrf @method('PATCH')
                        <div class="form-group"><label>Status</label>
                            <select name="status" class="form-control" required>
                                @foreach(['created','assigned','in_progress','completed','cancelled'] as $status)
                                    <option value="{{ $status }}" @selected(($requestData['status'] ?? '') === $status)>{{ $status }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group"><label>Provider ID</label><input type="number" name="provider_id" class="form-control" value="{{ old('provider_id', $requestData['provider_id'] ?? '') }}"></div>
                        <div class="form-group"><label>Motivo de cancelación</label><textarea name="cancel_reason" class="form-control" rows="3">{{ old('cancel_reason', $requestData['cancel_reason'] ?? '') }}</textarea></div>
                        <button class="btn btn-primary" type="submit">Guardar cambios</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-7 mb-4">
            <div class="card">
                <div class="card-header"><h3 class="zyga-section-title">Resumen operativo</h3></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3"><strong>Folio:</strong><br><span class="zyga-code">{{ $requestData['public_id'] ?? '—' }}</span></div>
                        <div class="col-md-6 mb-3"><strong>Estado:</strong><br>{{ $requestData['status'] ?? '—' }}</div>
                        <div class="col-md-6 mb-3"><strong>Cliente:</strong><br>{{ $requestData['user']['email'] ?? '—' }}</div>
                        <div class="col-md-6 mb-3"><strong>Proveedor:</strong><br>{{ $requestData['provider']['display_name'] ?? 'Sin asignar' }}</div>
                        <div class="col-md-6 mb-3"><strong>Servicio:</strong><br>{{ $requestData['service']['name'] ?? '—' }}</div>
                        <div class="col-md-6 mb-3"><strong>Vehículo:</strong><br>{{ $requestData['vehicle']['plate'] ?? $requestData['vehicle']['id'] ?? '—' }}</div>
                        <div class="col-12 mb-3"><strong>Dirección:</strong><br>{{ $requestData['pickup_address'] ?? '—' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop