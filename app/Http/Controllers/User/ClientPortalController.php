<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Http;

class ClientPortalController extends Controller
{
    private const DEFAULT_API_BASE_URL = 'http://127.0.0.1:8000';

    private const DEFAULT_VEHICLE_TYPE_OPTIONS = [
        ['id' => 1, 'name' => 'Automóvil'],
        ['id' => 2, 'name' => 'Motocicleta'],
        ['id' => 3, 'name' => 'Camioneta'],
    ];

    private const PAGE_DEFINITIONS = [
        'dashboard' => [
            'view' => 'user.dashboard',
            'title' => 'ZYGA | Inicio',
            'heading' => 'Inicio',
        ],
        'request' => [
            'view' => 'user.request.index',
            'title' => 'ZYGA | Solicitar asistencia',
            'heading' => 'Solicitar asistencia',
        ],
        'active' => [
            'view' => 'user.active.index',
            'title' => 'ZYGA | Servicio activo',
            'heading' => 'Servicio activo',
        ],
        'history' => [
            'view' => 'user.historial.index',
            'title' => 'ZYGA | Historial',
            'heading' => 'Historial',
        ],
        'payments' => [
            'view' => 'user.billetera.index',
            'title' => 'ZYGA | Pagos',
            'heading' => 'Pagos y métodos',
        ],
        'account' => [
            'view' => 'user.cuenta.index',
            'title' => 'ZYGA | Cuenta',
            'heading' => 'Mi cuenta',
        ],
    ];

    protected string $apiBaseUrl;

    public function __construct()
    {
        $this->apiBaseUrl = rtrim((string) env('URL_BASE_API', self::DEFAULT_API_BASE_URL), '/');
    }

    public function dashboard(): View|RedirectResponse
    {
        return $this->renderClientPage('dashboard', $this->dashboardContext());
    }

    public function solicitud(): View|RedirectResponse
    {
        return $this->renderClientPage('request', $this->requestContext());
    }

    public function servicioActivo(): View|RedirectResponse
    {
        return $this->renderClientPage('active');
    }

    public function historial(): View|RedirectResponse
    {
        return $this->renderClientPage('history');
    }

    public function pagos(): View|RedirectResponse
    {
        return $this->renderClientPage('payments');
    }

    public function cuenta(): View|RedirectResponse
    {
        return $this->renderClientPage('account', $this->accountContext());
    }

    private function renderClientPage(string $pageKey, array $extraData = []): View|RedirectResponse
    {
        if ($redirect = $this->validateClientSession()) {
            return $redirect;
        }

        $page = self::PAGE_DEFINITIONS[$pageKey] ?? self::PAGE_DEFINITIONS['dashboard'];

        return view(
            $page['view'],
            array_merge(
                $this->sharedViewData($pageKey, $page['title'], $page['heading']),
                $extraData
            )
        );
    }

    private function validateClientSession(): ?RedirectResponse
    {
        $sessionUser = session('user');
        $apiToken = session('api_token');

        if (!$sessionUser || !$apiToken) {
            return redirect()
                ->route('login')
                ->withErrors(['email' => 'Debes iniciar sesión para continuar.']);
        }

        if (!$this->hasClientRole()) {
            return redirect()
                ->route('login')
                ->withErrors(['email' => 'Tu sesión no corresponde a un cliente válido.']);
        }

        return null;
    }

    private function hasClientRole(): bool
    {
        $sessionUser = session('user', []);

        $rawRoles = collect(
            session('roles')
            ?? session('api_roles')
            ?? data_get($sessionUser, 'roles', [])
            ?? data_get(session('api_user'), 'roles', [])
            ?? []
        );

        $normalizedRoles = $rawRoles
            ->map(function ($role) {
                if (is_array($role)) {
                    return $role['code'] ?? $role['name'] ?? null;
                }

                if (is_object($role)) {
                    return $role->code ?? $role->name ?? null;
                }

                return $role;
            })
            ->filter()
            ->map(fn ($role) => strtolower((string) $role))
            ->values();

        $userRole = strtolower((string) data_get($sessionUser, 'role', ''));

        return $userRole === 'client' || $normalizedRoles->contains('client');
    }

