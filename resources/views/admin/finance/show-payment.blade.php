@extends('adminlte::page')

@section('title', 'Detalle de pago')

@section('content_header')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
        <div>
            <h1 class="m-0">Pago #{{ $payment['id'] ?? '—' }}</h1>
            <p class="zyga-muted mb-0">
                Revisión administrativa del pago, su solicitud asociada y sus transacciones.
            </p>
        </div>

        <div class="mt-3 mt-md-0">
            <a href="{{ route('admin.finance.index') }}" class="btn btn-light">
                <i class="fas fa-arrow-left mr-1"></i>
                Volver a finanzas
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
        $status = $payment['status'] ?? '—';
        $statusClass = 'badge-soft-dark';

        if ($status === 'completed') {
            $statusClass = 'badge-soft-success';
        } elseif ($status === 'pending') {
            $statusClass = 'badge-soft-warning';
        } elseif ($status === 'failed') {
            $statusClass = 'badge-soft-danger';
        }
    @endphp

    <div class="zyga-hero mb-4">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h2 class="mb-2">Detalle financiero del pago</h2>
                <p class="mb-0 text-white-50">
                    Aquí puedes validar monto, método, transacción y estado del pago,
                    además de revisar su contexto operativo.
                </p>
            </div>

            <div class="col-lg-4 text-lg-right mt-3 mt-lg-0">
                <span class="badge badge-pill {{ $statusClass }}">
                    {{ $status }}
                </span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h3 class="zyga-section-title">Editar pago</h3>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('admin.finance.update-payment', $payment['id']) }}">
                        @csrf
                        @method('PATCH')

                        <div class="form-group">
                            <label for="amount">Monto</label>
                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                name="amount"
                                id="amount"
                                class="form-control"
                                value="{{ old('amount', $payment['amount'] ?? 0) }}"
                                required
                            >
                        </div>

                        <div class="form-group">
                            <label for="payment_method">Método de pago</label>
                            <input
                                type="text"
                                name="payment_method"
                                id="payment_method"
                                class="form-control"
                                value="{{ old('payment_method', $payment['payment_method'] ?? '') }}"
                                placeholder="Ej. efectivo, tarjeta, transferencia"
                                required
                            >
                        </div>

                        <div class="form-group">
                            <label for="transaction_id">Transaction ID</label>
                            <input
                                type="text"
                                name="transaction_id"
                                id="transaction_id"
                                class="form-control"
                                value="{{ old('transaction_id', $payment['transaction_id'] ?? '') }}"
                                placeholder="Opcional"
                            >
                        </div>

                        <div class="form-group">
                            <label for="status">Estado</label>
                            <select name="status" id="status" class="form-control" required>
                                @foreach(['pending', 'completed', 'failed'] as $itemStatus)
                                    <option value="{{ $itemStatus }}" @selected(old('status', $payment['status'] ?? '') === $itemStatus)>
                                        {{ $itemStatus }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-save mr-1"></i>
                            Guardar cambios
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-xl-8 mb-4">
            <div class="row">
                <div class="col-md-6 col-xl-3 mb-3">
                    <div class="zyga-kpi">
                        <div class="kpi-label">Monto</div>
                        <div class="kpi-value">${{ number_format((float) ($payment['amount'] ?? 0), 2) }}</div>
                        <div class="zyga-muted">Total registrado</div>
                        <div class="kpi-icon">
                            <i class="fas fa-wallet"></i>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-xl-3 mb-3">
                    <div class="zyga-kpi">
                        <div class="kpi-label">Estado</div>
                        <div class="kpi-value">{{ $status }}</div>
                        <div class="zyga-muted">Situación actual</div>
                        <div class="kpi-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-xl-3 mb-3">
                    <div class="zyga-kpi">
                        <div class="kpi-label">Método</div>
                        <div class="kpi-value">{{ $payment['payment_method'] ?? '—' }}</div>
                        <div class="zyga-muted">Forma de pago</div>
                        <div class="kpi-icon">
                            <i class="fas fa-credit-card"></i>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-xl-3 mb-3">
                    <div class="zyga-kpi">
                        <div class="kpi-label">Transacciones</div>
                        <div class="kpi-value">{{ count($payment['transactions'] ?? []) }}</div>
                        <div class="zyga-muted">Registros vinculados</div>
                        <div class="kpi-icon">
                            <i class="fas fa-exchange-alt"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="zyga-section-title">Resumen general</h3>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>ID del pago</label>
                            <div class="zyga-code">{{ $payment['id'] ?? '—' }}</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Solicitud asociada</label>
                            <div class="zyga-code">
                                {{ $payment['assistance_request']['public_id'] ?? $payment['assistance_request_id'] ?? '—' }}
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Monto</label>
                            <div>${{ number_format((float) ($payment['amount'] ?? 0), 2) }}</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Método de pago</label>
                            <div>{{ $payment['payment_method'] ?? '—' }}</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Estado</label>
                            <div>
                                <span class="badge badge-pill {{ $statusClass }}">
                                    {{ $status }}
                                </span>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Transaction ID</label>
                            <div class="zyga-code">{{ $payment['transaction_id'] ?? '—' }}</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Creado</label>
                            <div>{{ $payment['created_at'] ?? '—' }}</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Última actualización</label>
                            <div>{{ $payment['updated_at'] ?? '—' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="zyga-section-title">Transacciones ligadas</h3>
                </div>

                <div class="card-body">
                    @if(empty($payment['transactions']))
                        <div class="zyga-empty">
                            Este pago no tiene transacciones registradas.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Gateway</th>
                                        <th>Evento</th>
                                        <th>Referencia</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($payment['transactions'] as $transaction)
                                        <tr>
                                            <td>{{ $transaction['id'] ?? '—' }}</td>
                                            <td>{{ $transaction['gateway'] ?? '—' }}</td>
                                            <td class="zyga-code">{{ $transaction['gateway_event_id'] ?? '—' }}</td>
                                            <td class="zyga-code">{{ $transaction['gateway_reference'] ?? '—' }}</td>
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
