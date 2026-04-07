<?php

namespace App\Http\Controllers\Admin;

use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class DashboardController extends BaseAdminController
{
    public function index(): View|RedirectResponse
    {
        if ($redirect = $this->redirectIfNotAdmin()) {
            return $redirect;
        }

        $usersResponse = $this->api('GET', '/api/v1/admin/users');
        $providersResponse = $this->api('GET', '/api/v1/admin/providers');
        $requestsResponse = $this->api('GET', '/api/v1/admin/assistance-requests');
        $paymentsResponse = $this->api('GET', '/api/v1/admin/finance/payments');
        $servicesResponse = $this->api('GET', '/api/v1/admin/services');

        $users = $this->toList($usersResponse['data']);
        $providers = $this->toList($providersResponse['data']);
        $requests = $this->toList($requestsResponse['data']);
        $payments = $this->toList($paymentsResponse['data']);
        $services = $this->toList($servicesResponse['data']);

        $apiErrors = array_values(array_filter([
            !$usersResponse['ok'] ? 'Usuarios: ' . $usersResponse['message'] : null,
            !$providersResponse['ok'] ? 'Proveedores: ' . $providersResponse['message'] : null,
            !$requestsResponse['ok'] ? 'Solicitudes: ' . $requestsResponse['message'] : null,
            !$paymentsResponse['ok'] ? 'Pagos: ' . $paymentsResponse['message'] : null,
            !$servicesResponse['ok'] ? 'Servicios: ' . $servicesResponse['message'] : null,
        ]));

        $completedPayments = array_values(array_filter($payments, fn ($payment) => ($payment['status'] ?? null) === 'completed'));
        $pendingRequests = array_values(array_filter($requests, fn ($request) => in_array($request['status'] ?? '', ['created', 'assigned', 'in_progress'], true)));
        $verifiedProviders = array_values(array_filter($providers, fn ($provider) => !empty($provider['is_verified'])));
        $activeServices = array_values(array_filter($services, fn ($service) => !empty($service['is_active'])));

        $recentRequests = array_slice($requests, 0, 5);
        $recentPayments = array_slice($payments, 0, 5);

        return view('admin.dashboard', [
            'metrics' => [
                'users' => count($users),
                'providers' => count($providers),
                'verified_providers' => count($verifiedProviders),
                'active_services' => count($activeServices),
                'requests' => count($requests),
                'pending_requests' => count($pendingRequests),
                'payments' => count($payments),
                'completed_revenue' => array_sum(array_map(fn ($payment) => (float) ($payment['amount'] ?? 0), $completedPayments)),
            ],
            'recentRequests' => $recentRequests,
            'recentPayments' => $recentPayments,
            'apiErrors' => $apiErrors,
        ]);
    }
}