@extends('adminlte::page')

@section('title', 'Detalle de usuario')

@section('content_header')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
        <div>
            <h1 class="m-0">Usuario #{{ $userData['id'] ?? '—' }}</h1>
            <p class="zyga-muted mb-0">
                Revisión administrativa de cuenta, roles y vínculos operativos del usuario.
            </p>
        </div>

        <div class="mt-3 mt-md-0">
            <a href="{{ route('admin.users.index') }}" class="btn btn-light mr-2">
                <i class="fas fa-arrow-left mr-1"></i>
                Volver a usuarios
            </a>

            <a href="{{ route('admin.users.edit', $userData['id'] ?? 0) }}" class="btn btn-primary">
                <i class="fas fa-pen mr-1"></i>
                Editar usuario
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

    @php
        $roles = $userData['roles'] ?? [];
        $vehicles = $userData['vehicles'] ?? [];
        $requests = $userData['assistance_requests'] ?? [];
        $hasProvider = !empty($userData['provider']);
    @endphp

    <div class="zyga-hero mb-4">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h2 class="mb-2">{{ $userData['email'] ?? 'Usuario sin correo' }}</h2>
                <p class="mb-0 text-white-50">
                    Consulta desde administración la composición de roles del usuario,
                    su actividad dentro del sistema y sus relaciones principales.
                </p>
            </div>

            <div class="col-lg-4 text-lg-right mt-3 mt-lg-0">
                @forelse($roles as $role)
                    <span class="badge badge-pill badge-soft-primary mb-1">
                        {{ $role['code'] ?? $role['name'] ?? 'rol' }}
                    </span>
                @empty
                    <span class="badge badge-pill badge-soft-dark">
                        Sin roles
                    </span>
                @endforelse
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-xl-3 mb-3">
            <div class="zyga-kpi">
                <div class="kpi-label">Roles</div>
                <div class="kpi-value">{{ count($roles) }}</div>
                <div class="zyga-muted">Roles asignados</div>
                <div class="kpi-icon">
                    <i class="fas fa-user-shield"></i>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3 mb-3">
            <div class="zyga-kpi">
                <div class="kpi-label">Vehículos</div>
                <div class="kpi-value">{{ count($vehicles) }}</div>
                <div class="zyga-muted">Vehículos asociados</div>
                <div class="kpi-icon">
                    <i class="fas fa-car"></i>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3 mb-3">
            <div class="zyga-kpi">
                <div class="kpi-label">Solicitudes</div>
                <div class="kpi-value">{{ count($requests) }}</div>
                <div class="zyga-muted">Solicitudes registradas</div>
                <div class="kpi-icon">
                    <i class="fas fa-route"></i>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3 mb-3">
            <div class="zyga-kpi">
                <div class="kpi-label">Perfil provider</div>
                <div class="kpi-value">{{ $hasProvider ? 'Sí' : 'No' }}</div>
                <div class="zyga-muted">Vinculación como proveedor</div>
                <div class="kpi-icon">
                    <i class="fas fa-people-carry"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h3 class="zyga-section-title">Resumen general</h3>
                </div>

                <div class="card-body">
                    <div class="mb-3">
                        <label>ID interno</label>
                        <div class="zyga-code">{{ $userData['id'] ?? '—' }}</div>
                    </div>

                    <div class="mb-3">
                        <label>Correo electrónico</label>
                        <div>{{ $userData['email'] ?? '—' }}</div>
                    </div>

                    <div class="mb-3">
                        <label>Roles</label>
                        <div>
                            @forelse($roles as $role)
                                <span class="badge badge-pill badge-soft-primary mr-1 mb-1">
                                    {{ $role['code'] ?? $role['name'] ?? 'rol' }}
                                </span>
                            @empty
                                <span class="badge badge-pill badge-soft-dark">
                                    Sin roles
                                </span>
                            @endforelse
                        </div>
                    </div>

                    <div class="mb-3">
                        <label>Proveedor vinculado</label>
                        <div>
                            <span class="badge badge-pill {{ $hasProvider ? 'badge-soft-success' : 'badge-soft-warning' }}">
                                {{ $hasProvider ? 'Sí' : 'No' }}
                            </span>
                        </div>
                    </div>

                    <div class="mb-0">
                        <label>Fecha de alta</label>
                        <div>{{ $userData['created_at'] ?? '—' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-8 mb-4">
            @if($hasProvider)
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="zyga-section-title">Perfil de proveedor vinculado</h3>

                        @if(!empty($userData['provider']['id']))
                            <a href="{{ route('admin.providers.show', $userData['provider']['id']) }}"
                               class="btn btn-sm btn-outline-primary">
                                Ver proveedor
                            </a>
                        @endif
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Nombre comercial</label>
                                <div>{{ $userData['provider']['display_name'] ?? '—' }}</div>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label>Tipo</label>
                                <div>{{ $userData['provider']['provider_kind'] ?? '—' }}</div>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label>Verificación</label>
                                <div>
                                    <span class="badge badge-pill {{ !empty($userData['provider']['is_verified']) ? 'badge-soft-success' : 'badge-soft-warning' }}">
                                        {{ !empty($userData['provider']['is_verified']) ? 'Verificado' : 'Pendiente' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="zyga-section-title">Vehículos del usuario</h3>
                </div>

                <div class="card-body p-0">
                    @if(empty($vehicles))
                        <div class="zyga-empty">
                            Este usuario no tiene vehículos registrados.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Placa</th>
                                        <th>Tipo</th>
                                        <th>Marca / Modelo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(array_slice($vehicles, 0, 10) as $vehicle)
                                        <tr>
                                            <td>{{ $vehicle['id'] ?? '—' }}</td>
                                            <td class="zyga-code">{{ $vehicle['plate'] ?? '—' }}</td>
                                            <td>{{ $vehicle['vehicle_type']['name'] ?? $vehicle['vehicle_type_id'] ?? '—' }}</td>
                                            <td>
                                                {{ trim(($vehicle['brand'] ?? '') . ' ' . ($vehicle['model'] ?? '')) ?: '—' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="zyga-section-title">Solicitudes recientes del usuario</h3>
                </div>

                <div class="card-body p-0">
                    @if(empty($requests))
                        <div class="zyga-empty">
                            Este usuario todavía no tiene solicitudes registradas.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Folio</th>
                                        <th>Estado</th>
                                        <th>Servicio</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(array_slice($requests, 0, 8) as $request)
                                        @php
                                            $status = $request['status'] ?? '—';
                                            $statusClass = 'badge-soft-dark';

                                            if ($status === 'completed') {
                                                $statusClass = 'badge-soft-success';
                                            } elseif ($status === 'cancelled') {
                                                $statusClass = 'badge-soft-danger';
                                            } elseif (in_array($status, ['created', 'assigned', 'in_progress'], true)) {
                                                $statusClass = 'badge-soft-warning';
                                            }
                                        @endphp

                                        <tr>
                                            <td>{{ $request['id'] ?? '—' }}</td>
                                            <td class="zyga-code">{{ $request['public_id'] ?? '—' }}</td>
                                            <td>
                                                <span class="badge badge-pill {{ $statusClass }}">
                                                    {{ $status }}
                                                </span>
                                            </td>
                                            <td>{{ $request['service']['name'] ?? $request['service_id'] ?? '—' }}</td>
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
