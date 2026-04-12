@extends('adminlte::page')

@section('title', 'Editar usuario')

@section('content_header')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
        <div>
            <h1 class="m-0">Editar usuario #{{ $userData['id'] ?? '—' }}</h1>
            <p class="zyga-muted mb-0">
                Actualiza el correo o restablece la contraseña de la cuenta desde administración.
            </p>
        </div>

        <div class="mt-3 mt-md-0">
            <a href="{{ route('admin.users.show', $userData['id'] ?? 0) }}" class="btn btn-light mr-2">
                <i class="fas fa-arrow-left mr-1"></i>
                Volver al detalle
            </a>

            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-users mr-1"></i>
                Ir a usuarios
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

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Corrige los siguientes errores:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @php
        $roles = $userData['roles'] ?? [];
        $hasProvider = !empty($userData['provider']);
        $vehiclesCount = count($userData['vehicles'] ?? []);
        $requestsCount = count($userData['assistance_requests'] ?? []);
    @endphp

    <div class="zyga-hero mb-4">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h2 class="mb-2">{{ $userData['email'] ?? 'Usuario sin correo' }}</h2>
                <p class="mb-0 text-white-50">
                    Realiza cambios administrativos controlados sobre la cuenta sin alterar
                    el resto de sus relaciones operativas.
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
                <div class="kpi-label">ID usuario</div>
                <div class="kpi-value">{{ $userData['id'] ?? '—' }}</div>
                <div class="zyga-muted">Identificador interno</div>
                <div class="kpi-icon">
                    <i class="fas fa-hashtag"></i>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3 mb-3">
            <div class="zyga-kpi">
                <div class="kpi-label">Roles</div>
                <div class="kpi-value">{{ count($roles) }}</div>
                <div class="zyga-muted">Roles asociados</div>
                <div class="kpi-icon">
                    <i class="fas fa-user-shield"></i>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3 mb-3">
            <div class="zyga-kpi">
                <div class="kpi-label">Vehículos</div>
                <div class="kpi-value">{{ $vehiclesCount }}</div>
                <div class="zyga-muted">Vehículos registrados</div>
                <div class="kpi-icon">
                    <i class="fas fa-car"></i>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3 mb-3">
            <div class="zyga-kpi">
                <div class="kpi-label">Solicitudes</div>
                <div class="kpi-value">{{ $requestsCount }}</div>
                <div class="zyga-muted">{{ $hasProvider ? 'Con perfil provider' : 'Sin perfil provider' }}</div>
                <div class="kpi-icon">
                    <i class="fas fa-route"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h3 class="zyga-section-title">Actualizar correo</h3>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('admin.users.update-email', $userData['id']) }}">
                        @csrf
                        @method('PATCH')

                        <div class="form-group">
                            <label for="email">Correo electrónico</label>
                            <input
                                type="email"
                                name="email"
                                id="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $userData['email'] ?? '') }}"
                                required
                            >
                            @error('email')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <button class="btn btn-primary btn-block" type="submit">
                            <i class="fas fa-save mr-1"></i>
                            Guardar correo
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h3 class="zyga-section-title">Restablecer contraseña</h3>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('admin.users.update-password', $userData['id']) }}">
                        @csrf
                        @method('PATCH')

                        <div class="form-group">
                            <label for="password">Nueva contraseña</label>
                            <input
                                type="password"
                                name="password"
                                id="password"
                                class="form-control @error('password') is-invalid @enderror"
                                required
                            >
                            @error('password')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">Confirmar contraseña</label>
                            <input
                                type="password"
                                name="password_confirmation"
                                id="password_confirmation"
                                class="form-control"
                                required
                            >
                        </div>

                        <button class="btn btn-primary btn-block" type="submit">
                            <i class="fas fa-key mr-1"></i>
                            Actualizar contraseña
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
