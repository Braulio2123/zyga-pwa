<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServiceController extends BaseAdminController
{
    public function index(): View|RedirectResponse
    {
        if ($redirect = $this->redirectIfNotAdmin()) {
            return $redirect;
        }

        $response = $this->api('GET', '/api/v1/admin/services');

        return view('admin.services.index', [
            'services' => $response['ok'] ? $this->toList($response['data']) : [],
            'apiError' => $response['ok'] ? null : $response['message'],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if ($redirect = $this->redirectIfNotAdmin()) {
            return $redirect;
        }

        $request->validate([
            'code' => ['required', 'string', 'max:100'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $response = $this->api('POST', '/api/v1/admin/services', [
            'code' => $request->code,
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => $request->boolean('is_active'),
        ]);

        return $response['ok']
            ? redirect()->route('admin.services.index')->with('success', $response['message'])
            : back()->withInput()->with('error', $response['message']);
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        if ($redirect = $this->redirectIfNotAdmin()) {
            return $redirect;
        }

        $request->validate([
            'code' => ['required', 'string', 'max:100'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $response = $this->api('PATCH', "/api/v1/admin/services/{$id}", [
            'code' => $request->code,
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => $request->boolean('is_active'),
        ]);

        return $response['ok']
            ? redirect()->route('admin.services.index')->with('success', $response['message'])
            : back()->withInput()->with('error', $response['message']);
    }

    public function destroy(int $id): RedirectResponse
    {
        if ($redirect = $this->redirectIfNotAdmin()) {
            return $redirect;
        }

        $response = $this->api('DELETE', "/api/v1/admin/services/{$id}");

        return $response['ok']
            ? redirect()->route('admin.services.index')->with('success', $response['message'])
            : back()->with('error', $response['message']);
    }
}