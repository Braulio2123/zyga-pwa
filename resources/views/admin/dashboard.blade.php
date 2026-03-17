@extends('adminlte::page')

@section('title', 'Dashboard Admin')

@section('content_header')
    <h1 class="m-0">Dashboard Administrador</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>25</h3>
                    <p>Usuarios registrados</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <a href="{{ url('admin/usuarios') }}" class="small-box-footer">
                    Ver más <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>12</h3>
                    <p>Conductores activos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-id-card"></i>
                </div>
                <a href="{{ url('admin/conductores') }}" class="small-box-footer">
                    Ver más <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>18</h3>
                    <p>Solicitudes pendientes</p>
                </div>
                <div class="icon">
                    <i class="fas fa-truck-pickup"></i>
                </div>
                <a href="{{ url('admin/solicitudes') }}" class="small-box-footer">
                    Ver más <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>$8,500</h3>
                    <p>Pagos del día</p>
                </div>
                <div class="icon">
                    <i class="fas fa-credit-card"></i>
                </div>
                <a href="{{ url('admin/pagos') }}" class="small-box-footer">
                    Ver más <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Bienvenido al panel de administración</h3>
                </div>
                <div class="card-body">
                    <p>
                        Has iniciado sesión como:
                        <strong>{{ session('user.name') ?? 'Administrador' }}</strong>
                    </p>

                    <p>
                        Correo:
                        <strong>{{ session('user.email') ?? 'admin@zyga.com' }}</strong>
                    </p>

                    <p>
                        Rol:
                        <strong>{{ session('user.role') ?? 'admin' }}</strong>
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Accesos rápidos</h3>
                </div>
                <div class="card-body">
                    <a href="{{ url('admin/usuarios') }}" class="btn btn-primary btn-block mb-2">
                        Usuarios
                    </a>
                    <a href="{{ url('admin/solicitudes') }}" class="btn btn-success btn-block mb-2">
                        Solicitudes
                    </a>
                    <a href="{{ url('admin/pagos') }}" class="btn btn-warning btn-block mb-2">
                        Pagos
                    </a>
                    <a href="{{ url('admin/reportes') }}" class="btn btn-info btn-block">
                        Reportes
                    </a>
                </div>
            </div>
        </div>
    </div>
@stop