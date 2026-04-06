@extends('adminlte::page')

@section('title', 'Detalle de proveedor')

@section('content_header')
    <div>
        <h1 class="m-0">Proveedor #{{ $provider['id'] ?? '—' }}</h1>
        <p class="zyga-muted mb-0">Edición administrativa del perfil del proveedor.</p>
    </div>
@stop

@section('content')
    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

    <div class="row">
        <div class="col-lg-5 mb-4">
            <div class="card">
                <div class="card-header"><h3 class="zyga-section-title">Datos generales</h3></div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.providers.update', $provider['id']) }}">
                        @csrf @method('PATCH')
                        <div class="form-group"><label>Nombre comercial</label><input type="text" name="display_name" class="form-control" value="{{ old('display_name', $provider['display_name'] ?? '') }}" required></div>
                        <div class="form-group"><label>Tipo de proveedor</label><input type="text" name="provider_kind" class="form-control" value="{{ old('provider_kind', $provider['provider_kind'] ?? '') }}"></div>
                        <div class="form-group"><label>Status ID</label><input type="number" name="status_id" class="form-control" value="{{ old('status_id', $provider['status_id'] ?? '') }}"></div>
                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" id="is_verified" name="is_verified" value="1" @checked(old('is_verified', !empty($provider['is_verified'])))>
                            <label class="form-check-label" for="is_verified">Proveedor verificado</label>
                        </div>
                        <button class="btn btn-primary" type="submit">Guardar cambios</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-7 mb-4">
            <div class="card mb-4">
                <div class="card-header"><h3 class="zyga-section-title">Resumen</h3></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3"><strong>Correo:</strong><br>{{ $provider['user']['email'] ?? '—' }}</div>
                        <div class="col-md-6 mb-3"><strong>Estatus:</strong><br>{{ $provider['status']['name'] ?? $provider['status']['code'] ?? '—' }}</div>
                        <div class="col-md-4 mb-3"><strong>Servicios:</strong><br>{{ count($provider['services'] ?? []) }}</div>
                        <div class="col-md-4 mb-3"><strong>Horarios:</strong><br>{{ count($provider['schedules'] ?? []) }}</div>
                        <div class="col-md-4 mb-3"><strong>Documentos:</strong><br>{{ count($provider['documents'] ?? []) }}</div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header"><h3 class="zyga-section-title">Servicios asignados</h3></div>
                <div class="card-body">
                    @forelse(($provider['services'] ?? []) as $service)
                        <span class="badge badge-pill badge-soft-primary mr-1 mb-1">{{ $service['name'] ?? $service['code'] ?? 'Servicio' }}</span>
                    @empty
                        <div class="zyga-muted">No tiene servicios asociados actualmente.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@stop