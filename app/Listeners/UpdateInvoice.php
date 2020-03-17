<?php

namespace App\Listeners;

use App\Invoice;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Xeviant\LaravelPaystack\Model\PaystackEvent;


class UpdateInvoice
{
    /**
     * Handle the event.
     *
     * @param  PaystackEvent  $event
     * @return bool
     */
    public function handle(PaystackEvent $event)
    {
        if (!empty($event->payload)) {
            $paystackPayload = $event->payload;
        }

        $invoice = Invoice::where('paystack_invoice_id', $paystackPayload->id)->first();
        $invoice->status = $paystackPayload->status;
        $invoice->paid_at = Carbon::parse($paystackPayload->paid_at)->toDateTime();
        $invoice->paid = $paystackPayload->paid;
        $invoice->save();
        dump($invoice);
    }
}
