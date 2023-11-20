<?php

namespace App\Clients\ExchangeRate;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;

class Client
{
    private PendingRequest $http;

    public function __construct()
    {
        $baseUrl = "https://api.exchangerate.host";
        $this->http = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->retry(3, 30)->throw()->baseUrl($baseUrl);
    }


    public function convert(string $from, string $to, float $amount): float
    {
        $v = \date('Y-m-d');

        return $this->http->get('/convert?' . \http_build_query(\compact('from', 'to', 'amount', 'v')))
            ->throwIf(fn ($r) => $r->json('success') == false)
            ->json('result');
    }
}
