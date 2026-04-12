@extends('adminlte::page')

@section('title', 'Usuarios')

@section('content_header')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
        <div>
            <h1 class="m-0">Usuarios</h1>
            <p class="zyga-muted mb-0">
                Administración de cuentas registradas y su relación con la operación de ZYGA.
            </p>
        </div>

        <div class="mt-3 mt-md-0">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-light">
                <i class="fas fa-arrow-left mr-1"></i>
                Volver al dashboard
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

    @if($apiError)
        <div class="alert alert-warning">{{ $apiError }}</div>
    @endif

    @php
        $totalUsers = count($users);
        $adminCount = 0;
        $clientCount = 0;
        $providerCount = 0;
        $linkedProviderCount = 0;

        foreach ($users as $user) {
            $roles = $user['roles'] ?? [];
            $codes = [];

            foreach ($roles as $role) {
                $codes[] = strtolower((string) ($role['code'] ?? $role['name'] ?? ''));
            }

            if (in_array('admin', $codes, true)) {
                $adminCount++;
            }

            if (in_array('client', $codes, true)) {
                $clientCount++;
            }

            if (in_array('provider', $codes, true)) {
                $providerCount++;
            }

            if (!empty($user['provider'])) {
                $linkedProviderCount++;
            }
        }
    @endphp

    <div class="zyga-hero mb-4">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h2 class="mb-2">Panel de usuarios</h2>
                <p class="mb-0 text-white-50">
                    Consulta cuentas por correo o rol, y detecta rápidamente qué usuarios tienen
                    perfil de proveedor asociado dentro de la operación.
                </p>
            </div>

            <div class="col-lg-4 text-lg-right mt-3 mt-lg-0">
                <span class="badge badge-pill badge-soft-primary">
                    {{ $totalUsers }} usuarios
                </span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-xl-3 mb-3">
            <div class="zyga-kpi">
                <div class="kpi-label">Usuarios</div>
                <div class="kpi-value">{{ $totalUsers }}</div>
                <div class="zyga-muted">Cuentas listadas</div>
                <div class="kpi-icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3 mb-3">
            <div class="zyga-kpi">
                <div class="kpi-label">Admins</div>
                <div class="kpi-value">{{ $adminCount }}</div>
                <div class="zyga-muted">Usuarios con rol administrativo</div>
                <div class="kpi-icon">
                    <i class="fas fa-user-shield"></i>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3 mb-3">
            <div class="zyga-kpi">
                <div class="kpi-label">Clients</div>
                <div class="kpi-value">{{ $clientCount }}</div>
                <div class="zyga-muted">Usuarios cliente detectados</div>
                <div class="kpi-icon">
                    <i class="fas fa-user"></i>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3 mb-3">
            <div class="zyga-kpi">
                <div class="kpi-label">Providers</div>
                <div class="kpi-value">{{ $providerCount }}</div>
                <div class="zyga-muted">
                    {{ $linkedProviderCount }} con perfil vinculado
                </div>
                <div class="kpi-icon">
                    <i class="fas fa-people-carry"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h3 class="zyga-section-title">Filtros</h3>
        </div>

        <div class="card-body">
            <form method="GET" action="{{ route('admin.users.index') }}">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="email">Correo electrónico</label>
                        <input
                            type="text"
                            id="email"
                            name="email"
                            class="form-control"
                            placeholder="Buscar por correo"
                            value="{{ $filters['email'] ?? '' }}"
                        >
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="role">Rol</label>
                        <select name="role" id="role" class="form-control">
                            <option value="">Todos los roles</option>
                            <option value="admin" @selected(($filters['role'] ?? '') === 'admin')>Admin</option>
                            <option value="client" @selected(($filters['role'] ?? '') === 'client')>Client</option>
                            <option value="provider" @selected(($filters['role'] ?? '') === 'provider')>Provider</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex flex-column flex-md-row">
                    <button type="submit" class="btn btn-primary mr-md-2 mb-2 mb-md-0">
                        <i class="fas fa-filter mr-1"></i>
                        Aplicar filtros
                    </button>

                    <a href="{{ route('admin.users.index') }}" class="btn btn-light">
                        <i class="fas fa-eraser mr-1"></i>
                        Limpiar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center">
            <h3 class="zyga-section-title mb-2 mb-md-0">Listado de usuarios</h3>

            <span class="badge badge-pill badge-soft-dark">
                {{ $totalUsers }} registros
            </span>
        </div>

        <div class="card-body p-0">
            @if(empty($users))
                <div class="zyga-empty">
                    No se encontraron usuarios con los filtros actuales.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Correo</th>
                                <th>Roles</th>
                                <th>Proveedor vinculado</th>
                                <th>Vehículos</th>
                                <th>Solicitudes</th>
                                <th class="text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>{{ $user['id'] ?? '—' }}</td>

                                    <td>
                                        <div class="font-weight-bold">
                                            {{ $user['email'] ?? '—' }}
                                        </div>
                                        <div class="zyga-muted small">
                                            Creado: {{ $user['created_at'] ?? '—' }}
                                        </div>
                                    </td>

                                    <td>
                                        @php
                                            $roles = $user['roles'] ?? [];
                                        @endphp

                                        @forelse($roles as $role)
                                            <span class="badge badge-pill badge-soft-primary mr-1 mb-1">
                                                {{ $role['code'] ?? $role['name'] ?? 'rol' }}
                                            </span>
                                        @empty
                                            <span class="badge badge-pill badge-soft-dark">
                                                Sin rol
                                            </span>
                                        @endforelse
                                    </td>

                                    <td>
                                        <span class="badge badge-pill {{ !empty($user['provider']) ? 'badge-soft-success' : 'badge-soft-warning' }}">
                                            {{ !empty($user['provider']) ? 'Sí' : 'No' }}
                                        </span>
                                    </td>

                                    <td>{{ count($user['vehicles'] ?? []) }}</td>

                                    <td>{{ count($user['assistance_requests'] ?? []) }}</td>

                                    <td class="text-right">
                                        <a href="{{ route('admin.users.show', $user['id']) }}"
                                           class="btn btn-sm btn-light">
                                            Ver
                                        </a>

                                        <a href="{{ route('admin.users.edit', $user['id']) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            Editar
                                        </a>
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
