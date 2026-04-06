@extends('adminlte::page')

@section('title', 'Mi perfil')

@section('content_header')
    <div>
        <h1 class="m-0">Mi perfil administrativo</h1>
        <p class="zyga-muted mb-0">Gestión de credenciales del administrador autenticado.</p>
    </div>
@stop

@section('content')
    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif
    @if($apiError) <div class="alert alert-warning">{{ $apiError }}</div> @endif

    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="small text-uppercase zyga-muted">Administrador</div>
                    <div class="h4 mb-1">{{ $profile['name'] ?? $profile['email'] ?? 'Usuario admin' }}</div>
                    <div class="zyga-muted mb-3">{{ $profile['email'] ?? '—' }}</div>
                    <span class="badge badge-pill badge-soft-primary">Rol admin</span>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-header"><h3 class="zyga-section-title">Actualizar correo</h3></div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.profile.update-email') }}">
                                @csrf @method('PUT')
                                <div class="form-group">
                                    <label>Correo electrónico</label>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $profile['email'] ?? '') }}" required>
                                    @error('email') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                                <button class="btn btn-primary" type="submit">Actualizar correo</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-header"><h3 class="zyga-section-title">Cambiar contraseña</h3></div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.profile.update-password') }}">
                                @csrf @method('PATCH')
                                <div class="form-group">
                                    <label>Nueva contraseña</label>
                                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                                    @error('password') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group">
                                    <label>Confirmar contraseña</label>
                                    <input type="password" name="password_confirmation" class="form-control" required>
                                </div>
                                <button class="btn btn-primary" type="submit">Actualizar contraseña</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop