@extends('adminlte::page')

@section('title', 'Solicitudes')

@section('content_header')
    <div>
        <h1 class="m-0">Solicitudes de asistencia</h1>
        <p class="zyga-muted mb-0">Seguimiento administrativo del flujo operativo de asistencia vial.</p>
    </div>
@stop

@section('content')
    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif
    @if($apiError) <div class="alert alert-warning">{{ $apiError }}</div> @endif

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.assistance.index') }}" class="zyga-toolbar">
                <input type="text" name="public_id" class="form-control" placeholder="Folio público" value="{{ $filters['public_id'] ?? '' }}">
                <input type="text" name="user_id" class="form-control" placeholder="User ID" value="{{ $filters['user_id'] ?? '' }}">
                <select name="status" class="form-control">
                    <option value="">Todos los estados</option>
                    @foreach(['created','assigned','in_progress','completed','cancelled'] as $status)
                        <option value="{{ $status }}" @selected(($filters['status'] ?? '') === $status)>{{ $status }}</option>
                    @endforeach
                </select>
                <button class="btn btn-primary">Filtrar</button>
                <a href="{{ route('admin.assistance.index') }}" class="btn btn-light">Limpiar</a>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="zyga-section-title">Solicitudes</h3>
            <span class="badge badge-pill badge-soft-dark">{{ count($requests) }} resultados</span>
        </div>
        <div class="card-body p-0">
            @if(empty($requests))
                <div class="zyga-empty">No hay solicitudes para los filtros seleccionados.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead><tr><th>ID</th><th>Folio</th><th>Cliente</th><th>Servicio</th><th>Proveedor</th><th>Estado</th><th></th></tr></thead>
                        <tbody>
                            @foreach($requests as $request)
                                @php $status = $request['status'] ?? '—'; @endphp
                                <tr>
                                    <td>{{ $request['id'] ?? '—' }}</td>
                                    <td class="zyga-code">{{ $request['public_id'] ?? '—' }}</td>
                                    <td>{{ $request['user']['email'] ?? '—' }}</td>
                                    <td>{{ $request['service']['name'] ?? '—' }}</td>
                                    <td>{{ $request['provider']['display_name'] ?? 'Sin asignar' }}</td>
                                    <td><span class="badge badge-pill {{ $status === 'completed' ? 'badge-soft-success' : ($status === 'cancelled' ? 'badge-soft-danger' : 'badge-soft-warning') }}">{{ $status }}</span></td>
                                    <td><a href="{{ route('admin.assistance.show', $request['id']) }}" class="btn btn-sm btn-outline-primary">Detalle</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@stop