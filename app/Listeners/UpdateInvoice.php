<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Xeviant\LaravelPaystack\Model\PaystackEvent;

class UpdateInvoice
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  PaystackEvent  $event
     * @return bool
     */
    public function handle(PaystackEvent $event)
    {
        $paystackPayload = $event->payload;
        dump($paystackPayload);
        // Do something
    }
}
