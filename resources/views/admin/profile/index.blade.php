@extends('adminlte::page')

@section('title', 'Mi Perfil')

@section('content_header')
    <h1 class="m-0 text-dark">Mi Perfil</h1>
@stop

@section('content')
    <div class="container-fluid">

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if (!empty($apiError))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Advertencia:</strong> {{ $apiError }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="row">
            <div class="col-md-4">
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile text-center">
                        <div class="mb-3">
                            <div class="rounded-circle bg-primary d-inline-flex align-items-center justify-content-center"
                                 style="width: 90px; height: 90px; font-size: 2rem; color: white;">
                                {{ strtoupper(substr($profile['email'] ?? 'A', 0, 1)) }}
                            </div>
                        </div>

                        <h3 class="profile-username text-center mb-1">
                            {{ $profile['name'] ?? 'Administrador' }}
                        </h3>

                        <p class="text-muted text-center mb-3">
                            {{ $profile['email'] ?? 'Sin correo disponible' }}
                        </p>

                        <ul class="list-group list-group-unbordered mb-3 text-left">
                            <li class="list-group-item">
                                <b>ID</b>
                                <span class="float-right">{{ $profile['id'] ?? '—' }}</span>
                            </li>
                            <li class="list-group-item">
                                <b>Rol</b>
                                <span class="float-right">
                                    @php
                                        $roleName = 'Administrador';

                                        if (!empty($profile['roles']) && is_array($profile['roles'])) {
                                            $roleName = $profile['roles'][0]['name'] ?? $profile['roles'][0]['code'] ?? 'Administrador';
                                        } elseif (!empty(session('user.role'))) {
                                            $roleName = session('user.role');
                                        }
                                    @endphp

                                    {{ ucfirst($roleName) }}
                                </span>
                            </li>
                            <li class="list-group-item">
                                <b>Estado</b>
                                <span class="float-right text-success">Activo</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Actualizar correo electrónico</h3>
                    </div>

                    <form action="{{ route('admin.profile.update-email') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="card-body">
                            <div class="form-group">
                                <label for="email">Correo electrónico</label>
                                <input
                                    type="email"
                                    name="email"
                                    id="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $profile['email'] ?? '') }}"
                                    placeholder="Ingresa tu correo"
                                >
                                @error('email')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i> Actualizar correo
                            </button>
                        </div>
                    </form>
                </div>

                <div class="card card-outline card-secondary">
                    <div class="card-header">
                        <h3 class="card-title">Cambiar contraseña</h3>
                    </div>

                    <form action="{{ route('admin.profile.update-password') }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="card-body">
                            <div class="form-group">
                                <label for="current_password">Contraseña actual</label>
                                <input
                                    type="password"
                                    name="current_password"
                                    id="current_password"
                                    class="form-control @error('current_password') is-invalid @enderror"
                                    placeholder="Ingresa tu contraseña actual"
                                >
                                @error('current_password')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password">Nueva contraseña</label>
                                <input
                                    type="password"
                                    name="password"
                                    id="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Ingresa tu nueva contraseña"
                                >
                                @error('password')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password_confirmation">Confirmar nueva contraseña</label>
                                <input
                                    type="password"
                                    name="password_confirmation"
                                    id="password_confirmation"
                                    class="form-control"
                                    placeholder="Confirma tu nueva contraseña"
                                >
                            </div>
                        </div>

                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-secondary">
                                <i class="fas fa-key mr-1"></i> Actualizar contraseña
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop