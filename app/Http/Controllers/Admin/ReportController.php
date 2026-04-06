<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ReportController extends BaseAdminController
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
        $auditResponse = $this->api('GET', '/api/v1/admin/audit-logs');

        $users = $this->toList($usersResponse['data']);
        $providers = $this->toList($providersResponse['data']);
        $requests = $this->toList($requestsResponse['data']);
        $payments = $this->toList($paymentsResponse['data']);
        $audits = $this->toList($auditResponse['data']);

        $statusCounts = [];
        foreach ($requests as $request) {
            $status = $request['status'] ?? 'sin_estado';
            $statusCounts[$status] = ($statusCounts[$status] ?? 0) + 1;
        }
        arsort($statusCounts);

        $methodCounts = [];
        foreach ($payments as $payment) {
            $method = $payment['payment_method'] ?? 'sin_metodo';
            $methodCounts[$method] = ($methodCounts[$method] ?? 0) + 1;
        }
        arsort($methodCounts);

        return view('admin.reportes.index', [
            'summary' => [
                'total_users' => count($users),
                'total_providers' => count($providers),
                'total_requests' => count($requests),
                'total_payments' => count($payments),
                'completed_revenue' => array_sum(array_map(fn ($payment) => ($payment['status'] ?? null) === 'completed' ? (float) ($payment['amount'] ?? 0) : 0, $payments)),
                'audit_events' => count($audits),
            ],
            'statusCounts' => $statusCounts,
            'methodCounts' => $methodCounts,
            'latestAudits' => array_slice($audits, 0, 8),
            'apiErrors' => array_values(array_filter([
                !$usersResponse['ok'] ? 'Usuarios: ' . $usersResponse['message'] : null,
                !$providersResponse['ok'] ? 'Proveedores: ' . $providersResponse['message'] : null,
                !$requestsResponse['ok'] ? 'Solicitudes: ' . $requestsResponse['message'] : null,
                !$paymentsResponse['ok'] ? 'Pagos: ' . $paymentsResponse['message'] : null,
                !$auditResponse['ok'] ? 'Auditoría: ' . $auditResponse['message'] : null,
            ])),
        ]);
    }
}