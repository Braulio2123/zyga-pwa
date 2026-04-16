<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends BaseAdminController
{
    public function index(Request $request): View|RedirectResponse
    {
        if ($redirect = $this->redirectIfNotAdmin()) {
            return $redirect;
        }

        $filters = [
            'email' => $request->query('email'),
            'role' => $request->query('role'),
        ];

        $response = $this->api('GET', '/api/v1/admin/users', [], $filters);
        $users = $response['ok'] ? $this->toList($response['data']) : [];

        return view('admin.users.index', [
            'users' => $users,
            'apiError' => $response['ok'] ? null : $response['message'],
            'filters' => $filters,
        ]);
    }

    public function show(int|string $id): View|RedirectResponse
    {
        if ($redirect = $this->redirectIfNotAdmin()) {
            return $redirect;
        }

        $response = $this->api('GET', "/api/v1/admin/users/{$id}");
        if (!$response['ok']) {
            return redirect()->route('admin.users.index')->with('error', $response['message']);
        }

        return view('admin.users.show', [
            'userData' => $this->toItem($response['data']) ?? [],
        ]);
    }

    public function edit(int|string $id): View|RedirectResponse
    {
        if ($redirect = $this->redirectIfNotAdmin()) {
            return $redirect;
        }

        $response = $this->api('GET', "/api/v1/admin/users/{$id}");
        if (!$response['ok']) {
            return redirect()->route('admin.users.index')->with('error', $response['message']);
        }

        return view('admin.users.edit', [
            'userData' => $this->toItem($response['data']) ?? [],
        ]);
    }

    public function updateEmail(Request $request, int|string $id): RedirectResponse
    {
        if ($redirect = $this->redirectIfNotAdmin()) {
            return $redirect;
        }

        $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        $response = $this->api('PATCH', "/api/v1/admin/users/{$id}", [
            'email' => $request->email,
        ]);

        return $response['ok']
            ? redirect()->route('admin.users.show', $id)->with('success', $response['message'])
            : back()->withInput()->with('error', $response['message']);
    }

    public function updatePassword(Request $request, int|string $id): RedirectResponse
    {
        if ($redirect = $this->redirectIfNotAdmin()) {
            return $redirect;
        }

        $request->validate([
            'password' => ['required', 'string', 'min:8', 'max:255', 'confirmed'],
        ]);

        $response = $this->api('PATCH', "/api/v1/admin/users/{$id}", [
            'password' => $request->password,
        ]);

        return $response['ok']
            ? redirect()->route('admin.users.show', $id)->with('success', $response['message'])
            : back()->with('error', $response['message']);
    }

    public function create(): View|RedirectResponse
    {
        if ($redirect = $this->redirectIfNotAdmin()) {
            return $redirect;
        }

        return view('admin.users.create');
    }

    public function adminStore(Request $request): RedirectResponse
    {
        if ($redirect = $this->redirectIfNotAdmin()) {
            return $redirect;
        }

        $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'max:255', 'confirmed'],
        ]);

        $payload = [
            'email' => strtolower(trim($request->email)),
            'password' => $request->password,
            'role' => 'admin',
        ];

        $response = $this->api('POST', '/api/v1/auth/register', $payload);

        return $response['ok']
            ? redirect()->route('admin.users.index')->with('success', $response['message'] ?: 'Administrador creado correctamente.')
            : back()->withInput()->with('error', $response['message']);
    }
}