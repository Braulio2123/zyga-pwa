@extends('adminlte::page')

@section('title', 'Usuarios')

@section('content_header')
    <h1>Usuarios</h1>
@stop

@section('content')
    <div class="container-fluid">

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
            <div class="alert alert-danger">
                {{ $apiError }}
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Listado de usuarios</h3>
            </div>

            <div class="card-body table-responsive p-0">
                <table class="table table-hover table-striped mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Correo</th>
                            <th>Roles</th>
                            <th>Creado</th>
                            <th>Actualizado</th>
                            <th width="180">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>{{ $user['id'] ?? 'N/A' }}</td>
                                <td>{{ $user['email'] ?? 'Sin correo' }}</td>
                                <td>
                                    @php
                                        $roles = $user['roles'] ?? [];
                                    @endphp

                                    @if(!empty($roles))
                                        @foreach($roles as $role)
                                            <span class="badge badge-primary mr-1">
                                                {{ $role['name'] ?? $role['code'] ?? 'Rol' }}
                                            </span>
                                        @endforeach
                                    @else
                                        <span class="badge badge-secondary">Sin roles</span>
                                    @endif
                                </td>
                                <td>{{ $user['created_at'] ?? 'N/A' }}</td>
                                <td>{{ $user['updated_at'] ?? 'N/A' }}</td>
                                <td>
                                    @if(isset($user['id']))
                                        <a href="{{ route('admin.users.show', $user['id']) }}" class="btn btn-info btn-sm">
                                            Ver
                                        </a>

                                        <a href="{{ route('admin.users.edit', $user['id']) }}" class="btn btn-warning btn-sm">
                                            Editar
                                        </a>
                                    @else
                                        <span class="text-muted">Sin acciones</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No hay usuarios para mostrar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop