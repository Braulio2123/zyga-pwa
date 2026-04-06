<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AssistanceController extends BaseAdminController
{
    public function index(Request $request): View|RedirectResponse
    {
        if ($redirect = $this->redirectIfNotAdmin()) {
            return $redirect;
        }

        $filters = [
            'status' => $request->query('status'),
            'public_id' => $request->query('public_id'),
            'user_id' => $request->query('user_id'),
        ];

        $response = $this->api('GET', '/api/v1/admin/assistance-requests', [], $filters);

        return view('admin.assistance.index', [
            'requests' => $response['ok'] ? $this->toList($response['data']) : [],
            'apiError' => $response['ok'] ? null : $response['message'],
            'filters' => $filters,
        ]);
    }

    public function show(int $id): View|RedirectResponse
    {
        if ($redirect = $this->redirectIfNotAdmin()) {
            return $redirect;
        }

        $response = $this->api('GET', "/api/v1/admin/assistance-requests/{$id}");
        if (!$response['ok']) {
            return redirect()->route('admin.assistance.index')->with('error', $response['message']);
        }

        return view('admin.assistance.show', [
            'requestData' => $this->toItem($response['data']) ?? [],
        ]);
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        if ($redirect = $this->redirectIfNotAdmin()) {
            return $redirect;
        }

        $request->validate([
            'provider_id' => ['nullable', 'integer'],
            'status' => ['required', 'string'],
            'cancel_reason' => ['nullable', 'string', 'max:255'],
        ]);

        $payload = [
            'status' => $request->status,
            'cancel_reason' => $request->cancel_reason,
        ];

        if ($request->filled('provider_id')) {
            $payload['provider_id'] = (int) $request->provider_id;
        }

        $response = $this->api('PATCH', "/api/v1/admin/assistance-requests/{$id}", $payload);

        return $response['ok']
            ? redirect()->route('admin.assistance.show', $id)->with('success', $response['message'])
            : back()->withInput()->with('error', $response['message']);
    }
}