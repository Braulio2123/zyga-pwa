<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class UserController extends Controller
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = rtrim((string) env('URL_BASE_API'), '/');
    }

    public function index(Request $request): View|RedirectResponse
    {
        if (!session('user') || (session('user')['role'] ?? null) !== 'admin') {
            return redirect()->route('login');
        }

        $response = $this->sendRequest('GET', '/api/v1/admin/users');

        if (!$response['ok']) {
            return view('admin.users.index', [
                'users' => [],
                'apiError' => $response['message'],
            ]);
        }

        $users = $this->extractUsers($response['data']);

        return view('admin.users.index', [
            'users' => $users,
            'apiError' => null,
        ]);
    }

    public function show(int|string $id): View|RedirectResponse
    {
        if (!session('user') || (session('user')['role'] ?? null) !== 'admin') {
            return redirect()->route('login');
        }

        $response = $this->sendRequest('GET', "/api/v1/admin/users/{$id}");

        if (!$response['ok']) {
            return redirect()->route('admin.users.index')
                ->with('error', $response['message']);
        }

        $user = $this->extractSingleUser($response['data']);

        if (!$user) {
            return redirect()->route('admin.users.index')
                ->with('error', 'No se pudo obtener la información del usuario.');
        }

        return view('admin.users.show', [
            'userData' => $user,
        ]);
    }

    public function edit(int|string $id): View|RedirectResponse
    {
        if (!session('user') || (session('user')['role'] ?? null) !== 'admin') {
            return redirect()->route('login');
        }

        $response = $this->sendRequest('GET', "/api/v1/admin/users/{$id}");

        if (!$response['ok']) {
            return redirect()->route('admin.users.index')
                ->with('error', $response['message']);
        }

        $user = $this->extractSingleUser($response['data']);

        if (!$user) {
            return redirect()->route('admin.users.index')
                ->with('error', 'No se pudo obtener la información del usuario.');
        }

        return view('admin.users.edit', [
            'userData' => $user,
        ]);
    }

    public function updateEmail(Request $request, int|string $id): RedirectResponse
    {
        if (!session('user') || (session('user')['role'] ?? null) !== 'admin') {
            return redirect()->route('login');
        }

        $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        $response = $this->sendRequest('PATCH', "/api/v1/admin/users/{$id}", [
            'email' => $request->email,
        ]);

        if (!$response['ok']) {
            return back()
                ->withInput()
                ->with('error', $response['message']);
        }

        return redirect()
            ->route('admin.users.show', $id)
            ->with('success', 'Correo actualizado correctamente.');
    }

    public function updatePassword(Request $request, int|string $id): RedirectResponse
    {
        if (!session('user') || (session('user')['role'] ?? null) !== 'admin') {
            return redirect()->route('login');
        }

        $request->validate([
            'password' => ['required', 'string', 'min:8', 'max:255', 'confirmed'],
        ]);

        $response = $this->sendRequest('PATCH', "/api/v1/admin/users/{$id}", [
            'password' => $request->password,
        ]);

        if (!$response['ok']) {
            return back()
                ->with('error', $response['message']);
        }

        return redirect()
            ->route('admin.users.show', $id)
            ->with('success', 'Contraseña actualizada correctamente.');
    }

    private function sendRequest(string $method, string $endpoint, array $payload = []): array
    {
        $token = session('api_token');

        if (!$token) {
            return [
                'ok' => false,
                'message' => 'No se encontró el token de sesión. Inicia sesión nuevamente.',
                'data' => null,
            ];
        }

        if (!$this->baseUrl) {
            return [
                'ok' => false,
                'message' => 'No está configurada la variable URL_BASE_API en el archivo .env.',
                'data' => null,
            ];
        }

        try {
            $http = Http::acceptJson()
                ->withToken($token);

            $response = match (strtoupper($method)) {
                'GET' => $http->get($this->baseUrl . $endpoint),
                'POST' => $http->post($this->baseUrl . $endpoint, $payload),
                'PATCH' => $http->patch($this->baseUrl . $endpoint, $payload),
                'PUT' => $http->put($this->baseUrl . $endpoint, $payload),
                'DELETE' => $http->delete($this->baseUrl . $endpoint, $payload),
                default => throw new \Exception("Método HTTP no soportado: {$method}"),
            };

            $json = $response->json();

            if ($response->successful()) {
                return [
                    'ok' => true,
                    'message' => $json['message'] ?? 'Operación exitosa.',
                    'data' => $json['data'] ?? $json,
                ];
            }

            $message = $json['message'] ?? 'Ocurrió un error al consumir la API.';

            if (isset($json['errors']) && is_array($json['errors'])) {
                foreach ($json['errors'] as $fieldErrors) {
                    if (is_array($fieldErrors) && count($fieldErrors) > 0) {
                        $message = $fieldErrors[0];
                        break;
                    }
                }
            }

            return [
                'ok' => false,
                'message' => $message,
                'data' => $json,
            ];
        } catch (\Throwable $e) {
            return [
                'ok' => false,
                'message' => 'Error de conexión con la API: ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    private function extractUsers(mixed $data): array
    {
        if (is_array($data)) {
            if ($this->isList($data)) {
                return $data;
            }

            if (isset($data['users']) && is_array($data['users'])) {
                return $data['users'];
            }

            if (isset($data['data']) && is_array($data['data'])) {
                return $data['data'];
            }
        }

        return [];
    }

    private function extractSingleUser(mixed $data): ?array
    {
        if (is_array($data)) {
            if (isset($data['id'])) {
                return $data;
            }

            if (isset($data['user']) && is_array($data['user'])) {
                return $data['user'];
            }

            if ($this->isList($data) && isset($data[0]) && is_array($data[0])) {
                return $data[0];
            }
        }

        return null;
    }

    private function isList(array $array): bool
    {
        return array_keys($array) === range(0, count($array) - 1);
    }
}