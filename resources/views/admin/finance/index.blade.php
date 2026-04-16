@extends('adminlte::page')

@section('title', 'Pagos y finanzas')

@section('content_header')
    <div>
        <h1 class="m-0">Pagos y finanzas</h1>
        <p class="zyga-muted mb-0">
            Supervisa pagos registrados, valida transferencias pendientes y revisa la trazabilidad financiera del MVP.
        </p>
    </div>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if(!empty($apiErrors))
        <div class="alert alert-warning">
            <strong>Se detectaron incidencias al consultar la API:</strong>
            <ul class="mb-0 mt-2 pl-3">
                @foreach($apiErrors as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @php
        $normalize = fn ($value) => strtolower(trim((string) $value));

        $statusLabel = function ($status) use ($normalize) {
            return match ($normalize($status)) {
                'pending' => 'Pendiente',
                'pending_validation' => 'Pendiente de validación',
                'completed' => 'Completado',
                'paid' => 'Pagado',
                'failed' => 'Fallido',
                'rejected' => 'Rechazado',
                default => $status ?: 'Sin estado',
            };
        };

        $statusClass = function ($status) use ($normalize) {
            return match ($normalize($status)) {
                'completed', 'paid' => 'badge-soft-success',
                'failed', 'rejected' => 'badge-soft-danger',
                'pending_validation' => 'badge-soft-primary',
                'pending' => 'badge-soft-warning',
                default => 'badge-soft-secondary',
            };
        };

        $completedPayments = array_values(array_filter($payments, fn ($payment) => $normalize($payment['status'] ?? '') === 'completed'));
        $pendingValidationPayments = array_values(array_filter($payments, fn ($payment) => $normalize($payment['status'] ?? '') === 'pending_validation'));
        $pendingPayments = array_values(array_filter($payments, fn ($payment) => in_array($normalize($payment['status'] ?? ''), ['pending', 'pending_validation'], true)));
        $failedPayments = array_values(array_filter($payments, fn ($payment) => in_array($normalize($payment['status'] ?? ''), ['failed', 'rejected'], true)));
        $completedRevenue = array_sum(array_map(fn ($payment) => (float) ($payment['amount'] ?? 0), $completedPayments));
    @endphp

    <div class="row mb-4">
        <div class="col-md-6 col-xl-3 mb-3">
            <div class="zyga-kpi">
                <div class="kpi-label">Pagos totales</div>
                <div class="kpi-value">{{ count($payments) }}</div>
                <div class="zyga-muted">Registros financieros</div>
                <div class="kpi-icon"><i class="fas fa-receipt"></i></div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3 mb-3">
            <div class="zyga-kpi">
                <div class="kpi-label">Pendientes de validación</div>
                <div class="kpi-value">{{ count($pendingValidationPayments) }}</div>
                <div class="zyga-muted">Transferencias por revisar</div>
                <div class="kpi-icon"><i class="fas fa-user-check"></i></div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3 mb-3">
            <div class="zyga-kpi">
                <div class="kpi-label">Completados</div>
                <div class="kpi-value">{{ count($completedPayments) }}</div>
                <div class="zyga-muted">Cobros confirmados</div>
                <div class="kpi-icon"><i class="fas fa-check-circle"></i></div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3 mb-3">
            <div class="zyga-kpi">
                <div class="kpi-label">Ingresos</div>
                <div class="kpi-value">${{ number_format($completedRevenue, 2) }}</div>
                <div class="zyga-muted">Solo pagos completados</div>
                <div class="kpi-icon"><i class="fas fa-wallet"></i></div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.finance.index') }}" class="zyga-toolbar">
                <select name="status" class="form-control">
                    <option value="">Todos los estados</option>
                    @foreach([
                        'pending' => 'Pendiente',
                        'pending_validation' => 'Pendiente de validación',
                        'completed' => 'Completado',
                        'failed' => 'Fallido',
                        'rejected' => 'Rechazado',
                    ] as $status => $label)
                        <option value="{{ $status }}" @selected(($filters['status'] ?? '') === $status)>{{ $label }}</option>
                    @endforeach
                </select>

                <input
                    type="text"
                    name="payment_method"
                    class="form-control"
                    placeholder="Método de pago"
                    value="{{ $filters['payment_method'] ?? '' }}"
                >

                <input
                    type="text"
                    name="transaction_id"
                    class="form-control"
                    placeholder="Transaction ID"
                    value="{{ $filters['transaction_id'] ?? '' }}"
                >

                <button class="btn btn-primary">Filtrar</button>
                <a href="{{ route('admin.finance.index') }}" class="btn btn-light">Limpiar</a>
                <a href="{{ route('admin.exportaciones.payments.excel', request()->query()) }}" class="btn btn-success">
                    <i class="fas fa-file-excel mr-1"></i>Exportar Excel
                </a>
                <a href="{{ route('admin.exportaciones.payments.pdf', request()->query()) }}" class="btn btn-danger">
                    <i class="fas fa-file-pdf mr-1"></i>Exportar PDF
                </a>
            </form>
        </div>
    </div>

    @if(!empty($pendingValidationPayments))
        <div class="card border-warning mb-4">
            <div class="card-header bg-warning">
                <h3 class="card-title mb-0 text-white">Transferencias pendientes de validación</h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Pago</th>
                                <th>Solicitud</th>
                                <th>Monto</th>
                                <th>Referencia</th>
                                <th>Método</th>
                                <th>Estatus</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingValidationPayments as $payment)
                                <tr>
                                    <td>#{{ $payment['id'] ?? '—' }}</td>
                                    <td>{{ data_get($payment, 'assistanceRequest.public_id') ?? data_get($payment, 'assistance_request.public_id') ?? $payment['assistance_request_id'] ?? '—' }}</td>
                                    <td>${{ number_format((float) ($payment['amount'] ?? 0), 2) }}</td>
                                    <td>{{ $payment['reference'] ?? 'Sin referencia' }}</td>
                                    <td>{{ strtoupper($payment['payment_method'] ?? '—') }}</td>
                                    <td>
                                        <span class="badge badge-pill {{ $statusClass($payment['status'] ?? '') }}">
                                            {{ $statusLabel($payment['status'] ?? '') }}
                                        </span>
                                    </td>
                                    <td class="text-right">
                                        <a href="{{ route('admin.finance.show-payment', $payment['id']) }}" class="btn btn-sm btn-outline-warning">
                                            Revisar
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-7 mb-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="zyga-section-title">Pagos</h3>
                </div>

                <div class="card-body p-0">
                    @if(empty($payments))
                        <div class="zyga-empty">No hay pagos registrados.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Solicitud</th>
                                        <th>Monto</th>
                                        <th>Método</th>
                                        <th>Referencia</th>
                                        <th>Estado</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($payments as $payment)
                                        <tr>
                                            <td>#{{ $payment['id'] ?? '—' }}</td>
                                            <td>{{ data_get($payment, 'assistanceRequest.public_id') ?? data_get($payment, 'assistance_request.public_id') ?? $payment['assistance_request_id'] ?? '—' }}</td>
                                            <td>${{ number_format((float) ($payment['amount'] ?? 0), 2) }}</td>
                                            <td>{{ strtoupper($payment['payment_method'] ?? '—') }}</td>
                                            <td>{{ $payment['reference'] ?? '—' }}</td>
                                            <td>
                                                <span class="badge badge-pill {{ $statusClass($payment['status'] ?? '') }}">
                                                    {{ $statusLabel($payment['status'] ?? '') }}
                                                </span>
                                            </td>
                                            <td class="text-right">
                                                <a href="{{ route('admin.finance.show-payment', $payment['id']) }}" class="btn btn-sm btn-outline-primary">
                                                    Abrir
                                                </a>
                                            </td>
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
                <div class="card-header">
                    <h3 class="zyga-section-title">Transacciones recientes</h3>
                </div>

                <div class="card-body p-0">
                    @if(empty($transactions))
                        <div class="zyga-empty">No hay transacciones para mostrar.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Gateway</th>
                                        <th>Evento</th>
                                        <th>Pago</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(array_slice($transactions, 0, 10) as $transaction)
                                        <tr>
                                            <td>{{ $transaction['id'] ?? '—' }}</td>
                                            <td>{{ $transaction['gateway'] ?? '—' }}</td>
                                            <td>{{ $transaction['gateway_event_id'] ?? '—' }}</td>
                                            <td>{{ $transaction['payment_id'] ?? data_get($transaction, 'payment.id') ?? '—' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="zyga-section-title">Resumen rápido</h3>
                </div>

                <div class="card-body">
                    <p class="mb-2"><strong>Pendientes:</strong> {{ count($pendingPayments) }}</p>
                    <p class="mb-2"><strong>Fallidos / rechazados:</strong> {{ count($failedPayments) }}</p>
                    <p class="mb-0">
                        <strong>Flujo actual:</strong> las transferencias se registran primero como
                        <code>pending_validation</code> y después administración las confirma o rechaza.
                    </p>
                </div>
            </div>
        </div>
    </div>
@stop
