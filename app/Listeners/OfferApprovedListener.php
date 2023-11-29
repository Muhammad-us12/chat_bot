<?php

namespace App\Listeners;

use App\Events\OfferApprovedEvent;
use Illuminate\Support\Facades\Mail;

class OfferApprovedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */

    /**
     * Handle the event.
     *
     * @param OfferApprovedEvent $event
     * @return void
     */
    public function handle(OfferApprovedEvent $event)
    {
        Mail::to($event->email)->send(new \App\Mail\OfferApprovedMail($event->code, $event->url));
    }
}
