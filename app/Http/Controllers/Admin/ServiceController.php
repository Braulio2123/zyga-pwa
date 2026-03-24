<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class ServiceController extends Controller
{
    public function index()
    {
        if (!session('user') || session('user')['role'] !== 'admin') {
            return redirect('/login');
        }

        $baseUrl = rtrim(env('URL_BASE_API', 'http://127.0.0.1:8000'), '/');

        try {
            $response = Http::acceptJson()
                ->get($baseUrl . '/api/v1/services');

            if ($response->successful()) {
                $services = $response->json('data') ?? [];

                return view('admin.servicios.index', compact('services'));
            }

            return back()->with('error', 'No se pudieron obtener los servicios.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Error al conectar con la API.');
        }
    }
}