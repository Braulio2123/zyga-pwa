@extends('adminlte::page')

@section('title', 'Pagos y finanzas')

@section('content_header')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
        <div>
            <h1 class="m-0">Pagos y finanzas</h1>
            <p class="zyga-muted mb-0">
                Supervisión administrativa de pagos registrados y transacciones asociadas.
            </p>
        </div>

        <div class="mt-3 mt-md-0">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-light">
                <i class="fas fa-arrow-left mr-1"></i>
                Volver al dashboard
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

    @if(!empty($apiErrors))
        <div class="alert alert-warning">
            <strong>Se detectaron incidencias parciales al cargar el módulo financiero:</strong>
            <ul class="mb-0 mt-2">
                @foreach($apiErrors as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @php
        $totalPayments = count($payments);
        $totalTransactions = count($transactions);

        $pendingPayments = 0;
        $completedPayments = 0;
        $failedPayments = 0;
        $totalAmount = 0;

        foreach ($payments as $payment) {
            $status = $payment['status'] ?? null;
            $amount = (float) ($payment['amount'] ?? 0);

            $totalAmount += $amount;

            if ($status === 'pending') {
                $pendingPayments++;
            } elseif ($status === 'completed') {
                $completedPayments++;
            } elseif ($status === 'failed') {
                $failedPayments++;
            }
        }
    @endphp

    <div class="zyga-hero mb-4">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h2 class="mb-2">Centro de control financiero</h2>
                <p class="mb-0 text-white-50">
                    Consulta pagos por estado, método o transacción, y revisa el historial reciente
                    reportado por la API administrativa.
                </p>
            </div>
            <div class="col-lg-4 text-lg-right mt-3 mt-lg-0">
                <span class="badge badge-pill badge-soft-primary">
                    {{ $totalPayments }} pagos
                </span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-xl-3 mb-3">
            <div class="zyga-kpi">
                <div class="kpi-label">Pagos</div>
                <div class="kpi-value">{{ $totalPayments }}</div>
                <div class="zyga-muted">Registros de pago encontrados</div>
                <div class="kpi-icon">
                    <i class="fas fa-credit-card"></i>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3 mb-3">
            <div class="zyga-kpi">
                <div class="kpi-label">Completados</div>
                <div class="kpi-value">{{ $completedPayments }}</div>
                <div class="zyga-muted">Pagos con estado completed</div>
                <div class="kpi-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3 mb-3">
            <div class="zyga-kpi">
                <div class="kpi-label">Pendientes</div>
                <div class="kpi-value">{{ $pendingPayments }}</div>
                <div class="zyga-muted">Pagos aún en proceso</div>
                <div class="kpi-icon">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3 mb-3">
            <div class="zyga-kpi">
                <div class="kpi-label">Monto total</div>
                <div class="kpi-value">${{ number_format($totalAmount, 2) }}</div>
                <div class="zyga-muted">{{ $totalTransactions }} transacciones registradas</div>
                <div class="kpi-icon">
                    <i class="fas fa-wallet"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h3 class="zyga-section-title">Filtros</h3>
        </div>

        <div class="card-body">
            <form method="GET" action="{{ route('admin.finance.index') }}">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="status">Estado</label>
                        <select name="status" id="status" class="form-control">
                            <option value="">Todos los estados</option>
                            @foreach(['pending', 'completed', 'failed'] as $status)
                                <option value="{{ $status }}" @selected(($filters['status'] ?? '') === $status)>
                                    {{ $status }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="payment_method">Método de pago</label>
                        <input
                            type="text"
                            name="payment_method"
                            id="payment_method"
                            class="form-control"
                            placeholder="Ej. efectivo, tarjeta, transferencia"
                            value="{{ $filters['payment_method'] ?? '' }}"
                        >
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="transaction_id">Transaction ID</label>
                        <input
                            type="text"
                            name="transaction_id"
                            id="transaction_id"
                            class="form-control"
                            placeholder="ID de transacción"
                            value="{{ $filters['transaction_id'] ?? '' }}"
                        >
                    </div>
                </div>

                <div class="d-flex flex-column flex-md-row">
                    <button type="submit" class="btn btn-primary mr-md-2 mb-2 mb-md-0">
                        <i class="fas fa-filter mr-1"></i>
                        Aplicar filtros
                    </button>

                    <a href="{{ route('admin.finance.index') }}" class="btn btn-light">
                        <i class="fas fa-eraser mr-1"></i>
                        Limpiar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-7 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="zyga-section-title">Pagos</h3>
                    <span class="badge badge-pill badge-soft-dark">
                        {{ $totalPayments }} registros
                    </span>
                </div>

                <div class="card-body p-0">
                    @if(empty($payments))
                        <div class="zyga-empty">
                            No hay pagos registrados para los filtros actuales.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Monto</th>
                                        <th>Método</th>
                                        <th>Estado</th>
                                        <th>Solicitud</th>
                                        <th>Transacción</th>
                                        <th class="text-right">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($payments as $payment)
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

                                        <tr>
                                            <td>{{ $payment['id'] ?? '—' }}</td>

                                            <td class="font-weight-bold">
                                                ${{ number_format((float) ($payment['amount'] ?? 0), 2) }}
                                            </td>

                                            <td>{{ $payment['payment_method'] ?? '—' }}</td>

                                            <td>
                                                <span class="badge badge-pill {{ $statusClass }}">
                                                    {{ $status }}
                                                </span>
                                            </td>

                                            <td class="zyga-code">
                                                {{ $payment['assistance_request']['public_id'] ?? $payment['assistance_request_id'] ?? '—' }}
                                            </td>

                                            <td class="zyga-code">
                                                {{ $payment['transaction_id'] ?? '—' }}
                                            </td>

                                            <td class="text-right">
                                                <a href="{{ route('admin.finance.show-payment', $payment['id']) }}"
                                                   class="btn btn-sm btn-outline-primary">
                                                    Ver
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
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="zyga-section-title">Transacciones recientes</h3>
                    <span class="badge badge-pill badge-soft-dark">
                        {{ $totalTransactions }} registros
                    </span>
                </div>

                <div class="card-body p-0">
                    @if(empty($transactions))
                        <div class="zyga-empty">
                            No hay transacciones para mostrar.
                        </div>
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
                                            <td class="zyga-code">
                                                {{ $transaction['gateway_event_id'] ?? '—' }}
                                            </td>
                                            <td>
                                                {{ $transaction['payment_id'] ?? $transaction['payment']['id'] ?? '—' }}
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
    </div>
@stop
