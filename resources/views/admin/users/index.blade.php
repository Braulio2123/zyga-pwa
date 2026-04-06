@extends('adminlte::page')

@section('title', 'Usuarios')

@section('content_header')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
        <div>
            <h1 class="m-0">Usuarios</h1>
            <p class="zyga-muted mb-0">Administración de cuentas reales registradas en ZYGA.</p>
        </div>
    </div>
@stop

@section('content')
    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif
    @if($apiError) <div class="alert alert-warning">{{ $apiError }}</div> @endif

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.users.index') }}" class="zyga-toolbar">
                <input type="text" name="email" class="form-control" placeholder="Filtrar por correo" value="{{ $filters['email'] ?? '' }}">
                <select name="role" class="form-control">
                    <option value="">Todos los roles</option>
                    <option value="admin" @selected(($filters['role'] ?? '') === 'admin')>Admin</option>
                    <option value="client" @selected(($filters['role'] ?? '') === 'client')>Client</option>
                    <option value="provider" @selected(($filters['role'] ?? '') === 'provider')>Provider</option>
                </select>
                <button class="btn btn-primary" type="submit"><i class="fas fa-search mr-1"></i> Filtrar</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-light">Limpiar</a>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="zyga-section-title">Listado de usuarios</h3>
            <span class="badge badge-pill badge-soft-dark">{{ count($users) }} registros</span>
        </div>
        <div class="card-body p-0">
            @if(empty($users))
                <div class="zyga-empty">No se encontraron usuarios con los filtros actuales.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Correo</th>
                                <th>Roles</th>
                                <th>Proveedor vinculado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>{{ $user['id'] ?? '—' }}</td>
                                    <td>{{ $user['email'] ?? '—' }}</td>
                                    <td>
                                        @php $roles = $user['roles'] ?? []; @endphp
                                        @forelse($roles as $role)
                                            <span class="badge badge-pill badge-soft-primary mr-1">{{ $role['code'] ?? $role['name'] ?? 'rol' }}</span>
                                        @empty
                                            <span class="badge badge-pill badge-soft-dark">Sin rol</span>
                                        @endforelse
                                    </td>
                                    <td>{{ !empty($user['provider']) ? 'Sí' : 'No' }}</td>
                                    <td>
                                        <a href="{{ route('admin.users.show', $user['id']) }}" class="btn btn-sm btn-light">Ver</a>
                                        <a href="{{ route('admin.users.edit', $user['id']) }}" class="btn btn-sm btn-outline-primary">Editar</a>
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