    private function sharedViewData(string $pageKey, string $pageTitle, string $pageHeading): array
    {
        [$vehicleTypeOptions, $vehicleTypeCatalogSource] = $this->resolveVehicleTypeOptions();

        return [
            'pageKey' => $pageKey,
            'pageTitle' => $pageTitle,
            'pageHeading' => $pageHeading,
            'sessionUser' => session('user', []),
            'apiBaseUrl' => $this->apiBaseUrl,
            'apiToken' => (string) session('api_token'),
            'vehicleTypeOptions' => $vehicleTypeOptions,
            'vehicleTypeCatalogSource' => $vehicleTypeCatalogSource,
        ];
    }

    private function dashboardContext(): array
    {
        $servicesResponse = $this->apiGet('/api/v1/services');
        $vehiclesResponse = $this->apiGet('/api/v1/client/vehicles');
        $requestsResponse = $this->apiGet('/api/v1/client/assistance-requests');

        $dashboardServices = $servicesResponse['ok']
            ? $this->normalizeList($servicesResponse['data'], ['services', 'items'])
            : [];

        $dashboardVehicles = $vehiclesResponse['ok']
            ? $this->normalizeList($vehiclesResponse['data'], ['vehicles', 'items'])
            : [];

        $dashboardRequestHistory = $requestsResponse['ok']
            ? $this->normalizeList($requestsResponse['data'], ['requests', 'items'])
            : [];

        $dashboardActiveRequest = $this->findActiveRequest($dashboardRequestHistory);

        $dashboardApiErrors = [];

        if (!$servicesResponse['ok']) {
            $dashboardApiErrors[] = 'No fue posible consultar los servicios disponibles desde la API.';
        }

        if (!$vehiclesResponse['ok']) {
            $dashboardApiErrors[] = 'No fue posible consultar los vehículos del cliente desde la API.';
        }

        if (!$requestsResponse['ok']) {
            $dashboardApiErrors[] = 'No fue posible consultar las asistencias del cliente desde la API.';
        }

        return [
            'dashboardServices' => $dashboardServices,
            'dashboardVehicles' => $dashboardVehicles,
            'dashboardRequestHistory' => $dashboardRequestHistory,
            'dashboardActiveRequest' => $dashboardActiveRequest,
            'dashboardApiErrors' => $dashboardApiErrors,
        ];
    }

    private function requestContext(): array
    {
        $servicesResponse = $this->apiGet('/api/v1/services');
        $vehiclesResponse = $this->apiGet('/api/v1/client/vehicles');
        $requestsResponse = $this->apiGet('/api/v1/client/assistance-requests');

        $requestServices = $servicesResponse['ok']
            ? $this->normalizeList($servicesResponse['data'], ['services', 'items'])
            : [];

        $requestVehicles = $vehiclesResponse['ok']
            ? $this->normalizeList($vehiclesResponse['data'], ['vehicles', 'items'])
            : [];

        $requestHistory = $requestsResponse['ok']
            ? $this->normalizeList($requestsResponse['data'], ['requests', 'items'])
            : [];

        $requestActiveRequest = $this->findActiveRequest($requestHistory);

        $requestApiErrors = [];

        if (!$servicesResponse['ok']) {
            $requestApiErrors[] = 'No fue posible consultar los servicios disponibles desde la API.';
        }

        if (!$vehiclesResponse['ok']) {
            $requestApiErrors[] = 'No fue posible consultar los vehículos del cliente desde la API.';
        }

        if (!$requestsResponse['ok']) {
            $requestApiErrors[] = 'No fue posible validar si el cliente ya tiene una solicitud activa.';
        }

        $requestCanCreate = $servicesResponse['ok']
            && $vehiclesResponse['ok']
            && $requestsResponse['ok']
            && !empty($requestServices)
            && !empty($requestVehicles)
            && empty($requestActiveRequest);

        return [
            'requestServices' => $requestServices,
            'requestVehicles' => $requestVehicles,
            'requestHistory' => $requestHistory,
            'requestActiveRequest' => $requestActiveRequest,
            'requestApiErrors' => $requestApiErrors,
            'requestCanCreate' => $requestCanCreate,
        ];
    }

