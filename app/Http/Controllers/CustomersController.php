<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Inertia\Inertia;
use App\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
use Xeviant\LaravelPaystack\Paystack;

class CustomersController extends Controller
{
    public function index()
    {
        return Inertia::render('Customers/Index', [
            'filters' => Request::all('search', 'trashed'),
            'customers' => Auth::user()->account->customers()
                ->orderBy('first_name')
                ->orderBy('last_name')
                ->filter(Request::only('search', 'trashed'))
                ->paginate()
                ->only('id', 'name', 'phone', 'city', 'deleted_at'),
        ]);
    }

    public function create()
    {
        return Inertia::render('Customers/Create');
    }

    public function store()
    {
        $customerRequest = Request::validate([
            'first_name' => ['required', 'max:50'],
            'last_name' => ['required', 'max:50'],
            'email' => ['nullable', 'max:50', 'email'],
            'phone' => ['nullable', 'max:50'],
            'address' => ['nullable', 'max:150'],
            'city' => ['nullable', 'max:50'],
            'region' => ['nullable', 'max:50'],
            'country' => ['nullable', 'max:2'],
            'postal_code' => ['nullable', 'max:25'],
        ]);

        $paystack = app()->make('paystack.connection');

        $paystackResponse = $paystack->customers()->create(
            Request::only(['email', 'first_name', 'last_name', 'phone'])
        );

        $customerRequest['paystack_customer_code'] = $paystackResponse['customer_code'];

        Auth::user()->account->customers()->create($customerRequest);

        return Redirect::route('customers')->with('success', 'Customer created.');
    }

    public function edit(Customer $customer)
    {
        return Inertia::render('Customers/Edit', [
            'customer' => [
                'id' => $customer->id,
                'name' => $customer->name,
                'first_name' => $customer->first_name,
                'last_name' => $customer->last_name,
                'email' => $customer->email,
                'phone' => $customer->phone,
                'address' => $customer->address,
                'city' => $customer->city,
                'region' => $customer->region,
                'country' => $customer->country,
                'postal_code' => $customer->postal_code,
                'deleted_at' => $customer->deleted_at,
                'invoices' => $customer->invoices()->get()->transform(function ($invoice) {
                    return [
                        'id' => $invoice->id,
                        'customer' => $invoice->customer ? $invoice->customer->only('name') : null,
                        'amount' => $invoice->amount,
                        'status' => $invoice->status,
                        'due_date' => Carbon::parse($invoice->due_date)->diffForHumans(),
                        'paid_at' => isset($invoice->paid_at) ? Carbon::parse($invoice->paid_at)->diffForHumans() : 'Not Paid',
                        'paystack_invoice_id' => $invoice->paystack_invoice_id,
                        'deleted_at' => $invoice->deleted_at,
                    ];
                }),
            ]
        ]);
    }

    public function update(Customer $customer)
    {
        $customer->update(
            Request::validate([
                'first_name' => ['required', 'max:50'],
                'last_name' => ['required', 'max:50'],
                // 'email' => ['nullable', 'max:50', 'email'],
                'phone' => ['nullable', 'max:50'],
                'address' => ['nullable', 'max:150'],
                'city' => ['nullable', 'max:50'],
                'region' => ['nullable', 'max:50'],
                'country' => ['nullable', 'max:2'],
                'postal_code' => ['nullable', 'max:25'],
            ])
        );

        return Redirect::back()->with('success', 'Customer updated.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return Redirect::back()->with('success', 'Customer deleted.');
    }

    public function restore(Customer $customer)
    {
        $customer->restore();

        return Redirect::back()->with('success', 'Customer restored.');
    }
}
