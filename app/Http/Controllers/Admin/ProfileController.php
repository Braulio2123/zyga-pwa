<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends BaseAdminController
{
    public function index(): View|RedirectResponse
    {
        if ($redirect = $this->redirectIfNotAdmin()) {
            return $redirect;
        }

        $response = $this->api('GET', '/api/v1/me');

        return view('admin.profile.index', [
            'profile' => $response['ok'] ? ($response['data']['user'] ?? $response['data'] ?? session('user', [])) : session('user', []),
            'apiError' => $response['ok'] ? null : $response['message'],
        ]);
    }

    public function updateEmail(Request $request): RedirectResponse
    {
        if ($redirect = $this->redirectIfNotAdmin()) {
            return $redirect;
        }

        $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        $response = $this->api('PUT', '/api/v1/me', [
            'email' => $request->email,
        ]);

        if ($response['ok']) {
            $user = session('user', []);
            $user['email'] = $request->email;
            session(['user' => $user]);
        }

        return $response['ok']
            ? redirect()->route('admin.profile.index')->with('success', $response['message'])
            : back()->withInput()->with('error', $response['message']);
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        if ($redirect = $this->redirectIfNotAdmin()) {
            return $redirect;
        }

        $request->validate([
            'password' => ['required', 'string', 'min:8', 'max:255', 'confirmed'],
        ]);

        $response = $this->api('PATCH', '/api/v1/me', [
            'password' => $request->password,
        ]);

        return $response['ok']
            ? redirect()->route('admin.profile.index')->with('success', $response['message'])
            : back()->with('error', $response['message']);
    }
}