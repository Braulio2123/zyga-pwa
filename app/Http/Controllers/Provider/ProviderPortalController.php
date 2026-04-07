<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class ProviderPortalController extends Controller
{
    private string $api;
    private ?string $token;

    public function __construct()
    {
        $this->api = rtrim((string) env('URL_BASE_API'), '/');
        $this->token = session('api_token');
    }

    private function apiRequest(string $method, string $endpoint, array $payload = []): array
    {
        if (empty($this->api)) {
            return [
                'ok' => false,
                'status' => 500,
                'message' => 'URL_BASE_API no está configurada en el proyecto web.',
                'data' => null,
                'raw' => [],
            ];
        }

        try {
            $request = Http::withToken($this->token)
                ->acceptJson()
                ->timeout(20);

            $response = match (strtoupper($method)) {
                'GET' => $request->get($this->api . $endpoint),
                'POST' => $request->post($this->api . $endpoint, $payload),
                'PUT' => $request->put($this->api . $endpoint, $payload),
                'PATCH' => $request->patch($this->api . $endpoint, $payload),
                'DELETE' => $request->delete($this->api . $endpoint, $payload),
                default => throw new \InvalidArgumentException('Método HTTP no soportado.'),
            };

            $json = $response->json();
            $message = is_array($json)
                ? ($json['message'] ?? 'La API respondió sin mensaje.')
                : 'La API devolvió una respuesta no válida.';

            return [
                'ok' => $response->successful(),
                'status' => $response->status(),
                'message' => $message,
                'data' => is_array($json) ? ($json['data'] ?? null) : null,
                'errors' => is_array($json) ? ($json['errors'] ?? []) : [],
                'raw' => is_array($json) ? $json : [],
            ];
        } catch (\Throwable $e) {
            return [
                'ok' => false,
                'status' => 500,
                'message' => 'No fue posible conectar con la API.',
                'data' => null,
                'errors' => [],
                'raw' => [],
                'exception' => $e->getMessage(),
            ];
        }
    }

    private function redirectApiError(string $route, array $result): RedirectResponse
    {
        $message = $result['message'] ?? 'No fue posible completar la operación.';

        if (!empty($result['exception'])) {
            $message .= ' ' . $result['exception'];
        }

        return redirect()
            ->route($route)
            ->withInput()
            ->with('error', $message);
    }

    public function dashboard(): View
    {
        $profile = $this->apiRequest('GET', '/api/v1/provider/profile');
        $services = $this->apiRequest('GET', '/api/v1/provider/services');
        $available = $this->apiRequest('GET', '/api/v1/provider/assistance-requests/available');
        $myRequests = $this->apiRequest('GET', '/api/v1/provider/assistance-requests');
        $documents = $this->apiRequest('GET', '/api/v1/provider/documents');

        return view('provider.dashboard', [
            'profileResult' => $profile,
            'servicesResult' => $services,
            'availableResult' => $available,
            'myRequestsResult' => $myRequests,
            'documentsResult' => $documents,
        ]);
    }

    public function perfil(): View
    {
        return view('provider.perfil.index', [
            'perfilResult' => $this->apiRequest('GET', '/api/v1/provider/profile'),
        ]);
    }

    public function actualizarPerfil(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'display_name' => ['required', 'string', 'max:255'],
            'provider_kind' => ['nullable', 'string', 'max:100'],
        ]);

        $result = $this->apiRequest('PATCH', '/api/v1/provider/profile', [
            'display_name' => trim($validated['display_name']),
            'provider_kind' => filled($validated['provider_kind'] ?? null)
                ? trim((string) $validated['provider_kind'])
                : null,
        ]);

        if (!$result['ok']) {
            return $this->redirectApiError('provider.perfil', $result);
        }

        return redirect()
            ->route('provider.perfil')
            ->with('success', $result['message'] ?? 'Perfil actualizado correctamente.');
    }

    public function servicios(): View
    {
        return view('provider.servicios.index', [
            'providerServicesResult' => $this->apiRequest('GET', '/api/v1/provider/services'),
            'catalogServicesResult' => $this->apiRequest('GET', '/api/v1/services'),
        ]);
    }

    public function actualizarServicios(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'service_ids' => ['required', 'array', 'min:1'],
            'service_ids.*' => ['integer'],
        ], [
            'service_ids.required' => 'Selecciona al menos un servicio.',
            'service_ids.min' => 'Selecciona al menos un servicio.',
        ]);

        $result = $this->apiRequest('PUT', '/api/v1/provider/services', [
            'service_ids' => array_values(array_map('intval', $validated['service_ids'])),
        ]);

        if (!$result['ok']) {
            return $this->redirectApiError('provider.servicios', $result);
        }

        return redirect()
            ->route('provider.servicios')
            ->with('success', $result['message'] ?? 'Servicios actualizados correctamente.');
    }

    public function horarios(): View
    {
        return view('provider.horarios.index', [
            'horariosResult' => $this->apiRequest('GET', '/api/v1/provider/schedules'),
        ]);
    }

    public function guardarHorario(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'day_of_week' => ['required', 'integer', 'min:1', 'max:7'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'timezone' => ['nullable', 'string', 'max:50'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $result = $this->apiRequest('POST', '/api/v1/provider/schedules', [
            'day_of_week' => (int) $validated['day_of_week'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'timezone' => $validated['timezone'] ?? 'America/Mexico_City',
            'is_active' => $request->boolean('is_active', true),
        ]);

        if (!$result['ok']) {
            return $this->redirectApiError('provider.horarios', $result);
        }

        return redirect()
            ->route('provider.horarios')
            ->with('success', $result['message'] ?? 'Horario registrado correctamente.');
    }

    public function actualizarHorario(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'day_of_week' => ['nullable', 'integer', 'min:1', 'max:7'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i'],
            'timezone' => ['nullable', 'string', 'max:50'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $payload = [
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'timezone' => $validated['timezone'] ?? 'America/Mexico_City',
            'is_active' => $request->boolean('is_active'),
        ];

        if (!empty($validated['day_of_week'])) {
            $payload['day_of_week'] = (int) $validated['day_of_week'];
        }

        $result = $this->apiRequest('PATCH', '/api/v1/provider/schedules/' . $id, $payload);

        if (!$result['ok']) {
            return $this->redirectApiError('provider.horarios', $result);
        }

        return redirect()
            ->route('provider.horarios')
            ->with('success', $result['message'] ?? 'Horario actualizado correctamente.');
    }

    public function eliminarHorario(int $id): RedirectResponse
    {
        $result = $this->apiRequest('DELETE', '/api/v1/provider/schedules/' . $id);

        if (!$result['ok']) {
            return $this->redirectApiError('provider.horarios', $result);
        }

        return redirect()
            ->route('provider.horarios')
            ->with('success', $result['message'] ?? 'Horario eliminado correctamente.');
    }

    public function documentos(): View
    {
        return view('provider.documentos.index', [
            'documentosResult' => $this->apiRequest('GET', '/api/v1/provider/documents'),
        ]);
    }

    public function guardarDocumento(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'document_type' => ['required', 'string', 'max:255'],
            'document_url' => ['required', 'url', 'max:2048'],
        ]);

        $result = $this->apiRequest('POST', '/api/v1/provider/documents', [
            'document_type' => trim($validated['document_type']),
            'document_url' => trim($validated['document_url']),
        ]);

        if (!$result['ok']) {
            return $this->redirectApiError('provider.documentos', $result);
        }

        return redirect()
            ->route('provider.documentos')
            ->with('success', $result['message'] ?? 'Documento registrado correctamente.');
    }

    public function eliminarDocumento(int $id): RedirectResponse
    {
        $result = $this->apiRequest('DELETE', '/api/v1/provider/documents/' . $id);

        if (!$result['ok']) {
            return $this->redirectApiError('provider.documentos', $result);
        }

        return redirect()
            ->route('provider.documentos')
            ->with('success', $result['message'] ?? 'Documento eliminado correctamente.');
    }

    public function asistencias(): View
    {
        return view('provider.asistencias.index', [
            'availableResult' => $this->apiRequest('GET', '/api/v1/provider/assistance-requests/available'),
            'myRequestsResult' => $this->apiRequest('GET', '/api/v1/provider/assistance-requests'),
        ]);
    }

    public function aceptarAsistencia(int $id): RedirectResponse
    {
        $result = $this->apiRequest('PATCH', '/api/v1/provider/assistance-requests/' . $id . '/accept');

        if (!$result['ok']) {
            return $this->redirectApiError('provider.asistencias', $result);
        }

        return redirect()
            ->route('provider.asistencias')
            ->with('success', $result['message'] ?? 'Solicitud aceptada correctamente.');
    }

    public function actualizarEstadoAsistencia(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:in_progress,completed,cancelled'],
        ]);

        $result = $this->apiRequest('PATCH', '/api/v1/provider/assistance-requests/' . $id . '/status', [
            'status' => $validated['status'],
        ]);

        if (!$result['ok']) {
            return $this->redirectApiError('provider.asistencias', $result);
        }

        return redirect()
            ->route('provider.asistencias')
            ->with('success', $result['message'] ?? 'Estado actualizado correctamente.');
    }
}
