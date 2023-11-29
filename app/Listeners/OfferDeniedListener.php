<?php

namespace App\Listeners;

use App\Events\OfferDeniedEvent;
use Illuminate\Support\Facades\Mail;

class OfferDeniedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */

    /**
     * Handle the event.
     *
     * @param OfferDeniedEvent $event
     * @return void
     */
    public function handle(OfferDeniedEvent $event)
    {
        Mail::to($event->email)->send(new \App\Mail\OfferDeniedMail());
    }
}
