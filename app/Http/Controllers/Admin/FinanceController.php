<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class FinanceController extends BaseAdminController
{
    public function index(Request $request): View|RedirectResponse
    {
        if ($redirect = $this->redirectIfNotAdmin()) {
            return $redirect;
        }

        $filters = [
            'status' => $request->query('status'),
            'payment_method' => $request->query('payment_method'),
            'transaction_id' => $request->query('transaction_id'),
        ];

        $paymentsResponse = $this->api('GET', '/api/v1/admin/finance/payments', [], $filters);
        $transactionsResponse = $this->api('GET', '/api/v1/admin/finance/transactions');

        return view('admin.finance.index', [
            'payments' => $paymentsResponse['ok'] ? $this->toList($paymentsResponse['data']) : [],
            'transactions' => $transactionsResponse['ok'] ? $this->toList($transactionsResponse['data']) : [],
            'apiErrors' => array_values(array_filter([
                !$paymentsResponse['ok'] ? 'Pagos: ' . $paymentsResponse['message'] : null,
                !$transactionsResponse['ok'] ? 'Transacciones: ' . $transactionsResponse['message'] : null,
            ])),
            'filters' => $filters,
        ]);
    }

    public function showPayment(int $id): View|RedirectResponse
    {
        if ($redirect = $this->redirectIfNotAdmin()) {
            return $redirect;
        }

        $response = $this->api('GET', "/api/v1/admin/finance/payments/{$id}");

        if (!$response['ok']) {
            return redirect()
                ->route('admin.finance.index')
                ->with('error', $response['message']);
        }

        return view('admin.finance.show-payment', [
            'payment' => $this->toItem($response['data']) ?? [],
        ]);
    }

    public function updatePayment(Request $request, int $id): RedirectResponse
    {
        if ($redirect = $this->redirectIfNotAdmin()) {
            return $redirect;
        }

        $request->validate([
            'payment_method' => ['required', 'string', 'max:50'],
            'reference' => ['nullable', 'string', 'max:120'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'transaction_id' => ['nullable', 'string', 'max:255'],
            'status' => [
                'required',
                'string',
                Rule::in(['pending', 'pending_validation', 'completed', 'failed', 'rejected']),
            ],
        ]);

        $response = $this->api('PATCH', "/api/v1/admin/finance/payments/{$id}", [
            'payment_method' => $request->payment_method,
            'reference' => $request->reference,
            'notes' => $request->notes,
            'transaction_id' => $request->transaction_id,
            'status' => $request->status,
        ]);

        return $response['ok']
            ? redirect()
                ->route('admin.finance.show-payment', $id)
                ->with('success', $response['message'])
            : back()
                ->withInput()
                ->with('error', $response['message']);
    }
}
