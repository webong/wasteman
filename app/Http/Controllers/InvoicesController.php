<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Invoice;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class InvoicesController extends Controller
{
    public function index()
    {
        return Inertia::render('Invoices/Index', [
            'filters' => Request::all('search', 'trashed'),
            'invoices' => Auth::user()->account->invoices()
                ->with('customer')
                ->orderByName()
                ->filter(Request::only('search', 'trashed'))
                ->paginate()
                ->transform(function ($invoice) {
                    return [
                        'id' => $invoice->id,
                        'customer' => $invoice->customer ? $invoice->customer->only('name') : null,
                        'paystack_invoice_id' => $invoice->paystack_invoice_id,
                        'due_date' => Carbon::parse($invoice->due_date)->diffForHumans(),
                        'amount' => $invoice->amount,
                        'deleted_at' => $invoice->deleted_at,
                    ];
                }),
        ]);
    }

    public function create()
    {
        return Inertia::render('Invoices/Create', [
            'customers' => Auth::user()->account
                ->customers()
                ->orderBy('name')
                ->get()
                ->map
                ->only('id', 'name'),
        ]);
    }

    public function store()
    {
        $invoiceRequest = Request::validate([
            'amount' => ['required', 'numeric'],
            'currency' => ['required'],
            'due_date' => ['required', 'date', 'after_or_equal:today'],
            'description' => ['nullable', 'max:250'],
            'customer_id' => ['required', Rule::exists('customers', 'id')->where(function ($query) {
                $query->where('account_id', Auth::user()->account_id);
            })],
        ]);

        $customer = Customer::findorFail($invoiceRequest['customer_id']);

        $paystack = app()->make('paystack.connection');

        $paystackResponse = $paystack->invoices()->create([
            'customer' => $customer->paystack_customer_code,
            'amount' => intval($invoiceRequest['amount'] * 100),
            'description' => $invoiceRequest['description'],
            'due_date' => Carbon::parse($invoiceRequest['due_date'])->toIso8601String(),
        ]);
        
        $invoiceRequest['paid'] = $paystackResponse['paid'];
        $invoiceRequest['status'] = $paystackResponse['status'];
        $invoiceRequest['paystack_invoice_id'] = $paystackResponse['id'];
        $invoiceRequest['paystack_invoice_code'] = $paystackResponse['request_code'];

        Auth::user()->account->invoices()->create(
            $invoiceRequest
        );

        return Redirect::route('invoices')->with('success', 'Invoice created.');
    }

    public function edit(Invoice $invoice)
    {
        return Inertia::render('Invoices/Edit', [
            'invoice' => [
                'id' => $invoice->id,
                'due_date' => $invoice->due_date,
                'amount' => $invoice->amount,
                'description' => $invoice->description,
                'currency' => $invoice->currency,
                'paystack_invoice_id' => $invoice->paystack_invoice_id ?? 509384,
                'customer_id' => $invoice->customer_id,
                'customer_name' => $invoice->customer->name,
                'deleted_at' => $invoice->deleted_at,
            ],
            'customers' => Auth::user()->account->customers()
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
                'amount' => ['required', 'numeric'],
                'currency' => ['required'],
                'due_date' => ['required', 'date', 'after_or_equal:today'],
                'description' => ['nullable', 'max:250'],
                'customer_id' => ['required', Rule::exists('customers', 'id')->where(function ($query) {
                    $query->where('account_id', Auth::user()->account_id);
                })],
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
