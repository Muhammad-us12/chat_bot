<?php

namespace App\Events;

use App\Models\Offer;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OfferApprovedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $email;
    public $code;
    public $url;

    public function __construct(string $email, string $code, $url)
    {
        $this->email = $email;
        $this->code = $code;
        $this->url = $url; // URL could be a link to buy the product or leads to the product.
    }
}
