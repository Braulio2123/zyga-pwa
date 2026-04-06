@extends('adminlte::page')

@section('title', 'Detalle de usuario')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0">Usuario #{{ $userData['id'] ?? '—' }}</h1>
            <p class="zyga-muted mb-0">Vista consolidada del usuario y sus relaciones disponibles.</p>
        </div>
        <a href="{{ route('admin.users.edit', $userData['id'] ?? 0) }}" class="btn btn-primary"><i class="fas fa-pen mr-1"></i> Editar</a>
    </div>
@stop

@section('content')
    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="small text-uppercase zyga-muted">Correo</div>
                    <div class="h4 mb-3">{{ $userData['email'] ?? '—' }}</div>
                    <div class="small text-uppercase zyga-muted">Roles</div>
                    <div class="mb-3">
                        @forelse(($userData['roles'] ?? []) as $role)
                            <span class="badge badge-pill badge-soft-primary mr-1">{{ $role['code'] ?? $role['name'] ?? 'rol' }}</span>
                        @empty
                            <span class="badge badge-pill badge-soft-dark">Sin roles</span>
                        @endforelse
                    </div>
                    <div class="small text-uppercase zyga-muted">Fecha de alta</div>
                    <div>{{ $userData['created_at'] ?? '—' }}</div>
                </div>
            </div>
        </div>
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header"><h3 class="zyga-section-title">Resumen operativo</h3></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="zyga-stat-item"><span>Vehículos</span><strong>{{ count($userData['vehicles'] ?? []) }}</strong></div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="zyga-stat-item"><span>Solicitudes</span><strong>{{ count($userData['assistance_requests'] ?? []) }}</strong></div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="zyga-stat-item"><span>Perfil proveedor</span><strong>{{ !empty($userData['provider']) ? 'Sí' : 'No' }}</strong></div>
                        </div>
                    </div>
                </div>
            </div>

            @if(!empty($userData['provider']))
                <div class="card mb-4">
                    <div class="card-header"><h3 class="zyga-section-title">Perfil de proveedor vinculado</h3></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3"><strong>Nombre comercial:</strong><br>{{ $userData['provider']['display_name'] ?? '—' }}</div>
                            <div class="col-md-3 mb-3"><strong>Tipo:</strong><br>{{ $userData['provider']['provider_kind'] ?? '—' }}</div>
                            <div class="col-md-3 mb-3"><strong>Verificado:</strong><br>{{ !empty($userData['provider']['is_verified']) ? 'Sí' : 'No' }}</div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="card">
                <div class="card-header"><h3 class="zyga-section-title">Solicitudes recientes del usuario</h3></div>
                <div class="card-body p-0">
                    @if(empty($userData['assistance_requests']))
                        <div class="zyga-empty">Este usuario todavía no tiene solicitudes registradas.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead><tr><th>ID</th><th>Folio</th><th>Estado</th><th>Servicio</th></tr></thead>
                                <tbody>
                                    @foreach(array_slice($userData['assistance_requests'], 0, 5) as $request)
                                        <tr>
                                            <td>{{ $request['id'] ?? '—' }}</td>
                                            <td class="zyga-code">{{ $request['public_id'] ?? '—' }}</td>
                                            <td>{{ $request['status'] ?? '—' }}</td>
                                            <td>{{ $request['service_id'] ?? '—' }}</td>
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