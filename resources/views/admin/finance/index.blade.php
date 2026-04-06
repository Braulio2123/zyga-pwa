@extends('adminlte::page')

@section('title', 'Pagos y finanzas')

@section('content_header')
    <div>
        <h1 class="m-0">Pagos y finanzas</h1>
        <p class="zyga-muted mb-0">Supervisión de pagos y transacciones registrados por la API administrativa.</p>
    </div>
@stop

@section('content')
    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif
    @if(!empty($apiErrors))
        <div class="alert alert-warning"><ul class="mb-0">@foreach($apiErrors as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.finance.index') }}" class="zyga-toolbar">
                <select name="status" class="form-control">
                    <option value="">Todos los estados</option>
                    @foreach(['pending','completed','failed'] as $status)
                        <option value="{{ $status }}" @selected(($filters['status'] ?? '') === $status)>{{ $status }}</option>
                    @endforeach
                </select>
                <input type="text" name="payment_method" class="form-control" placeholder="Método de pago" value="{{ $filters['payment_method'] ?? '' }}">
                <input type="text" name="transaction_id" class="form-control" placeholder="Transaction ID" value="{{ $filters['transaction_id'] ?? '' }}">
                <button class="btn btn-primary">Filtrar</button>
                <a href="{{ route('admin.finance.index') }}" class="btn btn-light">Limpiar</a>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-7 mb-4">
            <div class="card">
                <div class="card-header"><h3 class="zyga-section-title">Pagos</h3></div>
                <div class="card-body p-0">
                    @if(empty($payments))
                        <div class="zyga-empty">No hay pagos registrados.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead><tr><th>ID</th><th>Monto</th><th>Método</th><th>Estado</th><th>Solicitud</th><th></th></tr></thead>
                                <tbody>
                                    @foreach($payments as $payment)
                                        <tr>
                                            <td>{{ $payment['id'] ?? '—' }}</td>
                                            <td>${{ number_format((float) ($payment['amount'] ?? 0), 2) }}</td>
                                            <td>{{ $payment['payment_method'] ?? '—' }}</td>
                                            <td>{{ $payment['status'] ?? '—' }}</td>
                                            <td>{{ $payment['assistance_request']['public_id'] ?? $payment['assistance_request_id'] ?? '—' }}</td>
                                            <td><a href="{{ route('admin.finance.show-payment', $payment['id']) }}" class="btn btn-sm btn-outline-primary">Ver</a></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-5 mb-4">
            <div class="card">
                <div class="card-header"><h3 class="zyga-section-title">Transacciones recientes</h3></div>
                <div class="card-body p-0">
                    @if(empty($transactions))
                        <div class="zyga-empty">No hay transacciones para mostrar.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead><tr><th>ID</th><th>Gateway</th><th>Evento</th><th>Pago</th></tr></thead>
                                <tbody>
                                    @foreach(array_slice($transactions, 0, 10) as $transaction)
                                        <tr>
                                            <td>{{ $transaction['id'] ?? '—' }}</td>
                                            <td>{{ $transaction['gateway'] ?? '—' }}</td>
                                            <td>{{ $transaction['gateway_event_id'] ?? '—' }}</td>
                                            <td>{{ $transaction['payment_id'] ?? $transaction['payment']['id'] ?? '—' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop