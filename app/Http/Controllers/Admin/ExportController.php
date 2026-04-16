<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class ExportController extends BaseAdminController
{
    public function usersExcel(Request $request): Response|RedirectResponse
    {
        if ($redirect = $this->redirectIfNotAdmin()) {
            return $redirect;
        }

        return $this->downloadFromApi('/api/v1/admin/exportaciones/users/excel', $request->only([
            'email',
            'role',
        ]));
    }

    public function usersPdf(Request $request): Response|RedirectResponse
    {
        if ($redirect = $this->redirectIfNotAdmin()) {
            return $redirect;
        }

        return $this->downloadFromApi('/api/v1/admin/exportaciones/users/pdf', $request->only([
            'email',
            'role',
        ]));
    }

    public function providersExcel(Request $request): Response|RedirectResponse
    {
        if ($redirect = $this->redirectIfNotAdmin()) {
            return $redirect;
        }

        return $this->downloadFromApi('/api/v1/admin/exportaciones/providers/excel', $request->only([
            'status_id',
            'is_verified',
            'email',
            'service_id',
        ]));
    }

    public function providersPdf(Request $request): Response|RedirectResponse
    {
        if ($redirect = $this->redirectIfNotAdmin()) {
            return $redirect;
        }

        return $this->downloadFromApi('/api/v1/admin/exportaciones/providers/pdf', $request->only([
            'status_id',
            'is_verified',
            'email',
            'service_id',
        ]));
    }

    public function assistanceRequestsExcel(Request $request): Response|RedirectResponse
    {
        if ($redirect = $this->redirectIfNotAdmin()) {
            return $redirect;
        }

        return $this->downloadFromApi('/api/v1/admin/exportaciones/assistance-requests/excel', $request->only([
            'status',
            'user_id',
            'provider_id',
            'service_id',
            'public_id',
            'date_from',
            'date_to',
        ]));
    }

    public function assistanceRequestsPdf(Request $request): Response|RedirectResponse
    {
        if ($redirect = $this->redirectIfNotAdmin()) {
            return $redirect;
        }

        return $this->downloadFromApi('/api/v1/admin/exportaciones/assistance-requests/pdf', $request->only([
            'status',
            'user_id',
            'provider_id',
            'service_id',
            'public_id',
            'date_from',
            'date_to',
        ]));
    }

    public function paymentsExcel(Request $request): Response|RedirectResponse
    {
        if ($redirect = $this->redirectIfNotAdmin()) {
            return $redirect;
        }

        return $this->downloadFromApi('/api/v1/admin/exportaciones/payments/excel', $request->only([
            'assistance_request_id',
            'status',
            'payment_method',
            'transaction_id',
            'date_from',
            'date_to',
        ]));
    }

    public function paymentsPdf(Request $request): Response|RedirectResponse
    {
        if ($redirect = $this->redirectIfNotAdmin()) {
            return $redirect;
        }

        return $this->downloadFromApi('/api/v1/admin/exportaciones/payments/pdf', $request->only([
            'assistance_request_id',
            'status',
            'payment_method',
            'transaction_id',
            'date_from',
            'date_to',
        ]));
    }

    protected function downloadFromApi(string $endpoint, array $query = []): Response|RedirectResponse
    {
        $token = session('api_token');

        if (!$token) {
            return redirect()
                ->route('login')
                ->with('error', 'Tu sesión expiró. Inicia sesión nuevamente.');
        }

        if (empty($this->baseUrl)) {
            return back()->with('error', 'La URL de la API no está configurada.');
        }

        try {
            $url = $this->baseUrl . $endpoint;

            $query = array_filter($query, function ($value) {
                return $value !== null && $value !== '';
            });

            $response = Http::withToken($token)
                ->accept('*/*')
                ->timeout(120)
                ->get($url, $query);

            if (!$response->successful()) {
                $json = $response->json();
                $message = $json['message'] ?? 'No se pudo generar la exportación solicitada.';

                return back()->with('error', $message);
            }

            $contentType = $response->header('Content-Type', 'application/octet-stream');
            $contentDisposition = $response->header('Content-Disposition', '');
            $filename = $this->extractFilename($contentDisposition);

            $headers = [
                'Content-Type' => $contentType,
            ];

            if ($contentDisposition) {
                $headers['Content-Disposition'] = $contentDisposition;
            } elseif ($filename) {
                $headers['Content-Disposition'] = 'attachment; filename="' . $filename . '"';
            }

            return response($response->body(), 200, $headers);
        } catch (\Throwable $e) {
            return back()->with('error', 'No se pudo descargar el archivo: ' . $e->getMessage());
        }
    }

    protected function extractFilename(string $contentDisposition): ?string
    {
        if (!$contentDisposition) {
            return null;
        }

        if (preg_match("/filename\\*=UTF-8''([^;]+)/i", $contentDisposition, $matches)) {
            return rawurldecode($matches[1]);
        }

        if (preg_match('/filename="?([^";]+)"?/i', $contentDisposition, $matches)) {
            return $matches[1];
        }

        return null;
    }
}