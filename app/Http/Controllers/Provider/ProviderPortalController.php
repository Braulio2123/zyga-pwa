<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class ProviderPortalController extends Controller
{
    protected string $apiBase;

    public function __construct()
    {
        $this->apiBase = rtrim((string) env('URL_BASE_API', 'http://127.0.0.1:8000'), '/');
    }

    protected function token(): ?string
    {
        return session('api_token');
    }

    protected function apiClient()
    {
        return Http::withToken($this->token())
            ->acceptJson()
            ->timeout(20);
    }

    protected function apiGet(string $endpoint): array
    {
        try {
            return $this->formatResponse($this->apiClient()->get($this->apiBase . $endpoint));
        } catch (\Throwable $e) {
            return [
                'ok' => false,
                'status' => 0,
                'message' => 'No fue posible conectar con la API.',
                'details' => $e->getMessage(),
                'errors' => [],
                'data' => [],
                'raw' => [],
            ];
        }
    }

    protected function apiSend(string $method, string $endpoint, array $payload = []): array
    {
        try {
            $client = $this->apiClient();

            $response = match (strtolower($method)) {
                'post' => $client->post($this->apiBase . $endpoint, $payload),
                'put' => $client->put($this->apiBase . $endpoint, $payload),
                'patch' => $client->patch($this->apiBase . $endpoint, $payload),
                'delete' => $client->delete($this->apiBase . $endpoint, $payload),
                default => throw new \InvalidArgumentException('Método HTTP no soportado'),
            };

            return $this->formatResponse($response);
        } catch (\Throwable $e) {
            return [
                'ok' => false,
                'status' => 0,
                'message' => 'No fue posible conectar con la API.',
                'details' => $e->getMessage(),
                'errors' => [],
                'data' => [],
                'raw' => [],
            ];
        }
    }

    protected function formatResponse($response): array
    {
        $json = $response->json();

        return [
            'ok' => $response->successful(),
            'status' => $response->status(),
            'message' => is_array($json) ? ($json['message'] ?? ($response->successful() ? 'Operación realizada correctamente.' : 'La API respondió con error.')) : ($response->successful() ? 'Operación realizada correctamente.' : 'La API respondió con error.'),
            'errors' => is_array($json) ? ($json['errors'] ?? []) : [],
            'data' => is_array($json) ? ($json['data'] ?? []) : [],
            'raw' => is_array($json) ? $json : [],
        ];
    }

    protected function normalizeList(mixed $data, array $preferredKeys = []): array
    {
        if (is_array($data) && array_is_list($data)) {
            return $data;
        }

        if (is_array($data)) {
            foreach ($preferredKeys as $key) {
                if (isset($data[$key]) && is_array($data[$key])) {
                    return $data[$key];
                }
            }

            foreach (['items', 'services', 'schedules', 'documents', 'reviews', 'requests'] as $key) {
                if (isset($data[$key]) && is_array($data[$key])) {
                    return $data[$key];
                }
            }
        }

        return [];
    }

    protected function providerContext(): array
    {
        $profileResponse = $this->apiGet('/api/v1/provider/profile');
        $hasProfile = $profileResponse['ok'];
        $profile = $hasProfile && is_array($profileResponse['data']) ? $profileResponse['data'] : [];

        $servicesResponse = ['ok' => false, 'data' => []];
        $schedulesResponse = ['ok' => false, 'data' => []];
        $documentsResponse = ['ok' => false, 'data' => []];

        $services = [];
        $schedules = [];
        $documents = [];

        if ($hasProfile) {
            $servicesResponse = $this->apiGet('/api/v1/provider/services');
            $schedulesResponse = $this->apiGet('/api/v1/provider/schedules');
            $documentsResponse = $this->apiGet('/api/v1/provider/documents');

            $services = $this->normalizeList($servicesResponse['data'] ?? [], ['services']);
            $schedules = $this->normalizeList($schedulesResponse['data'] ?? [], ['schedules']);
            $documents = $this->normalizeList($documentsResponse['data'] ?? [], ['documents']);
        }

        return [
            'profileResponse' => $profileResponse,
            'hasProfile' => $hasProfile,
            'profile' => $profile,
            'servicesResponse' => $servicesResponse,
            'services' => $services,
            'schedulesResponse' => $schedulesResponse,
            'schedules' => $schedules,
            'documentsResponse' => $documentsResponse,
            'documents' => $documents,
            'readiness' => $this->providerReadiness($profile, $services, $schedules, $documents, $hasProfile),
        ];
    }

    protected function publicServicesCatalog(): array
    {
        try {
            return $this->formatResponse(
                Http::acceptJson()->timeout(20)->get($this->apiBase . '/api/v1/services')
            );
        } catch (\Throwable $e) {
            return [
                'ok' => false,
                'status' => 0,
                'message' => 'No fue posible cargar el catálogo general de servicios.',
                'details' => $e->getMessage(),
                'errors' => [],
                'data' => [],
                'raw' => [],
            ];
        }
    }

    protected function providerReadiness(array $profile, array $services, array $schedules, array $documents, bool $hasProfile): array
    {
        $statusCode = strtolower((string) data_get($profile, 'status.code', ''));
        $statusName = data_get($profile, 'status.name');
        $isVerified = (bool) ($profile['is_verified'] ?? false);
        $activeServicesCount = count($services);
        $activeSchedulesCount = collect($schedules)->filter(fn ($schedule) => (bool) ($schedule['is_active'] ?? true))->count();
        $documentsCount = count($documents);

        $checks = [
            'has_profile' => $hasProfile,
            'has_services' => $activeServicesCount > 0,
            'has_active_schedule' => $activeSchedulesCount > 0,
            'is_verified' => $isVerified,
            'status_active' => $statusCode === 'active',
            'documents_registered' => $documentsCount > 0,
        ];

        $blockers = [];

        if (!$checks['has_profile']) {
            $blockers[] = 'Completa primero tu perfil de proveedor.';
        }
        if ($checks['has_profile'] && !$checks['has_services']) {
            $blockers[] = 'Debes asignar al menos un servicio para poder recibir solicitudes compatibles.';
        }
        if ($checks['has_profile'] && !$checks['has_active_schedule']) {
            $blockers[] = 'Debes registrar al menos un horario activo para marcarte como listo en el portal provider.';
        }
        if ($checks['has_profile'] && !$checks['is_verified']) {
            $blockers[] = 'Administración todavía no valida tu proveedor. El backend no te permitirá operar hasta quedar verificado.';
        }
        if ($checks['has_profile'] && !$checks['status_active']) {
            $blockers[] = 'Tu proveedor no está en estatus operativo activo dentro del backend.';
        }

        $documentsNote = $documentsCount > 0
            ? 'Tienes documentos registrados en tu expediente.'
            : 'El backend actual sí permite registrar documentos, pero no los usa todavía como bloqueo operativo automático.';

        return [
            'checks' => $checks,
            'status_code' => $statusCode,
            'status_name' => $statusName ?: ($statusCode !== '' ? $this->statusLabel($statusCode) : 'Sin estado'),
            'verification_text' => $isVerified ? 'Verificado por administración' : 'Pendiente de validación',
            'services_count' => $activeServicesCount,
            'active_schedules_count' => $activeSchedulesCount,
            'documents_count' => $documentsCount,
            'documents_note' => $documentsNote,
            'status_tone' => $this->statusTone($statusCode),
            'backend_can_operate' => $hasProfile && $checks['has_services'] && $checks['is_verified'] && $checks['status_active'],
            'portal_ready' => $hasProfile && $checks['has_services'] && $checks['has_active_schedule'] && $checks['is_verified'] && $checks['status_active'],
            'blockers' => $blockers,
        ];
    }

    protected function allowedStatusOptions(?string $currentStatus): array
    {
        return match ($currentStatus) {
            'assigned' => [
                'in_progress' => 'Marcar en proceso',
                'cancelled' => 'Cancelar servicio',
            ],
            'in_progress' => [
                'completed' => 'Marcar completado',
                'cancelled' => 'Cancelar servicio',
            ],
            default => [],
        };
    }


    protected function statusLabel(?string $status): string
    {
        return match ($status) {
            'created' => 'Nueva',
            'assigned' => 'Asignada',
            'in_progress' => 'En proceso',
            'completed' => 'Completada',
            'cancelled' => 'Cancelada',
            'active' => 'Activo',
            'inactive' => 'Inactivo',
            default => $status ? ucfirst(str_replace('_', ' ', $status)) : 'Sin estado',
        };
    }

    protected function statusTone(?string $status): string
    {
        return match ($status) {
            'created' => 'info',
            'assigned', 'in_progress', 'active' => 'success',
            'completed' => 'dark',
            'cancelled', 'inactive' => 'warning',
            default => 'info',
        };
    }

    protected function dayOptions(): array
    {
        return [
            1 => 'Lunes',
            2 => 'Martes',
            3 => 'Miércoles',
            4 => 'Jueves',
            5 => 'Viernes',
            6 => 'Sábado',
            7 => 'Domingo',
        ];
    }

    protected function activeRequests(array $requests): array
    {
        return array_values(array_filter($requests, function (array $request) {
            return in_array($request['status'] ?? null, ['assigned', 'in_progress'], true);
        }));
    }

    protected function historicalRequests(array $requests): array
    {
        return array_values(array_filter($requests, function (array $request) {
            return in_array($request['status'] ?? null, ['completed', 'cancelled'], true);
        }));
    }

    protected function normalizeRequest(array $request): array
    {
        return [
            'id' => $request['id'] ?? null,
            'public_id' => $request['public_id'] ?? null,
            'status' => $request['status'] ?? null,
            'status_label' => $this->statusLabel($request['status'] ?? null),
            'status_tone' => $this->statusTone($request['status'] ?? null),
            'pickup_address' => $request['pickup_address'] ?? $request['address'] ?? 'Sin dirección de referencia',
            'lat' => $request['lat'] ?? null,
            'lng' => $request['lng'] ?? null,
            'service_name' => data_get($request, 'service.name') ?? ($request['service_name'] ?? 'Servicio'),
            'service_code' => data_get($request, 'service.code'),
            'client_email' => data_get($request, 'user.email'),
            'vehicle' => trim(implode(' ', array_filter([
                data_get($request, 'vehicle.brand'),
                data_get($request, 'vehicle.model'),
            ]))) ?: 'Vehículo no especificado',
            'created_at' => $request['created_at'] ?? null,
            'updated_at' => $request['updated_at'] ?? null,
            'raw' => $request,
        ];
    }

    protected function routeBackToAssistance(string $messageKey, string $message): RedirectResponse
    {
        return redirect()->route('provider.asistencias')->with($messageKey, $message);
    }

    public function dashboard(): View
    {
        $context = $this->providerContext();

        $availableResponse = ['ok' => false, 'data' => []];
        $mineResponse = ['ok' => false, 'data' => []];
        $availableRequests = [];
        $myRequests = [];

        if ($context['hasProfile']) {
            $mineResponse = $this->apiGet('/api/v1/provider/assistance-requests');
            $myRequests = array_map(fn ($item) => $this->normalizeRequest($item), $this->normalizeList($mineResponse['data'] ?? [], ['requests']));

            if ($context['readiness']['backend_can_operate']) {
                $availableResponse = $this->apiGet('/api/v1/provider/assistance-requests/available');
                $availableRequests = array_map(fn ($item) => $this->normalizeRequest($item), $this->normalizeList($availableResponse['data'] ?? [], ['requests']));
            }
        }

        return view('provider.dashboard', [
            'context' => $context,
            'availableResponse' => $availableResponse,
            'mineResponse' => $mineResponse,
            'availableRequests' => $availableRequests,
            'activeRequests' => $this->activeRequests($myRequests),
            'historicalRequests' => $this->historicalRequests($myRequests),
        ]);
    }

    public function perfil(): View
    {
        return view('provider.perfil.index', [
            'context' => $this->providerContext(),
        ]);
    }

    public function crearPerfil(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'display_name' => ['required', 'string', 'max:255'],
            'provider_kind' => ['nullable', 'string', 'max:100'],
        ]);

        $response = $this->apiSend('post', '/api/v1/provider/profile', [
            'display_name' => trim($validated['display_name']),
            'provider_kind' => $validated['provider_kind'] ?: null,
        ]);

        if ($response['ok']) {
            return redirect()->route('provider.servicios')
                ->with('success', 'Perfil creado correctamente. Continúa con tus servicios para completar la configuración operativa.');
        }

        return redirect()->route('provider.perfil')
            ->withInput()
            ->with('error', $response['message'] ?? 'No se pudo crear el perfil del proveedor.');
    }

    public function actualizarPerfil(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'display_name' => ['required', 'string', 'max:255'],
            'provider_kind' => ['nullable', 'string', 'max:100'],
        ]);

        $response = $this->apiSend('patch', '/api/v1/provider/profile', [
            'display_name' => trim($validated['display_name']),
            'provider_kind' => $validated['provider_kind'] ?: null,
        ]);

        if ($response['ok']) {
            return redirect()->route('provider.perfil')->with('success', 'Perfil actualizado correctamente.');
        }

        return redirect()->route('provider.perfil')
            ->withInput()
            ->with('error', $response['message'] ?? 'No se pudo actualizar el perfil.');
    }

    public function servicios(): View
    {
        $context = $this->providerContext();
        $catalogResponse = $this->publicServicesCatalog();
        $catalog = $this->normalizeList($catalogResponse['data'] ?? [], ['services']);
        $selectedIds = collect($context['services'])->pluck('id')->map(fn ($id) => (int) $id)->all();

        return view('provider.servicios.index', [
            'context' => $context,
            'catalogResponse' => $catalogResponse,
            'catalog' => $catalog,
            'selectedIds' => $selectedIds,
        ]);
    }

    public function actualizarServicios(Request $request): RedirectResponse
    {
        $request->validate([
            'service_ids' => ['required', 'array', 'min:1'],
            'service_ids.*' => ['integer'],
        ], [
            'service_ids.required' => 'Selecciona al menos un servicio.',
            'service_ids.min' => 'Selecciona al menos un servicio.',
        ]);

        $serviceIds = collect($request->input('service_ids', []))
            ->map(fn ($id) => (int) $id)
            ->filter(fn ($id) => $id > 0)
            ->unique()
            ->values()
            ->all();

        $response = $this->apiSend('put', '/api/v1/provider/services', [
            'service_ids' => $serviceIds,
        ]);

        if ($response['ok']) {
            return redirect()->route('provider.horarios')
                ->with('success', 'Servicios actualizados correctamente. Ahora registra al menos un horario activo.');
        }

        return redirect()->route('provider.servicios')
            ->withInput()
            ->with('error', $response['message'] ?? 'No se pudieron actualizar los servicios.');
    }

    public function horarios(): View
    {
        return view('provider.horarios.index', [
            'context' => $this->providerContext(),
            'dayOptions' => $this->dayOptions(),
        ]);
    }

    public function guardarHorario(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'day_of_week' => ['required', 'integer', 'min:1', 'max:7'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
        ]);

        $response = $this->apiSend('post', '/api/v1/provider/schedules', [
            'day_of_week' => (int) $validated['day_of_week'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
        ]);

        if ($response['ok']) {
            return redirect()->route('provider.horarios')->with('success', 'Horario registrado correctamente.');
        }

        return redirect()->route('provider.horarios')
            ->withInput()
            ->with('error', $response['message'] ?? 'No se pudo registrar el horario.');
    }

    public function actualizarHorario(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'day_of_week' => ['nullable', 'integer', 'min:1', 'max:7'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $payload = Arr::whereNotNull([
            'day_of_week' => $validated['day_of_week'] ?? null,
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'is_active' => $request->has('is_active') ? (bool) $request->boolean('is_active') : null,
        ]);

        $response = $this->apiSend('patch', '/api/v1/provider/schedules/' . $id, $payload);

        if ($response['ok']) {
            return redirect()->route('provider.horarios')->with('success', 'Horario actualizado correctamente.');
        }

        return redirect()->route('provider.horarios')
            ->withInput()
            ->with('error', $response['message'] ?? 'No se pudo actualizar el horario.');
    }

    public function eliminarHorario(int $id): RedirectResponse
    {
        $response = $this->apiSend('delete', '/api/v1/provider/schedules/' . $id);

        if ($response['ok']) {
            return redirect()->route('provider.horarios')->with('success', 'Horario eliminado correctamente.');
        }

        return redirect()->route('provider.horarios')
            ->with('error', $response['message'] ?? 'No se pudo eliminar el horario.');
    }

    public function documentos(): View
    {
        return view('provider.documentos.index', [
            'context' => $this->providerContext(),
        ]);
    }

    public function guardarDocumento(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'document_type' => ['required', 'string', 'max:255'],
            'document_url' => ['required', 'string', 'max:2048'],
        ]);

        $response = $this->apiSend('post', '/api/v1/provider/documents', [
            'document_type' => trim($validated['document_type']),
            'document_url' => trim($validated['document_url']),
        ]);

        if ($response['ok']) {
            return redirect()->route('provider.documentos')->with('success', 'Documento registrado correctamente.');
        }

        return redirect()->route('provider.documentos')
            ->withInput()
            ->with('error', $response['message'] ?? 'No se pudo registrar el documento.');
    }

    public function eliminarDocumento(int $id): RedirectResponse
    {
        $response = $this->apiSend('delete', '/api/v1/provider/documents/' . $id);

        if ($response['ok']) {
            return redirect()->route('provider.documentos')->with('success', 'Documento eliminado correctamente.');
        }

        return redirect()->route('provider.documentos')
            ->with('error', $response['message'] ?? 'No se pudo eliminar el documento.');
    }

    public function asistencias(): View
    {
        $context = $this->providerContext();
        $availableResponse = ['ok' => false, 'data' => []];
        $mineResponse = ['ok' => false, 'data' => []];
        $availableRequests = [];
        $myRequests = [];

        if ($context['hasProfile']) {
            $mineResponse = $this->apiGet('/api/v1/provider/assistance-requests');
            $myRequests = array_map(fn ($item) => $this->normalizeRequest($item), $this->normalizeList($mineResponse['data'] ?? [], ['requests']));

            if ($context['readiness']['backend_can_operate']) {
                $availableResponse = $this->apiGet('/api/v1/provider/assistance-requests/available');
                $availableRequests = array_map(fn ($item) => $this->normalizeRequest($item), $this->normalizeList($availableResponse['data'] ?? [], ['requests']));
            }
        }

        return view('provider.asistencias.index', [
            'context' => $context,
            'availableResponse' => $availableResponse,
            'mineResponse' => $mineResponse,
            'availableRequests' => $availableRequests,
            'activeRequests' => $this->activeRequests($myRequests),
            'historicalRequests' => $this->historicalRequests($myRequests),
            'allowedStatusOptionsResolver' => fn (?string $status) => $this->allowedStatusOptions($status),
        ]);
    }

    public function verAsistencia(int $id): View|RedirectResponse
    {
        $context = $this->providerContext();
        $response = $this->apiGet('/api/v1/provider/assistance-requests/' . $id);

        if (!$response['ok'] || !is_array($response['data'])) {
            return redirect()->route('provider.asistencias')
                ->with('error', $response['message'] ?? 'No se pudo cargar el detalle de la asistencia.');
        }

        $requestData = $response['data'];

        return view('provider.asistencias.show', [
            'context' => $context,
            'requestItem' => $this->normalizeRequest($requestData),
            'requestRaw' => $requestData,
            'allowedStatusOptions' => $this->allowedStatusOptions($requestData['status'] ?? null),
        ]);
    }

    public function aceptarAsistencia(int $id): RedirectResponse
    {
        $context = $this->providerContext();

        if (!$context['readiness']['portal_ready']) {
            return $this->routeBackToAssistance(
                'error',
                'Tu provider todavía no está listo para operar desde el portal. Completa onboarding, validación y horarios antes de aceptar solicitudes.'
            );
        }

        $response = $this->apiSend('patch', '/api/v1/provider/assistance-requests/' . $id . '/accept');

        if ($response['ok']) {
            return redirect()->route('provider.asistencias.show', $id)
                ->with('success', 'Solicitud aceptada correctamente.');
        }

        return $this->routeBackToAssistance('error', $response['message'] ?? 'No se pudo aceptar la solicitud.');
    }

    public function actualizarEstatusAsistencia(Request $request, int $id): RedirectResponse
    {
        $context = $this->providerContext();

        if (!$context['readiness']['portal_ready']) {
            return redirect()->route('provider.asistencias')
                ->with('error', 'Tu provider todavía no está listo para operar desde el portal.');
        }

        $validated = $request->validate([
            'status' => ['required', 'string', 'in:in_progress,completed,cancelled'],
        ]);

        $response = $this->apiSend('patch', '/api/v1/provider/assistance-requests/' . $id . '/status', [
            'status' => $validated['status'],
        ]);

        if ($response['ok']) {
            return redirect()->route('provider.asistencias.show', $id)
                ->with('success', 'Estatus actualizado correctamente.');
        }

        return redirect()->route('provider.asistencias.show', $id)
            ->with('error', $response['message'] ?? 'No se pudo actualizar el estatus.');
    }
}
