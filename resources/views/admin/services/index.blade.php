@extends('adminlte::page')

@section('title', 'Servicios')

@section('content_header')
    <div>
        <h1 class="m-0">Catálogo de servicios</h1>
        <p class="zyga-muted mb-0">Administra el catálogo de servicios disponible para la operación de ZYGA.</p>
    </div>
@stop

@section('content')
    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif
    @if($apiError) <div class="alert alert-warning">{{ $apiError }}</div> @endif

    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header"><h3 class="zyga-section-title">Nuevo servicio</h3></div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.services.store') }}">
                        @csrf
                        <div class="form-group"><label>Código</label><input type="text" name="code" class="form-control" required></div>
                        <div class="form-group"><label>Nombre</label><input type="text" name="name" class="form-control" required></div>
                        <div class="form-group"><label>Descripción</label><textarea name="description" class="form-control" rows="4"></textarea></div>
                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" checked>
                            <label class="form-check-label" for="is_active">Activo</label>
                        </div>
                        <button class="btn btn-primary" type="submit">Crear servicio</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="zyga-section-title">Servicios registrados</h3>
                    <span class="badge badge-pill badge-soft-dark">{{ count($services) }} registros</span>
                </div>
                <div class="card-body p-0">
                    @if(empty($services))
                        <div class="zyga-empty">No hay servicios disponibles todavía.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead><tr><th>ID</th><th>Código</th><th>Nombre</th><th>Estado</th><th style="width: 280px;">Acciones</th></tr></thead>
                                <tbody>
                                    @foreach($services as $service)
                                        <tr>
                                            <td>{{ $service['id'] ?? '—' }}</td>
                                            <td class="zyga-code">{{ $service['code'] ?? '—' }}</td>
                                            <td>
                                                <div class="font-weight-bold">{{ $service['name'] ?? 'Sin nombre' }}</div>
                                                <div class="small zyga-muted">{{ $service['description'] ?? 'Sin descripción' }}</div>
                                            </td>
                                            <td><span class="badge badge-pill {{ !empty($service['is_active']) ? 'badge-soft-success' : 'badge-soft-dark' }}">{{ !empty($service['is_active']) ? 'Activo' : 'Inactivo' }}</span></td>
                                            <td>
                                                <form method="POST" action="{{ route('admin.services.update', $service['id']) }}" class="row mx-0 align-items-center">
                                                    @csrf @method('PATCH')
                                                    <div class="col-md-4 px-1"><input type="text" name="code" class="form-control form-control-sm" value="{{ $service['code'] ?? '' }}" required></div>
                                                    <div class="col-md-4 px-1"><input type="text" name="name" class="form-control form-control-sm" value="{{ $service['name'] ?? '' }}" required></div>
                                                    <input type="hidden" name="description" value="{{ $service['description'] ?? '' }}">
                                                    <input type="hidden" name="is_active" value="{{ !empty($service['is_active']) ? 1 : 0 }}">
                                                    <div class="col-md-4 px-1 d-flex">
                                                        <button class="btn btn-sm btn-light mr-2" type="submit">Guardar</button>
                                                </form>
                                                <form method="POST" action="{{ route('admin.services.destroy', $service['id']) }}" onsubmit="return confirm('¿Eliminar este servicio?');">
                                                    @csrf @method('DELETE')
                                                    <button class="btn btn-sm btn-outline-danger" type="submit">Eliminar</button>
                                                </form>
                                                    </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop