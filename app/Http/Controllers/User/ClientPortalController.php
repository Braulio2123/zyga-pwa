<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

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

    public function dashboard(): View|RedirectResponse
    {
        return $this->renderClientPage('dashboard');
    }

    public function solicitud(): View|RedirectResponse
    {
        return $this->renderClientPage('request');
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
        return $this->renderClientPage('account');
    }

    private function renderClientPage(string $pageKey): View|RedirectResponse
    {
        if ($redirect = $this->validateClientSession()) {
            return $redirect;
        }

        $page = self::PAGE_DEFINITIONS[$pageKey] ?? self::PAGE_DEFINITIONS['dashboard'];

        return view(
            $page['view'],
            $this->sharedViewData($pageKey, $page['title'], $page['heading'])
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
            'apiBaseUrl' => rtrim((string) env('URL_BASE_API', self::DEFAULT_API_BASE_URL), '/'),
            'apiToken' => (string) session('api_token'),
            'vehicleTypeOptions' => $vehicleTypeOptions,
            'vehicleTypeCatalogSource' => $vehicleTypeCatalogSource,
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
}
