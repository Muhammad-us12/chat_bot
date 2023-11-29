<?php

namespace App\Listeners;

use App\Events\OfferReceivedEvent;
use Illuminate\Support\Facades\Mail;

class OfferReceivedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */

    /**
     * Handle the event.
     *
     * @param OfferReceivedEvent $event
     * @return void
     */
    public function handle(OfferReceivedEvent $event)
    {
        Mail::to($event->email)->send(new \App\Mail\OfferReceivedMail($event->status));
    }
}
