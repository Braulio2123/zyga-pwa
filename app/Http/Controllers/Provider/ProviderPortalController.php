<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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
            $response = $this->apiClient()->get($this->apiBase . $endpoint);

            return $this->formatResponse($response);
        } catch (\Throwable $e) {
            return [
                'ok' => false,
                'status' => 0,
                'message' => 'No fue posible conectar con la API.',
                'details' => $e->getMessage(),
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
                'data' => [],
                'raw' => [],
            ];
        }
    }

    protected function formatResponse($response): array
    {
        return [
            'ok' => $response->successful(),
            'status' => $response->status(),
            'message' => $response->json('message') ?? ($response->successful() ? 'Operación realizada correctamente.' : 'La API respondió con error.'),
            'errors' => $response->json('errors') ?? [],
            'data' => $response->json('data') ?? [],
            'raw' => $response->json() ?? [],
        ];
    }

    protected function normalizeList($data, array $preferredKeys = []): array
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

        return [
            'profileResponse' => $profileResponse,
            'hasProfile' => $hasProfile,
            'profile' => $profile,
        ];
    }

    protected function publicServicesCatalog(): array
    {
        try {
            $response = Http::acceptJson()
                ->timeout(20)
                ->get($this->apiBase . '/api/v1/services');

            return $this->formatResponse($response);
        } catch (\Throwable $e) {
            return [
                'ok' => false,
                'status' => 0,
                'message' => 'No fue posible cargar el catálogo general de servicios.',
                'details' => $e->getMessage(),
                'data' => [],
                'raw' => [],
            ];
        }
    }

    protected function statusBadgeData(array $profile): array
    {
        $statusName = $profile['status']['name'] ?? null;
        $statusId = (int) ($profile['status_id'] ?? 0);

        if (!$statusName) {
            $statusName = match ($statusId) {
                1 => 'Activo',
                2 => 'En revisión',
                3 => 'Suspendido',
                default => 'Sin estado',
            };
        }

        $isVerified = (bool) ($profile['is_verified'] ?? false);

        return [
            'statusName' => $statusName,
            'verificationText' => $isVerified ? 'Verificado' : 'Pendiente de validación',
            'isVerified' => $isVerified,
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

    protected function initialProviderStatusCandidates(): array
    {
        $candidates = [];

        $single = (int) env('DEFAULT_PROVIDER_STATUS_ID', 0);
        if ($single > 0) {
            $candidates[] = $single;
        }

        $list = (string) env('DEFAULT_PROVIDER_STATUS_IDS', '');
        if ($list !== '') {
            $parsed = collect(explode(',', $list))
                ->map(fn ($value) => (int) trim($value))
                ->filter(fn ($value) => $value > 0)
                ->values()
                ->all();

            $candidates = array_merge($candidates, $parsed);
        }

        if (empty($candidates)) {
            $candidates = range(1, 50);
        }

        return array_values(array_unique($candidates));
    }

    protected function isProviderStatusValidationError(array $response): bool
    {
        $message = strtolower((string) ($response['message'] ?? ''));
        $errors = $response['errors'] ?? [];
        $statusErrors = strtolower(implode(' ', $errors['status_id'] ?? []));

        return str_contains($message, 'status id')
            || str_contains($message, 'status_id')
            || str_contains($message, 'no pertenece al dominio de proveedor')
            || str_contains($statusErrors, 'status id')
            || str_contains($statusErrors, 'status_id')
            || str_contains($statusErrors, 'dominio de proveedor');
    }

    protected function createProfileAgainstApi(array $payload): array
    {
        $lastResponse = [
            'ok' => false,
            'status' => 422,
            'message' => 'No se encontró un status_id válido para crear el perfil del proveedor.',
            'errors' => [],
            'data' => [],
            'raw' => [],
        ];

        foreach ($this->initialProviderStatusCandidates() as $statusId) {
            $response = $this->apiSend('post', '/api/v1/provider/profile', array_merge($payload, [
                'status_id' => $statusId,
            ]));

            if ($response['ok']) {
                return $response;
            }

            $lastResponse = $response;

            if (!$this->isProviderStatusValidationError($response)) {
                return $response;
            }
        }

        $lastResponse['message'] = 'No se pudo crear el perfil porque la API requiere un status_id inicial válido para provider y en este ambiente no coincide con el valor fijo anterior. Configura DEFAULT_PROVIDER_STATUS_ID en el .env web o revisa los status_types del dominio provider en Railway.';

        return $lastResponse;
    }

    public function dashboard()
    {
        $context = $this->providerContext();

        $services = [];
        $schedules = [];
        $availableRequests = [];
        $myRequests = [];

        if ($context['hasProfile']) {
            $servicesResponse = $this->apiGet('/api/v1/provider/services');
            $schedulesResponse = $this->apiGet('/api/v1/provider/schedules');
            $availableResponse = $this->apiGet('/api/v1/provider/assistance-requests/available');
            $mineResponse = $this->apiGet('/api/v1/provider/assistance-requests');

            $services = $this->normalizeList($servicesResponse['data'] ?? [], ['services']);
            $schedules = $this->normalizeList($schedulesResponse['data'] ?? [], ['schedules']);
            $availableRequests = $this->normalizeList($availableResponse['data'] ?? [], ['requests']);
            $myRequests = $this->normalizeList($mineResponse['data'] ?? [], ['requests']);
        }

        return view('provider.dashboard', [
            'profileResponse' => $context['profileResponse'],
            'profile' => $context['profile'],
            'hasProfile' => $context['hasProfile'],
            'services' => $services,
            'schedules' => $schedules,
            'availableRequests' => $availableRequests,
            'myRequests' => $myRequests,
            'dayOptions' => $this->dayOptions(),
            'badgeData' => $this->statusBadgeData($context['profile']),
        ]);
    }

    public function perfil()
    {
        $context = $this->providerContext();

        return view('provider.perfil.index', [
            'profileResponse' => $context['profileResponse'],
            'profile' => $context['profile'],
            'hasProfile' => $context['hasProfile'],
            'badgeData' => $this->statusBadgeData($context['profile']),
        ]);
    }

    public function crearPerfil(Request $request)
    {
        $validated = $request->validate([
            'display_name' => ['required', 'string', 'max:255'],
            'provider_kind' => ['nullable', 'string', 'max:100'],
        ]);

        $response = $this->createProfileAgainstApi([
            'display_name' => trim($validated['display_name']),
            'provider_kind' => $validated['provider_kind'] ?: null,
        ]);

        if ($response['ok']) {
            return redirect()->route('provider.servicios')->with('success', 'Perfil creado correctamente. Ahora selecciona los servicios que ofrecerás.');
        }

        return redirect()->route('provider.perfil')->withInput()->with('error', $response['message'] ?? 'No se pudo crear el perfil del proveedor.');
    }

    public function actualizarPerfil(Request $request)
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

        return redirect()->route('provider.perfil')->withInput()->with('error', $response['message'] ?? 'No se pudo actualizar el perfil.');
    }

    public function servicios()
    {
        $context = $this->providerContext();
        $catalogResponse = $this->publicServicesCatalog();
        $catalog = $this->normalizeList($catalogResponse['data'] ?? [], ['services']);
        $myServicesResponse = ['ok' => false, 'message' => null, 'data' => []];
        $myServices = [];
        $selectedIds = [];

        if ($context['hasProfile']) {
            $myServicesResponse = $this->apiGet('/api/v1/provider/services');
            $myServices = $this->normalizeList($myServicesResponse['data'] ?? [], ['services']);
            $selectedIds = collect($myServices)->pluck('id')->filter()->map(fn ($id) => (int) $id)->values()->all();
        }

        return view('provider.servicios.index', [
            'hasProfile' => $context['hasProfile'],
            'catalogResponse' => $catalogResponse,
            'myServicesResponse' => $myServicesResponse,
            'catalog' => $catalog,
            'myServices' => $myServices,
            'selectedIds' => $selectedIds,
        ]);
    }

    public function actualizarServicios(Request $request)
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
            return redirect()->route('provider.horarios')->with('success', 'Servicios actualizados correctamente. Continúa con la configuración de horarios.');
        }

        return redirect()->route('provider.servicios')->withInput()->with('error', $response['message'] ?? 'No se pudieron actualizar los servicios.');
    }

    public function horarios()
    {
        $context = $this->providerContext();
        $schedulesResponse = ['ok' => false, 'message' => null, 'data' => []];
        $schedules = [];

        if ($context['hasProfile']) {
            $schedulesResponse = $this->apiGet('/api/v1/provider/schedules');
            $schedules = $this->normalizeList($schedulesResponse['data'] ?? [], ['schedules']);
        }

        return view('provider.horarios.index', [
            'hasProfile' => $context['hasProfile'],
            'schedulesResponse' => $schedulesResponse,
            'schedules' => $schedules,
            'dayOptions' => $this->dayOptions(),
        ]);
    }

    public function guardarHorario(Request $request)
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

        return redirect()->route('provider.horarios')->withInput()->with('error', $response['message'] ?? 'No se pudo registrar el horario.');
    }

    public function actualizarHorario(Request $request, int $id)
    {
        $validated = $request->validate([
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
        ]);

        $response = $this->apiSend('patch', '/api/v1/provider/schedules/' . $id, [
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
        ]);

        if ($response['ok']) {
            return redirect()->route('provider.horarios')->with('success', 'Horario actualizado correctamente.');
        }

        return redirect()->route('provider.horarios')->withInput()->with('error', $response['message'] ?? 'No se pudo actualizar el horario.');
    }

    public function eliminarHorario(int $id)
    {
        $response = $this->apiSend('delete', '/api/v1/provider/schedules/' . $id);

        if ($response['ok']) {
            return redirect()->route('provider.horarios')->with('success', 'Horario eliminado correctamente.');
        }

        return redirect()->route('provider.horarios')->with('error', $response['message'] ?? 'No se pudo eliminar el horario.');
    }

    public function documentos()
    {
        $context = $this->providerContext();
        $documentsResponse = ['ok' => false, 'message' => null, 'data' => []];
        $documents = [];

        if ($context['hasProfile']) {
            $documentsResponse = $this->apiGet('/api/v1/provider/documents');
            $documents = $this->normalizeList($documentsResponse['data'] ?? [], ['documents']);
        }

        return view('provider.documentos.index', [
            'hasProfile' => $context['hasProfile'],
            'documentsResponse' => $documentsResponse,
            'documents' => $documents,
        ]);
    }

    public function guardarDocumento(Request $request)
    {
        $validated = $request->validate([
            'document_type' => ['required', 'string', 'max:255'],
            'document_url' => ['required', 'url', 'max:2048'],
        ]);

        $response = $this->apiSend('post', '/api/v1/provider/documents', [
            'document_type' => trim($validated['document_type']),
            'document_url' => trim($validated['document_url']),
        ]);

        if ($response['ok']) {
            return redirect()->route('provider.documentos')->with('success', 'Documento registrado correctamente.');
        }

        return redirect()->route('provider.documentos')->withInput()->with('error', $response['message'] ?? 'No se pudo registrar el documento.');
    }

    public function eliminarDocumento(int $id)
    {
        $response = $this->apiSend('delete', '/api/v1/provider/documents/' . $id);

        if ($response['ok']) {
            return redirect()->route('provider.documentos')->with('success', 'Documento eliminado correctamente.');
        }

        return redirect()->route('provider.documentos')->with('error', $response['message'] ?? 'No se pudo eliminar el documento.');
    }

    public function asistencias()
    {
        $context = $this->providerContext();
        $availableResponse = ['ok' => false, 'message' => null, 'data' => []];
        $mineResponse = ['ok' => false, 'message' => null, 'data' => []];
        $availableRequests = [];
        $myRequests = [];

        if ($context['hasProfile']) {
            $availableResponse = $this->apiGet('/api/v1/provider/assistance-requests/available');
            $mineResponse = $this->apiGet('/api/v1/provider/assistance-requests');
            $availableRequests = $this->normalizeList($availableResponse['data'] ?? [], ['requests']);
            $myRequests = $this->normalizeList($mineResponse['data'] ?? [], ['requests']);
        }

        return view('provider.asistencias.index', [
            'hasProfile' => $context['hasProfile'],
            'availableResponse' => $availableResponse,
            'mineResponse' => $mineResponse,
            'availableRequests' => $availableRequests,
            'myRequests' => $myRequests,
            'allowedStatusOptionsResolver' => fn (?string $status) => $this->allowedStatusOptions($status),
        ]);
    }

    public function aceptarAsistencia(int $id)
    {
        $response = $this->apiSend('patch', '/api/v1/provider/assistance-requests/' . $id . '/accept');

        if ($response['ok']) {
            return redirect()->route('provider.asistencias')->with('success', 'Solicitud aceptada correctamente.');
        }

        return redirect()->route('provider.asistencias')->with('error', $response['message'] ?? 'No se pudo aceptar la solicitud.');
    }

    public function actualizarEstatusAsistencia(Request $request, int $id)
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:in_progress,completed,cancelled'],
        ]);

        $response = $this->apiSend('patch', '/api/v1/provider/assistance-requests/' . $id . '/status', [
            'status' => $validated['status'],
        ]);

        if ($response['ok']) {
            return redirect()->route('provider.asistencias')->with('success', 'Estatus actualizado correctamente.');
        }

        return redirect()->route('provider.asistencias')->with('error', $response['message'] ?? 'No se pudo actualizar el estatus.');
    }
}
