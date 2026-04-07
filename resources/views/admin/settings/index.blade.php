@extends('adminlte::page')

@section('title', 'Configuración')

@section('content_header')
    <div>
        <h1 class="m-0">Configuración del panel</h1>
        <p class="zyga-muted mb-0">Estado actual de conexión, sesión y parámetros base del administrador web.</p>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header"><h3 class="zyga-section-title">Conectividad API</h3></div>
                <div class="card-body">
                    <div class="mb-3"><strong>Base URL:</strong><br><span class="zyga-code">{{ $apiBaseUrl ?: 'No configurada' }}</span></div>
                    <div class="mb-3"><strong>Estado:</strong><br><span class="badge badge-pill {{ $apiStatus === 'Conectada' ? 'badge-soft-success' : 'badge-soft-warning' }}">{{ $apiStatus }}</span></div>
                    <div><strong>Mensaje:</strong><br>{{ $apiMessage }}</div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header"><h3 class="zyga-section-title">Sesión web</h3></div>
                <div class="card-body">
                    <div class="mb-3"><strong>Nombre:</strong><br>{{ $sessionUser['name'] ?? '—' }}</div>
                    <div class="mb-3"><strong>Correo:</strong><br>{{ $sessionUser['email'] ?? '—' }}</div>
                    <div><strong>Rol:</strong><br>{{ $sessionUser['role'] ?? '—' }}</div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header"><h3 class="zyga-section-title">Perfil resuelto desde API</h3></div>
                <div class="card-body">
                    <div class="mb-3"><strong>ID:</strong><br>{{ $profile['id'] ?? '—' }}</div>
                    <div class="mb-3"><strong>Email:</strong><br>{{ $profile['email'] ?? '—' }}</div>
                    <div><strong>Actualizado:</strong><br>{{ $profile['updated_at'] ?? '—' }}</div>
                </div>
            </div>
        </div>
    </div>
@stop