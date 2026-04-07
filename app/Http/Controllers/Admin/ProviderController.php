<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProviderController extends BaseAdminController
{
    public function index(): View|RedirectResponse
    {
        if ($redirect = $this->redirectIfNotAdmin()) {
            return $redirect;
        }

        $response = $this->api('GET', '/api/v1/admin/providers');

        return view('admin.providers.index', [
            'providers' => $response['ok'] ? $this->toList($response['data']) : [],
            'apiError' => $response['ok'] ? null : $response['message'],
        ]);
    }

    public function show(int $id): View|RedirectResponse
    {
        if ($redirect = $this->redirectIfNotAdmin()) {
            return $redirect;
        }

        $response = $this->api('GET', "/api/v1/admin/providers/{$id}");
        if (!$response['ok']) {
            return redirect()->route('admin.providers.index')->with('error', $response['message']);
        }

        return view('admin.providers.show', [
            'provider' => $this->toItem($response['data']) ?? [],
        ]);
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        if ($redirect = $this->redirectIfNotAdmin()) {
            return $redirect;
        }

        $request->validate([
            'display_name' => ['required', 'string', 'max:255'],
            'provider_kind' => ['nullable', 'string', 'max:100'],
            'status_id' => ['nullable', 'integer'],
            'is_verified' => ['nullable', 'boolean'],
        ]);

        $payload = [
            'display_name' => $request->display_name,
            'provider_kind' => $request->provider_kind,
            'is_verified' => $request->boolean('is_verified'),
        ];

        if ($request->filled('status_id')) {
            $payload['status_id'] = (int) $request->status_id;
        }

        $response = $this->api('PATCH', "/api/v1/admin/providers/{$id}", $payload);

        return $response['ok']
            ? redirect()->route('admin.providers.show', $id)->with('success', $response['message'])
            : back()->withInput()->with('error', $response['message']);
    }
}