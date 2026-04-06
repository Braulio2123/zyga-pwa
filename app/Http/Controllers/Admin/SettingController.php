<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SettingController extends BaseAdminController
{
    public function index(): View|RedirectResponse
    {
        if ($redirect = $this->redirectIfNotAdmin()) {
            return $redirect;
        }

        $profileResponse = $this->api('GET', '/api/v1/me');

        return view('admin.settings.index', [
            'sessionUser' => session('user', []),
            'apiBaseUrl' => $this->baseUrl,
            'apiStatus' => $profileResponse['ok'] ? 'Conectada' : 'Con incidencias',
            'apiMessage' => $profileResponse['message'],
            'profile' => $profileResponse['ok'] ? ($profileResponse['data']['user'] ?? $profileResponse['data'] ?? []) : [],
        ]);
    }
}