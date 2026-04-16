@extends('adminlte::page')

@section('title', 'Ficha de pago')

@section('content_header')
    <div>
        <h1 class="m-0">Ficha de pago #{{ $payment['id'] ?? '—' }}</h1>
        <p class="zyga-muted mb-0">
            Revisión administrativa del pago, su referencia, trazabilidad y efecto sobre la solicitud asociada.
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

        $requestData = $payment['assistanceRequest'] ?? $payment['assistance_request'] ?? [];
        $serviceName = data_get($requestData, 'service.name', 'Servicio no identificado');
        $vehicleData = data_get($requestData, 'vehicle', []);
        $vehicleLabel = trim(
            trim((string) data_get($vehicleData, 'brand', '')) . ' ' .
            trim((string) data_get($vehicleData, 'model', ''))
        );
        $vehiclePlate = trim((string) data_get($vehicleData, 'plate', ''));
        $validatorName = data_get($payment, 'validator.name') ?: data_get($payment, 'validator.email');
    @endphp

    <div class="row">
        <div class="col-lg-5 mb-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="zyga-section-title">Validación del pago</h3>
                </div>

                <div class="card-body">
                    <div class="alert alert-light border mb-4">
                        <div class="mb-2">
                            <strong>Estatus actual:</strong>
                            <span class="badge badge-pill {{ $statusClass($payment['status'] ?? '') }}">
                                {{ $statusLabel($payment['status'] ?? '') }}
                            </span>
                        </div>

                        <div class="mb-2">
                            <strong>Monto protegido:</strong>
                            ${{ number_format((float) ($payment['amount'] ?? 0), 2) }}
                        </div>

                        <div class="mb-0">
                            <strong>Solicitud:</strong>
                            {{ data_get($requestData, 'public_id') ?: (($requestData['id'] ?? null) ? '#' . $requestData['id'] : '—') }}
                        </div>
                    </div>

                    <form method="POST" action="{{ route('admin.finance.update-payment', $payment['id']) }}">
                        @csrf
                        @method('PATCH')

                        <div class="form-group">
                            <label>Método de pago</label>
                            <select name="payment_method" class="form-control" required>
                                @foreach([
                                    'cash' => 'Efectivo',
                                    'transfer' => 'Transferencia',
                                ] as $code => $label)
                                    <option value="{{ $code }}" @selected(old('payment_method', $payment['payment_method'] ?? '') === $code)>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Referencia</label>
                            <input
                                type="text"
                                name="reference"
                                class="form-control"
                                value="{{ old('reference', $payment['reference'] ?? '') }}"
                                placeholder="Referencia de transferencia o nota corta"
                            >
                        </div>

                        <div class="form-group">
                            <label>Transaction ID</label>
                            <input
                                type="text"
                                name="transaction_id"
                                class="form-control"
                                value="{{ old('transaction_id', $payment['transaction_id'] ?? '') }}"
                                placeholder="ID interno del registro"
                            >
                        </div>

                        <div class="form-group">
                            <label>Notas</label>
                            <textarea
                                name="notes"
                                rows="4"
                                class="form-control"
                                placeholder="Observaciones administrativas o contexto de validación"
                            >{{ old('notes', $payment['notes'] ?? '') }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>Estado</label>
                            <select name="status" class="form-control" required>
                                @foreach([
                                    'pending' => 'Pendiente',
                                    'pending_validation' => 'Pendiente de validación',
                                    'completed' => 'Completado',
                                    'failed' => 'Fallido',
                                    'rejected' => 'Rechazado',
                                ] as $status => $label)
                                    <option value="{{ $status }}" @selected(old('status', $payment['status'] ?? '') === $status)>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <button class="btn btn-primary btn-block">
                            Guardar cambios
                        </button>
                    </form>

                    <div class="alert alert-warning mt-4 mb-0">
                        El monto ya no se edita desde esta pantalla. Para este MVP, administración solo valida,
                        corrige el estatus y documenta la referencia o notas del pago.
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-7 mb-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="zyga-section-title">Contexto financiero</h3>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong>Solicitud asociada:</strong><br>
                            {{ data_get($requestData, 'public_id') ?: (($requestData['id'] ?? null) ? '#' . $requestData['id'] : '—') }}
                        </div>

                        <div class="col-md-6 mb-3">
                            <strong>Estatus del pago:</strong><br>
                            {{ $statusLabel($payment['status'] ?? '') }}
                        </div>

                        <div class="col-md-6 mb-3">
                            <strong>Monto:</strong><br>
                            ${{ number_format((float) ($payment['amount'] ?? 0), 2) }}
                        </div>

                        <div class="col-md-6 mb-3">
                            <strong>Método:</strong><br>
                            {{ strtoupper($payment['payment_method'] ?? '—') }}
                        </div>

                        <div class="col-md-6 mb-3">
                            <strong>Referencia:</strong><br>
                            {{ $payment['reference'] ?? 'Sin referencia' }}
                        </div>

                        <div class="col-md-6 mb-3">
                            <strong>Transaction ID:</strong><br>
                            {{ $payment['transaction_id'] ?? '—' }}
                        </div>

                        <div class="col-md-6 mb-3">
                            <strong>Validado por:</strong><br>
                            {{ $validatorName ?: 'Aún no validado' }}
                        </div>

                        <div class="col-md-6 mb-3">
                            <strong>Validado en:</strong><br>
                            {{ !empty($payment['validated_at']) ? \Illuminate\Support\Carbon::parse($payment['validated_at'])->format('d/m/Y H:i') : 'Pendiente' }}
                        </div>
                    </div>

                    <hr>

                    <h6 class="font-weight-bold">Notas del pago</h6>
                    <p class="mb-0 text-muted">
                        {{ $payment['notes'] ?? 'Sin notas registradas.' }}
                    </p>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="zyga-section-title">Solicitud relacionada</h3>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong>Servicio:</strong><br>
                            {{ $serviceName }}
                        </div>

                        <div class="col-md-6 mb-3">
                            <strong>Vehículo:</strong><br>
                            {{ $vehicleLabel !== '' ? $vehicleLabel : 'Vehículo no identificado' }}
                            @if($vehiclePlate !== '')
                                · {{ $vehiclePlate }}
                            @endif
                        </div>

                        <div class="col-md-6 mb-3">
                            <strong>Monto cotizado:</strong><br>
                            ${{ number_format((float) (data_get($requestData, 'quoted_amount', 0)), 2) }}
                        </div>

                        <div class="col-md-6 mb-3">
                            <strong>Monto final:</strong><br>
                            ${{ number_format((float) (data_get($requestData, 'final_amount', 0)), 2) }}
                        </div>

                        <div class="col-md-6 mb-3">
                            <strong>Estatus financiero de la solicitud:</strong><br>
                            {{ $statusLabel(data_get($requestData, 'payment_status', 'pending')) }}
                        </div>

                        <div class="col-md-6 mb-3">
                            <strong>Método registrado en la solicitud:</strong><br>
                            {{ strtoupper((string) data_get($requestData, 'payment_method', '—')) }}
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
                        <div class="zyga-muted">Este pago no tiene transacciones registradas.</div>
                    @else
                        <ul class="mb-0 pl-3">
                            @foreach($payment['transactions'] as $transaction)
                                <li>
                                    {{ $transaction['gateway'] ?? 'gateway' }}
                                    ·
                                    {{ $transaction['gateway_event_id'] ?? 'sin evento' }}
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop
