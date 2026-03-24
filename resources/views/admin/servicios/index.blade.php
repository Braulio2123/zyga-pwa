@extends('adminlte::page')

@section('title', 'Servicios')

@section('content_header')
    <h1>Servicios</h1>
@stop

@section('content')

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<div class="card">
    <div class="card-body">

        @if(empty($services))
            <p>No hay servicios disponibles.</p>
        @else
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Activo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($services as $service)
                        <tr>
                            <td>{{ $service['id'] }}</td>
                            <td>{{ $service['name'] }}</td>
                            <td>
                                {{ $service['is_active'] ? 'Sí' : 'No' }}
                            </td>
                            <td><button>Editar</button></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

    </div>
</div>

@stop