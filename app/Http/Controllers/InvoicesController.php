<?php

namespace App\Http\Controllers;

use App\Invoice;
use Inertia\Inertia;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;

class InvoicesController extends Controller
{
    public function index()
    {
        return Inertia::render('Invoices/Index', [
            'filters' => Request::all('search', 'trashed'),
            'invoices' => Auth::user()->account->invoices()
                ->with('organization')
                ->orderByName()
                ->filter(Request::only('search', 'trashed'))
                ->paginate()
                ->transform(function ($invoice) {
                    return [
                        'id' => $invoice->id,
                        'name' => $invoice->name,
                        'phone' => $invoice->phone,
                        'city' => $invoice->city,
                        'deleted_at' => $invoice->deleted_at,
                        'organization' => $invoice->organization ? $invoice->organization->only('name') : null,
                    ];
                }),
        ]);
    }

    public function create()
    {
        return Inertia::render('Invoices/Create', [
            'organizations' => Auth::user()->account
                ->organizations()
                ->orderBy('name')
                ->get()
                ->map
                ->only('id', 'name'),
        ]);
    }

    public function store()
    {
        Auth::user()->account->invoices()->create(
            Request::validate([
                'first_name' => ['required', 'max:50'],
                'last_name' => ['required', 'max:50'],
                'organization_id' => ['nullable', Rule::exists('organizations', 'id')->where(function ($query) {
                    $query->where('account_id', Auth::user()->account_id);
                })],
                'email' => ['nullable', 'max:50', 'email'],
                'phone' => ['nullable', 'max:50'],
                'address' => ['nullable', 'max:150'],
                'city' => ['nullable', 'max:50'],
                'region' => ['nullable', 'max:50'],
                'country' => ['nullable', 'max:2'],
                'postal_code' => ['nullable', 'max:25'],
            ])
        );

        return Redirect::route('invoices')->with('success', 'Invoice created.');
    }

    public function edit(Invoice $invoice)
    {
        return Inertia::render('Invoices/Edit', [
            'invoice' => [
                'id' => $invoice->id,
                'first_name' => $invoice->first_name,
                'last_name' => $invoice->last_name,
                'organization_id' => $invoice->organization_id,
                'email' => $invoice->email,
                'phone' => $invoice->phone,
                'address' => $invoice->address,
                'city' => $invoice->city,
                'region' => $invoice->region,
                'country' => $invoice->country,
                'postal_code' => $invoice->postal_code,
                'deleted_at' => $invoice->deleted_at,
            ],
            'organizations' => Auth::user()->account->organizations()
                ->orderBy('name')
                ->get()
                ->map
                ->only('id', 'name'),
        ]);
    }

    public function update(Invoice $invoice)
    {
        $invoice->update(
            Request::validate([
                'first_name' => ['required', 'max:50'],
                'last_name' => ['required', 'max:50'],
                'organization_id' => ['nullable', Rule::exists('organizations', 'id')->where(function ($query) {
                    $query->where('account_id', Auth::user()->account_id);
                })],
                'email' => ['nullable', 'max:50', 'email'],
                'phone' => ['nullable', 'max:50'],
                'address' => ['nullable', 'max:150'],
                'city' => ['nullable', 'max:50'],
                'region' => ['nullable', 'max:50'],
                'country' => ['nullable', 'max:2'],
                'postal_code' => ['nullable', 'max:25'],
            ])
        );

        return Redirect::back()->with('success', 'Invoice updated.');
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();

        return Redirect::back()->with('success', 'Invoice deleted.');
    }

    public function restore(Invoice $invoice)
    {
        $invoice->restore();

        return Redirect::back()->with('success', 'Invoice restored.');
    }
}
