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

        $verifiedProviders = array_values(array_filter($providers, fn ($provider) => !empty($provider['is_verified'])));
        $activeServices = array_values(array_filter($services, fn ($service) => !empty($service['is_active'])));
        $completedPayments = array_values(array_filter($payments, fn ($payment) => ($payment['status'] ?? null) === 'completed'));
        $failedPayments = array_values(array_filter($payments, fn ($payment) => ($payment['status'] ?? null) === 'failed'));

        $queueRequests = array_values(array_filter($requests, fn ($request) => ($request['status'] ?? null) === 'created'));
        $assignedRequests = array_values(array_filter($requests, fn ($request) => ($request['status'] ?? null) === 'assigned'));
        $inProgressRequests = array_values(array_filter($requests, fn ($request) => ($request['status'] ?? null) === 'in_progress'));
        $completedRequests = array_values(array_filter($requests, fn ($request) => ($request['status'] ?? null) === 'completed'));
        $cancelledRequests = array_values(array_filter($requests, fn ($request) => ($request['status'] ?? null) === 'cancelled'));

        $notReadyProviders = array_values(array_filter($providers, function ($provider) {
            $hasServices = count($provider['services'] ?? []) > 0;
            $hasSchedules = count($provider['schedules'] ?? []) > 0;
            $hasDocuments = count($provider['documents'] ?? []) > 0;

            return empty($provider['is_verified']) || !$hasServices || !$hasSchedules || !$hasDocuments;
        }));

        $statusBreakdown = [
            'created' => count($queueRequests),
            'assigned' => count($assignedRequests),
            'in_progress' => count($inProgressRequests),
            'completed' => count($completedRequests),
            'cancelled' => count($cancelledRequests),
        ];

        arsort($statusBreakdown);

        $operationalDoughnut = [
            'labels' => [
                'Solicitudes activas',
                'Completadas',
                'Canceladas',
            ],
            'values' => [
                count($queueRequests) + count($assignedRequests) + count($inProgressRequests),
                count($completedRequests),
                count($cancelledRequests),
            ],
        ];

        $statusBarChart = [
            'labels' => [
                'En cola',
                'Asignadas',
                'En progreso',
                'Completadas',
                'Canceladas',
            ],
            'values' => [
                count($queueRequests),
                count($assignedRequests),
                count($inProgressRequests),
                count($completedRequests),
                count($cancelledRequests),
            ],
        ];

        return view('admin.dashboard', [
            'metrics' => [
                'users' => count($users),
                'providers' => count($providers),
                'verified_providers' => count($verifiedProviders),
                'not_ready_providers' => count($notReadyProviders),
                'active_services' => count($activeServices),
                'requests' => count($requests),
                'queue_requests' => count($queueRequests),
                'assigned_requests' => count($assignedRequests),
                'in_progress_requests' => count($inProgressRequests),
                'payments' => count($payments),
                'completed_revenue' => array_sum(array_map(fn ($payment) => (float) ($payment['amount'] ?? 0), $completedPayments)),
                'failed_payments' => count($failedPayments),
            ],
            'statusBreakdown' => $statusBreakdown,
            'recentRequests' => array_slice($requests, 0, 6),
            'recentPayments' => array_slice($payments, 0, 6),
            'apiErrors' => $apiErrors,
            'statusBarChart' => $statusBarChart,
            'operationalDoughnut' => $operationalDoughnut,
        ]);
    }
}
