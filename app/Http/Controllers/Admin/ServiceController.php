<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ServiceController extends Controller
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = rtrim((string) env('URL_BASE_API'), '/');
    }

    public function index()
    {
        if (!$this->isAdmin()) {
            return redirect()->route('login');
        }

        if (empty($this->baseUrl)) {
            return view('admin.services.index', [
                'services' => [],
                'apiError' => 'La variable URL_BASE_API no está configurada.',
            ]);
        }

        try {
            $response = Http::acceptJson()
                ->get($this->baseUrl . '/api/v1/services');

            if (!$response->successful()) {
                return view('admin.services.index', [
                    'services' => [],
                    'apiError' => $this->extractErrorMessage($response, 'No se pudieron obtener los servicios.'),
                ]);
            }

            return view('admin.services.index', [
                'services' => $this->extractServices($response),
                'apiError' => null,
            ]);
        } catch (\Throwable $e) {
            return view('admin.services.index', [
                'services' => [],
                'apiError' => 'Error al conectar con la API: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Base futura para ver detalle de un servicio.
     */

    /*
    public function show(int $id)
    {
        if (!$this->isAdmin()) {
            return redirect()->route('login');
        }

        if (empty($this->baseUrl)) {
            return redirect()
                ->route('admin.servicios.index')
                ->with('error', 'La variable URL_BASE_API no está configurada.');
        }

        try {
            $response = Http::acceptJson()
                ->get($this->baseUrl . '/api/v1/services/' . $id);

            if (!$response->successful()) {
                return redirect()
                    ->route('admin.servicios.index')
                    ->with('error', $this->extractErrorMessage($response, 'No se pudo obtener el servicio.'));
            }

            $service = $response->json('data.service')
                ?? $response->json('data')
                ?? [];

            return view('admin.services.show', [
                'service' => $service,
                'apiError' => null,
            ]);
        } catch (\Throwable $e) {
            return redirect()
                ->route('admin.servicios.index')
                ->with('error', 'Error al conectar con la API: ' . $e->getMessage());
        }
    }
    
    Base futura para formulario de edición.
    
    public function edit(int $id)
    {
        if (!$this->isAdmin()) {
            return redirect()->route('login');
        }

        if (empty($this->baseUrl)) {
            return redirect()
                ->route('admin.servicios.index')
                ->with('error', 'La variable URL_BASE_API no está configurada.');
        }

        try {
            $response = Http::acceptJson()
                ->get($this->baseUrl . '/api/v1/services/' . $id);

            if (!$response->successful()) {
                return redirect()
                    ->route('admin.servicios.index')
                    ->with('error', $this->extractErrorMessage($response, 'No se pudo obtener el servicio para edición.'));
            }

            $service = $response->json('data.service')
                ?? $response->json('data')
                ?? [];

            return view('admin.services.edit', [
                'service' => $service,
                'apiError' => null,
            ]);
        } catch (\Throwable $e) {
            return redirect()
                ->route('admin.servicios.index')
                ->with('error', 'Error al conectar con la API: ' . $e->getMessage());
        }
    }

    Base futura para crear servicios.
    Déjala comentada en rutas hasta que exista el endpoint real.
    
    public function store(Request $request)
    {
        if (!$this->isAdmin()) {
            return redirect()->route('login');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if (empty($this->baseUrl)) {
            return redirect()
                ->route('admin.servicios.index')
                ->with('error', 'La variable URL_BASE_API no está configurada.');
        }

        try {
            $response = Http::acceptJson()
                ->post($this->baseUrl . '/api/v1/services', [
                    'name' => $request->name,
                    'is_active' => $request->boolean('is_active'),
                ]);

            if (!$response->successful()) {
                return redirect()
                    ->route('admin.servicios.index')
                    ->with('error', $this->extractErrorMessage($response, 'No se pudo crear el servicio.'));
            }

            return redirect()
                ->route('admin.servicios.index')
                ->with('success', 'Servicio creado correctamente.');
        } catch (\Throwable $e) {
            return redirect()
                ->route('admin.servicios.index')
                ->with('error', 'Error al conectar con la API: ' . $e->getMessage());
        }
    }

    Base futura para actualizar servicios.
    Ajusta el método HTTP cuando tu API lo defina.
    
    public function update(Request $request, int $id)
    {
        if (!$this->isAdmin()) {
            return redirect()->route('login');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if (empty($this->baseUrl)) {
            return redirect()
                ->route('admin.servicios.index')
                ->with('error', 'La variable URL_BASE_API no está configurada.');
        }

        try {
            $response = Http::acceptJson()
                ->put($this->baseUrl . '/api/v1/services/' . $id, [
                    'name' => $request->name,
                    'is_active' => $request->boolean('is_active'),
                ]);

            if (!$response->successful()) {
                return redirect()
                    ->route('admin.servicios.index')
                    ->with('error', $this->extractErrorMessage($response, 'No se pudo actualizar el servicio.'));
            }

            return redirect()
                ->route('admin.servicios.index')
                ->with('success', 'Servicio actualizado correctamente.');
        } catch (\Throwable $e) {
            return redirect()
                ->route('admin.servicios.index')
                ->with('error', 'Error al conectar con la API: ' . $e->getMessage());
        }
    }

    Base futura para eliminar o desactivar servicios.

    public function destroy(int $id)
    {
        if (!$this->isAdmin()) {
            return redirect()->route('login');
        }

        if (empty($this->baseUrl)) {
            return redirect()
                ->route('admin.servicios.index')
                ->with('error', 'La variable URL_BASE_API no está configurada.');
        }

        try {
            $response = Http::acceptJson()
                ->delete($this->baseUrl . '/api/v1/services/' . $id);

            if (!$response->successful()) {
                return redirect()
                    ->route('admin.servicios.index')
                    ->with('error', $this->extractErrorMessage($response, 'No se pudo eliminar el servicio.'));
            }

            return redirect()
                ->route('admin.servicios.index')
                ->with('success', 'Servicio eliminado correctamente.');
        } catch (\Throwable $e) {
            return redirect()
                ->route('admin.servicios.index')
                ->with('error', 'Error al conectar con la API: ' . $e->getMessage());
        }
    }

     Verifica si el usuario autenticado en sesión es administrador.
    */
    protected function isAdmin(): bool
    {
        return session()->has('user')
            && (session('user')['role'] ?? null) === 'admin';
    }

    /**
     * Normaliza distintos formatos de respuesta posibles del endpoint.
     */
    protected function extractServices(Response $response): array
    {
        $data = $response->json('data');

        if (is_array($data)) {
            if (isset($data['services']) && is_array($data['services'])) {
                return $data['services'];
            }

            if (isset($data['data']) && is_array($data['data'])) {
                return $data['data'];
            }

            if (array_is_list($data)) {
                return $data;
            }
        }

        return [];
    }

    /**
     * Extrae un mensaje de error utilizable desde la API.
     */
    protected function extractErrorMessage(Response $response, string $defaultMessage): string
    {
        return $response->json('message')
            ?? $response->json('error')
            ?? $defaultMessage;
    }
}