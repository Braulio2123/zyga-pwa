<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class ProviderPortalController extends Controller
{
    private $api;
    private $token;

    public function __construct()
    {
        $this->api = rtrim(env('URL_BASE_API'), '/');
        $this->token = session('api_token');
    }

    private function request($endpoint)
    {
        return Http::withToken($this->token)
            ->acceptJson()
            ->get($this->api . $endpoint)
            ->json();
    }

    public function dashboard()
    {
        return view('provider.dashboard');
    }

    public function perfil()
    {
        $perfil = $this->request('/api/v1/provider/profile');
        return view('provider.perfil.index', compact('perfil'));
    }

    public function servicios()
    {
        $servicios = $this->request('/api/v1/provider/services');
        return view('provider.servicios.index', compact('servicios'));
    }

    public function horarios()
    {
        $horarios = $this->request('/api/v1/provider/schedules');
        return view('provider.horarios.index', compact('horarios'));
    }

    public function documentos()
    {
        $documentos = $this->request('/api/v1/provider/documents');
        return view('provider.documentos.index', compact('documentos'));
    }

    public function asistencias()
    {
        $asistencias = $this->request('/api/v1/provider/assistance-requests/available');
        return view('provider.asistencias.index', compact('asistencias'));
    }
}