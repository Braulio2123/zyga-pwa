@extends('adminlte::page')

@section('title', 'Proveedores')

@section('content_header')
    <div>
        <h1 class="m-0">Proveedores</h1>
        <p class="zyga-muted mb-0">Operación y seguimiento de prestadores de servicio registrados.</p>
    </div>
@stop

@section('content')
    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif
    @if($apiError) <div class="alert alert-warning">{{ $apiError }}</div> @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="zyga-section-title">Listado de proveedores</h3>
            <span class="badge badge-pill badge-soft-dark">{{ count($providers) }} registros</span>
        </div>
        <div class="card-body p-0">
            @if(empty($providers))
                <div class="zyga-empty">No hay proveedores para mostrar.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead><tr><th>ID</th><th>Proveedor</th><th>Tipo</th><th>Estado</th><th>Verificado</th><th>Servicios</th><th></th></tr></thead>
                        <tbody>
                            @foreach($providers as $provider)
                                <tr>
                                    <td>{{ $provider['id'] ?? '—' }}</td>
                                    <td>
                                        <div class="font-weight-bold">{{ $provider['display_name'] ?? 'Sin nombre comercial' }}</div>
                                        <div class="small zyga-muted">{{ $provider['user']['email'] ?? 'Sin correo asociado' }}</div>
                                    </td>
                                    <td>{{ $provider['provider_kind'] ?? '—' }}</td>
                                    <td>{{ $provider['status']['name'] ?? $provider['status']['code'] ?? '—' }}</td>
                                    <td><span class="badge badge-pill {{ !empty($provider['is_verified']) ? 'badge-soft-success' : 'badge-soft-warning' }}">{{ !empty($provider['is_verified']) ? 'Sí' : 'No' }}</span></td>
                                    <td>{{ count($provider['services'] ?? []) }}</td>
                                    <td><a href="{{ route('admin.providers.show', $provider['id']) }}" class="btn btn-sm btn-outline-primary">Detalle</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@stop