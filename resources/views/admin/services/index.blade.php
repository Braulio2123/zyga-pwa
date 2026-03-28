@extends('adminlte::page')

@section('title', 'Servicios')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="m-0">Servicios</h1>
        {{-- Futuro botón para crear --}}
        {{-- <a href="{{ route('admin.servicios.create') }}" class="btn btn-primary">
            <i class="fas fa-plus mr-1"></i> Nuevo servicio
        </a> --}}
    </div>
@stop

@section('content')

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(!empty($apiError))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>Advertencia:</strong> {{ $apiError }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Listado de servicios</h3>
        </div>

        <div class="card-body p-0">
            @if(empty($services))
                <div class="p-3">
                    <p class="mb-0 text-muted">No hay servicios disponibles.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover table-bordered mb-0">
                        <thead>
                            <tr>
                                <th style="width: 90px;">ID</th>
                                <th>Nombre</th>
                                <th style="width: 130px;">Activo</th>
                                <th style="width: 180px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($services as $service)
                                <tr>
                                    <td>{{ $service['id'] ?? '—' }}</td>
                                    <td>{{ $service['name'] ?? 'Sin nombre' }}</td>
                                    <td>
                                        @if(($service['is_active'] ?? false))
                                            <span class="badge badge-success">Sí</span>
                                        @else
                                            <span class="badge badge-secondary">No</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            {{-- Futuro --}}
                                            {{-- <a href="{{ route('admin.servicios.show', $service['id']) }}" class="btn btn-info btn-sm mr-1">
                                                Ver
                                            </a> --}}

                                            {{-- <a href="{{ route('admin.servicios.edit', $service['id']) }}" class="btn btn-warning btn-sm">
                                                Editar
                                            </a> --}}

                                            <button type="button" class="btn btn-secondary btn-sm" disabled>
                                                Editar
                                            </button>
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

@stop