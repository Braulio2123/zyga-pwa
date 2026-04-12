@extends('adminlte::page')

@section('title', 'Ficha de pago')

@section('content_header')
    <div>
        <h1 class="m-0">Ficha de pago #{{ $payment['id'] ?? '—' }}</h1>
        <p class="zyga-muted mb-0">Ajuste administrativo del cobro y revisión de su trazabilidad.</p>
    </div>
@stop

@section('content')
    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

    <div class="row">
        <div class="col-lg-5 mb-4">
            <div class="card">
                <div class="card-header"><h3 class="zyga-section-title">Ajuste del pago</h3></div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.finance.update-payment', $payment['id']) }}">
                        @csrf @method('PATCH')
                        <div class="form-group"><label>Monto</label><input type="number" step="0.01" name="amount" class="form-control" value="{{ old('amount', $payment['amount'] ?? 0) }}" required></div>
                        <div class="form-group"><label>Método de pago</label><input type="text" name="payment_method" class="form-control" value="{{ old('payment_method', $payment['payment_method'] ?? '') }}" required></div>
                        <div class="form-group"><label>Transaction ID</label><input type="text" name="transaction_id" class="form-control" value="{{ old('transaction_id', $payment['transaction_id'] ?? '') }}"></div>
                        <div class="form-group"><label>Estado</label>
                            <select name="status" class="form-control" required>
                                @foreach(['pending' => 'Pendiente', 'completed' => 'Completado', 'failed' => 'Fallido'] as $status => $label)
                                    <option value="{{ $status }}" @selected(($payment['status'] ?? '') === $status)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button class="btn btn-primary btn-block">Guardar cambios</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-7 mb-4">
            <div class="card">
                <div class="card-header"><h3 class="zyga-section-title">Contexto financiero</h3></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3"><strong>Solicitud asociada:</strong><br>{{ $payment['assistance_request']['public_id'] ?? $payment['assistance_request_id'] ?? '—' }}</div>
                        <div class="col-md-6 mb-3"><strong>Estado:</strong><br>{{ ucfirst($payment['status'] ?? '—') }}</div>
                        <div class="col-md-6 mb-3"><strong>Monto:</strong><br>${{ number_format((float) ($payment['amount'] ?? 0), 2) }}</div>
                        <div class="col-md-6 mb-3"><strong>Método:</strong><br>{{ $payment['payment_method'] ?? '—' }}</div>
                    </div>
                    <hr>
                    <h6 class="font-weight-bold">Transacciones ligadas</h6>
                    @if(empty($payment['transactions']))
                        <div class="zyga-muted">Este pago no tiene transacciones registradas.</div>
                    @else
                        <ul class="mb-0 pl-3">
                            @foreach($payment['transactions'] as $transaction)
                                <li>{{ $transaction['gateway'] ?? 'gateway' }} · {{ $transaction['gateway_event_id'] ?? 'sin evento' }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop
