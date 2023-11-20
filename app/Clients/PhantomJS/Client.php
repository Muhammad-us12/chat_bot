<?php

namespace App\Clients\PhantomJS;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;

class Client
{
    private PendingRequest $httpClient;

    public function __construct()
    {
        $phanomJsSecretKey = config('services.phantomjs.secret');
        $this->httpClient = Http::baseUrl("https://phantomjscloud.com/api/browser/v2/{$phanomJsSecretKey}/");
    }

    public function crawl(string $url, string $renderType = 'html'): string
    {
        $queryString = urlencode(json_encode(compact('url', 'renderType')));
        $response = $this->httpClient->get("?request={$queryString}");

        return $response->body();
    }
}
