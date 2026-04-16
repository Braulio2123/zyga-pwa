@extends('adminlte::page')

@section('title', 'Caso de asistencia')

@section('content_header')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
        <div>
            <h1 class="m-0">Caso {{ $requestData['public_id'] ?? ('#' . ($requestData['id'] ?? '—')) }}</h1>
            <p class="zyga-muted mb-0">Revisión detallada del caso para seguimiento, reasignación, monitoreo GPS o cierre administrativo.</p>
        </div>
        <div class="mt-3 mt-md-0">
            <a href="{{ route('admin.assistance.index') }}" class="btn btn-light">
                <i class="fas fa-arrow-left mr-1"></i>
                Volver a operación
            </a>
        </div>
    </div>
@stop

@section('css')
<link
    rel="stylesheet"
    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
/>
<style>
    .admin-tracking-status {
        margin: 0 0 16px;
        padding: 14px 16px;
        border-radius: 16px;
        border: 1px solid rgba(15, 23, 42, 0.08);
        background: rgba(37, 99, 235, 0.08);
        color: #1d4ed8;
        font-weight: 600;
        line-height: 1.5;
    }

    .admin-tracking-status--success {
        background: rgba(22, 163, 74, 0.08);
        color: #15803d;
    }

    .admin-tracking-status--warning {
        background: rgba(245, 158, 11, 0.12);
        color: #b45309;
    }

    .admin-tracking-status--danger {
        background: rgba(239, 68, 68, 0.10);
        color: #b91c1c;
    }

    .admin-tracking-map-shell {
        position: relative;
        border-radius: 18px;
        overflow: hidden;
        border: 1px solid rgba(15, 23, 42, 0.08);
        background: #e2e8f0;
    }

    .admin-tracking-map {
        width: 100%;
        min-height: 420px;
    }

    .admin-tracking-map-overlay {
        position: absolute;
        inset: auto 16px 16px 16px;
        z-index: 500;
        background: rgba(15, 23, 42, 0.78);
        color: #fff;
        padding: 12px 14px;
        border-radius: 16px;
        font-size: 0.92rem;
        line-height: 1.5;
        backdrop-filter: blur(8px);
    }

    .admin-tracking-meta-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 14px;
    }

    .admin-tracking-meta-card {
        padding: 16px;
        border-radius: 16px;
        background: #f8fafc;
        border: 1px solid rgba(148, 163, 184, 0.18);
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .admin-tracking-meta-card span {
        font-size: .82rem;
        color: #64748b;
    }

    .admin-tracking-meta-card strong {
        font-size: .98rem;
        color: #0f172a;
        line-height: 1.5;
        word-break: break-word;
    }

    .admin-tracking-marker-wrapper {
        background: transparent;
        border: 0;
    }

    .admin-tracking-marker {
        display: block;
        width: 20px;
        height: 20px;
        border-radius: 999px;
        border: 3px solid #fff;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.24);
    }

    .admin-tracking-marker--client {
        background: #2563eb;
    }

    .admin-tracking-marker--provider {
        background: #f97316;
    }
</style>
@stop

@section('content')
    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Corrige los siguientes errores:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    @php
        $status = $requestData['status'] ?? 'sin_estado';
        $statusLabel = ucfirst(str_replace('_', ' ', $status));
        $statusClass = 'badge-soft-dark';

        if ($status === 'completed') $statusClass = 'badge-soft-success';
        elseif ($status === 'cancelled') $statusClass = 'badge-soft-danger';
        elseif (in_array($status, ['created', 'assigned', 'in_progress'], true)) $statusClass = 'badge-soft-warning';

        $provider = $requestData['provider'] ?? [];
        $providerUser = $provider['user'] ?? [];
        $tracking = $requestData['latest_provider_location'] ?? null;

        $clientLat = isset($requestData['lat']) ? (float) $requestData['lat'] : null;
        $clientLng = isset($requestData['lng']) ? (float) $requestData['lng'] : null;
        $providerLat = isset($tracking['lat']) ? (float) $tracking['lat'] : null;
        $providerLng = isset($tracking['lng']) ? (float) $tracking['lng'] : null;

        $hasClientCoordinates = $clientLat !== null && $clientLng !== null;
        $hasProviderCoordinates = $providerLat !== null && $providerLng !== null;

        $trackingTime = $tracking['recorded_at'] ?? ($tracking['created_at'] ?? null);
    @endphp

    <div class="row">
        <div class="col-xl-4 mb-4">
            <div class="card h-100">
                <div class="card-header"><h3 class="zyga-section-title">Acción administrativa</h3></div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.assistance.update', $requestData['id']) }}">
                        @csrf
                        @method('PATCH')

                        <div class="form-group">
                            <label for="status">Estado</label>
                            <select name="status" id="status" class="form-control" required>
                                @foreach(['created' => 'En cola', 'assigned' => 'Asignada', 'in_progress' => 'En curso', 'completed' => 'Completada', 'cancelled' => 'Cancelada'] as $key => $label)
                                    <option value="{{ $key }}" @selected(old('status', $requestData['status'] ?? '') === $key)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="provider_id">Provider ID</label>
                            <input type="number" name="provider_id" id="provider_id" class="form-control" value="{{ old('provider_id', $requestData['provider_id'] ?? '') }}" placeholder="Opcional, solo si vas a reasignar manualmente">
                        </div>

                        <div class="form-group mb-4">
                            <label for="cancel_reason">Motivo de cancelación</label>
                            <textarea name="cancel_reason" id="cancel_reason" rows="4" class="form-control" placeholder="Usa este campo solo si el caso se cancelará o ya fue cancelado.">{{ old('cancel_reason', $requestData['cancel_reason'] ?? '') }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-save mr-1"></i>
                            Guardar decisión
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-xl-8 mb-4">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="zyga-section-title mb-0">Resumen del caso</h3>
                    <span class="badge badge-pill {{ $statusClass }}">{{ $statusLabel }}</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3"><label>Cliente</label><div>{{ $requestData['user']['email'] ?? '—' }}</div></div>
                        <div class="col-md-6 mb-3"><label>Servicio</label><div>{{ $requestData['service']['name'] ?? 'Sin servicio' }}</div></div>
                        <div class="col-md-6 mb-3"><label>Proveedor</label><div>{{ $provider['display_name'] ?? 'Sin asignar' }}</div></div>
                        <div class="col-md-6 mb-3"><label>Correo del proveedor</label><div>{{ $providerUser['email'] ?? 'Sin correo visible' }}</div></div>
                        <div class="col-md-6 mb-3"><label>Vehículo</label><div>{{ $requestData['vehicle']['plate'] ?? ($requestData['vehicle']['id'] ?? '—') }}</div></div>
                        <div class="col-md-6 mb-3"><label>Creación</label><div>{{ $requestData['created_at'] ?? '—' }}</div></div>
                        <div class="col-md-6 mb-3"><label>Última actualización</label><div>{{ $requestData['updated_at'] ?? '—' }}</div></div>
                        <div class="col-md-6 mb-3"><label>Último tracking</label><div>{{ $trackingTime ?? 'Sin registro' }}</div></div>
                        <div class="col-12 mb-3"><label>Dirección base</label><div>{{ $requestData['pickup_address'] ?? '—' }}</div></div>
                        <div class="col-12 mb-3"><label>Referencia manual</label><div>{{ $requestData['pickup_reference'] ?? 'Sin referencia manual registrada' }}</div></div>
                        @if(!empty($requestData['cancel_reason']))
                            <div class="col-12"><label>Motivo de cancelación</label><div>{{ $requestData['cancel_reason'] }}</div></div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header"><h3 class="zyga-section-title">Lectura operativa</h3></div>
                <div class="card-body">
                    <div class="zyga-stat-list">
                        <div class="zyga-stat-item">
                            <div>
                                <div class="font-weight-bold">Cliente asociado</div>
                                <div class="zyga-muted small">ID {{ $requestData['user_id'] ?? '—' }}</div>
                            </div>
                            <span class="badge badge-pill badge-soft-primary">{{ $requestData['user']['email'] ?? '—' }}</span>
                        </div>
                        <div class="zyga-stat-item">
                            <div>
                                <div class="font-weight-bold">Proveedor actual</div>
                                <div class="zyga-muted small">{{ $provider['display_name'] ?? 'Aún no se ha tomado el caso' }}</div>
                            </div>
                            <span class="badge badge-pill {{ !empty($requestData['provider_id']) ? 'badge-soft-success' : 'badge-soft-warning' }}">{{ $requestData['provider_id'] ?? 'Pendiente' }}</span>
                        </div>
                        <div class="zyga-stat-item">
                            <div>
                                <div class="font-weight-bold">Servicio solicitado</div>
                                <div class="zyga-muted small">{{ $requestData['service']['name'] ?? 'Sin información' }}</div>
                            </div>
                            <span class="badge badge-pill badge-soft-accent">{{ $requestData['service_id'] ?? '—' }}</span>
                        </div>
                        <div class="zyga-stat-item">
                            <div>
                                <div class="font-weight-bold">Tracking del proveedor</div>
                                <div class="zyga-muted small">
                                    {{ $hasProviderCoordinates ? 'Última ubicación disponible' : 'Sin ubicación recibida todavía' }}
                                </div>
                            </div>
                            <span class="badge badge-pill {{ $hasProviderCoordinates ? 'badge-soft-success' : 'badge-soft-warning' }}">
                                {{ $hasProviderCoordinates ? 'Activo' : 'Pendiente' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h3 class="zyga-section-title">Mapa operativo</h3></div>
                <div class="card-body">
                    @if(!$hasClientCoordinates)
                        <div class="alert alert-warning mb-0">
                            Esta solicitud no tiene coordenadas del cliente, por lo que no es posible mostrar el mapa operativo.
                        </div>
                    @else
                        <div id="adminTrackingStatus" class="admin-tracking-status {{ $hasProviderCoordinates ? 'admin-tracking-status--success' : 'admin-tracking-status--warning' }}">
                            {{ $hasProviderCoordinates ? 'El proveedor ya reportó ubicación. El punto naranja representa su última posición conocida.' : 'Todavía no existe una ubicación reciente del proveedor para este caso.' }}
                        </div>

                        <div class="admin-tracking-meta-grid mb-3">
                            <article class="admin-tracking-meta-card">
                                <span>Cliente</span>
                                <strong>{{ $requestData['user']['email'] ?? 'Sin correo visible' }}</strong>
                            </article>

                            <article class="admin-tracking-meta-card">
                                <span>Proveedor</span>
                                <strong>{{ $provider['display_name'] ?? 'Sin asignar' }}</strong>
                            </article>

                            <article class="admin-tracking-meta-card">
                                <span>Dirección base</span>
                                <strong>{{ $requestData['pickup_address'] ?? 'Sin dirección' }}</strong>
                            </article>

                            <article class="admin-tracking-meta-card">
                                <span>Referencia manual</span>
                                <strong>{{ $requestData['pickup_reference'] ?? 'Sin referencia manual registrada' }}</strong>
                            </article>

                            <article class="admin-tracking-meta-card">
                                <span>Coordenadas del cliente</span>
                                <strong>{{ $clientLat }}, {{ $clientLng }}</strong>
                            </article>

                            <article class="admin-tracking-meta-card">
                                <span>Coordenadas del proveedor</span>
                                <strong>
                                    {{ $hasProviderCoordinates ? ($providerLat . ', ' . $providerLng) : 'Sin registro todavía' }}
                                </strong>
                            </article>

                            <article class="admin-tracking-meta-card">
                                <span>Última hora de tracking</span>
                                <strong>{{ $trackingTime ?? 'Sin registro' }}</strong>
                            </article>

                            <article class="admin-tracking-meta-card">
                                <span>Precisión reportada</span>
                                <strong>{{ $tracking['accuracy'] ?? 'Sin dato' }}</strong>
                            </article>
                        </div>

                        <div class="admin-tracking-map-shell">
                            <div id="adminTrackingMap" class="admin-tracking-map"></div>
                            <div id="adminTrackingMapOverlay" class="admin-tracking-map-overlay">
                                Punto azul: cliente. Punto naranja: última ubicación conocida del proveedor.
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
(function () {
    const mapConfig = {
        clientLat: @json($clientLat),
        clientLng: @json($clientLng),
        providerLat: @json($providerLat),
        providerLng: @json($providerLng),
        pickupAddress: @json($requestData['pickup_address'] ?? ''),
        pickupReference: @json($requestData['pickup_reference'] ?? ''),
        providerName: @json($provider['display_name'] ?? 'Proveedor'),
        trackingTime: @json($trackingTime),
    };

    function boot() {
        if (!window.L) {
            return;
        }

        if (!isFiniteNumber(mapConfig.clientLat) || !isFiniteNumber(mapConfig.clientLng)) {
            return;
        }

        const mapElement = document.getElementById('adminTrackingMap');

        if (!mapElement) {
            return;
        }

        const map = L.map(mapElement).setView([mapConfig.clientLat, mapConfig.clientLng], 15);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        const clientMarker = L.marker([mapConfig.clientLat, mapConfig.clientLng], {
            icon: createMarkerIcon('client')
        }).addTo(map);

        clientMarker.bindPopup(
            '<strong>Cliente</strong><br>' +
            escapeHtml(mapConfig.pickupAddress || 'Sin dirección') +
            '<br><small>' + escapeHtml(mapConfig.pickupReference || 'Sin referencia manual') + '</small>'
        );

        if (isFiniteNumber(mapConfig.providerLat) && isFiniteNumber(mapConfig.providerLng)) {
            const providerMarker = L.marker([mapConfig.providerLat, mapConfig.providerLng], {
                icon: createMarkerIcon('provider')
            }).addTo(map);

            providerMarker.bindPopup(
                '<strong>' + escapeHtml(mapConfig.providerName || 'Proveedor') + '</strong><br>' +
                'Último tracking: ' + escapeHtml(mapConfig.trackingTime || 'Sin fecha')
            );

            const bounds = L.latLngBounds([
                [mapConfig.clientLat, mapConfig.clientLng],
                [mapConfig.providerLat, mapConfig.providerLng]
            ]);

            map.fitBounds(bounds, {
                padding: [40, 40],
                maxZoom: 16
            });
        }

        setTimeout(function () {
            map.invalidateSize();
        }, 250);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', boot, { once: true });
    } else {
        boot();
    }

    function createMarkerIcon(type) {
        return L.divIcon({
            className: 'admin-tracking-marker-wrapper',
            html: '<span class="admin-tracking-marker admin-tracking-marker--' + type + '"></span>',
            iconSize: [20, 20],
            iconAnchor: [10, 10],
            popupAnchor: [0, -10]
        });
    }

    function isFiniteNumber(value) {
        return typeof value === 'number' && Number.isFinite(value);
    }

    function escapeHtml(value) {
        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }
})();
</script>
@stop
