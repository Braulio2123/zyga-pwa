@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Mis Servicios</h2>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Precio</th>
            </tr>
        </thead>
        <tbody>
            @foreach($servicios['data'] ?? [] as $servicio)
                <tr>
                    <td>{{ $servicio['id'] }}</td>
                    <td>{{ $servicio['name'] }}</td>
                    <td>{{ $servicio['price'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection