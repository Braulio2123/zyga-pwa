<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

abstract class BaseAdminController extends Controller
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = rtrim((string) env('URL_BASE_API'), '/');
    }

    protected function redirectIfNotAdmin(): ?RedirectResponse
    {
        if (!session('user') || (session('user')['role'] ?? null) !== 'admin') {
            return redirect()->route('login');
        }

        return null;
    }

    protected function api(string $method, string $endpoint, array $payload = [], array $query = []): array
    {
        $token = session('api_token');

        if (!$token) {
            return [
                'ok' => false,
                'status' => 401,
                'message' => 'No se encontró el token de sesión. Inicia sesión nuevamente.',
                'data' => null,
                'raw' => null,
            ];
        }

        if (empty($this->baseUrl)) {
            return [
                'ok' => false,
                'status' => 500,
                'message' => 'La variable URL_BASE_API no está configurada.',
                'data' => null,
                'raw' => null,
            ];
        }

        try {
            $http = Http::acceptJson()->withToken($token);
            $url = $this->baseUrl . $endpoint;
            if (!empty($query)) {
                $url .= '?' . http_build_query(array_filter($query, fn ($value) => $value !== null && $value !== ''));
            }

            $response = match (strtoupper($method)) {
                'GET' => $http->get($url),
                'POST' => $http->post($url, $payload),
                'PATCH' => $http->patch($url, $payload),
                'PUT' => $http->put($url, $payload),
                'DELETE' => $http->delete($url, $payload),
                default => throw new \InvalidArgumentException("Método HTTP no soportado: {$method}"),
            };

            $json = $response->json();
            $message = $json['message'] ?? 'Operación procesada.';

            if ($response->successful()) {
                return [
                    'ok' => true,
                    'status' => $response->status(),
                    'message' => $message,
                    'data' => $json['data'] ?? $json,
                    'raw' => $json,
                ];
            }

            if (isset($json['errors']) && is_array($json['errors'])) {
                foreach ($json['errors'] as $fieldErrors) {
                    if (is_array($fieldErrors) && !empty($fieldErrors[0])) {
                        $message = $fieldErrors[0];
                        break;
                    }
                }
            }

            return [
                'ok' => false,
                'status' => $response->status(),
                'message' => $message,
                'data' => $json['data'] ?? $json,
                'raw' => $json,
            ];
        } catch (\Throwable $e) {
            return [
                'ok' => false,
                'status' => 500,
                'message' => 'Error al conectar con la API: ' . $e->getMessage(),
                'data' => null,
                'raw' => null,
            ];
        }
    }

    protected function toList(mixed $data, array $keys = []): array
    {
        if (is_array($data) && $this->isSequential($data)) {
            return $data;
        }

        if (is_array($data)) {
            foreach ($keys as $key) {
                if (isset($data[$key]) && is_array($data[$key])) {
                    return $this->toList($data[$key]);
                }
            }
        }

        return [];
    }

    protected function toItem(mixed $data, array $keys = []): ?array
    {
        if (is_array($data) && isset($data['id'])) {
            return $data;
        }

        if (is_array($data)) {
            foreach ($keys as $key) {
                if (isset($data[$key]) && is_array($data[$key])) {
                    return $this->toItem($data[$key]);
                }
            }
        }

        return is_array($data) ? $data : null;
    }

    protected function isSequential(array $array): bool
    {
        return array_keys($array) === range(0, count($array) - 1);
    }

    protected function roleNames(array $roles): string
    {
        $names = [];
        foreach ($roles as $role) {
            $names[] = $role['name'] ?? $role['code'] ?? 'Sin rol';
        }

        return implode(', ', $names);
    }

    protected function formatMoney(mixed $amount): string
    {
        if ($amount === null || $amount === '') {
            return '—';
        }

        return '$' . number_format((float) $amount, 2);
    }

    protected function formatDate(mixed $date, string $format = 'd/m/Y H:i'): string
    {
        if (empty($date)) {
            return '—';
        }

        try {
            return Carbon::parse($date)->format($format);
        } catch (\Throwable $e) {
            return (string) $date;
        }
    }

    protected function boolText(mixed $value, string $true = 'Sí', string $false = 'No'): string
    {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN) ? $true : $false;
    }
}