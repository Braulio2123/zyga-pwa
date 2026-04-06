@extends('adminlte::page')

@section('title', 'Editar usuario')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0">Editar usuario #{{ $userData['id'] ?? '—' }}</h1>
            <p class="zyga-muted mb-0">Actualiza correo o restablece contraseña desde el panel admin.</p>
        </div>
        <a href="{{ route('admin.users.show', $userData['id'] ?? 0) }}" class="btn btn-light">Volver al detalle</a>
    </div>
@stop

@section('content')
    @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header"><h3 class="zyga-section-title">Actualizar correo</h3></div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.users.update-email', $userData['id']) }}">
                        @csrf @method('PATCH')
                        <div class="form-group">
                            <label>Correo electrónico</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $userData['email'] ?? '') }}" required>
                            @error('email') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                        <button class="btn btn-primary" type="submit">Guardar correo</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header"><h3 class="zyga-section-title">Restablecer contraseña</h3></div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.users.update-password', $userData['id']) }}">
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
@stop