    private function accountContext(): array
    {
        $profileResponse = $this->apiGet('/api/v1/me');
        $vehiclesResponse = $this->apiGet('/api/v1/client/vehicles');

        $accountProfile = $profileResponse['ok'] && is_array($profileResponse['data'])
            ? $profileResponse['data']
            : [];

        $accountVehicles = $vehiclesResponse['ok']
            ? $this->normalizeList($vehiclesResponse['data'], ['vehicles', 'items'])
            : [];

        $errors = [];

        if (!$profileResponse['ok']) {
            $errors[] = 'No fue posible consultar el perfil del cliente desde la API.';
        }

        if (!$vehiclesResponse['ok']) {
            $errors[] = 'No fue posible consultar los vehículos del cliente desde la API.';
        }

        return [
            'accountProfile' => $accountProfile,
            'accountVehicles' => $accountVehicles,
            'accountLoadError' => !empty($errors) ? implode(' ', $errors) : null,
        ];
    }

    private function resolveVehicleTypeOptions(): array
    {
        $rawJson = trim((string) env('PWA_VEHICLE_TYPES_JSON', ''));

        if ($rawJson !== '') {
            $decoded = json_decode($rawJson, true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $options = collect($decoded)
                    ->filter(fn ($item) => is_array($item))
                    ->map(function (array $item) {
                        return [
                            'id' => $item['id'] ?? null,
                            'name' => trim((string) ($item['name'] ?? '')),
                        ];
                    })
                    ->filter(fn (array $item) => !empty($item['id']) && $item['name'] !== '')
                    ->values()
                    ->all();

                if (!empty($options)) {
                    return [$options, 'env'];
                }
            }
        }

        return [self::DEFAULT_VEHICLE_TYPE_OPTIONS, 'fallback'];
    }

    private function apiClient(): PendingRequest
    {
        return Http::acceptJson()
            ->withToken((string) session('api_token'))
            ->timeout(20);
    }

    private function apiGet(string $endpoint): array
    {
        try {
            $response = $this->apiClient()->get($this->apiBaseUrl . $endpoint);

            return $this->formatResponse($response);
        } catch (\Throwable $e) {
            return [
                'ok' => false,
                'status' => 0,
                'message' => 'No fue posible conectar con la API.',
                'errors' => [],
                'data' => [],
                'raw' => [],
                'details' => $e->getMessage(),
            ];
        }
    }

    private function formatResponse($response): array
    {
        $json = $response->json();

        return [
            'ok' => $response->successful(),
            'status' => $response->status(),
            'message' => is_array($json)
                ? ($json['message'] ?? ($response->successful() ? 'Operación realizada correctamente.' : 'La API respondió con error.'))
                : ($response->successful() ? 'Operación realizada correctamente.' : 'La API respondió con error.'),
            'errors' => is_array($json) ? ($json['errors'] ?? []) : [],
            'data' => is_array($json) ? ($json['data'] ?? []) : [],
            'raw' => is_array($json) ? $json : [],
        ];
    }

    private function normalizeList(mixed $data, array $preferredKeys = []): array
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

            foreach (['items', 'vehicles', 'services', 'requests'] as $key) {
                if (isset($data[$key]) && is_array($data[$key])) {
                    return $data[$key];
                }
            }
        }

        return [];
    }

    private function findActiveRequest(array $requests): array
    {
        foreach ($requests as $request) {
            $status = strtolower((string) data_get($request, 'status', ''));

            if (!in_array($status, ['completed', 'cancelled'], true)) {
                return $request;
            }
        }

        return [];
    }
}
