<?php

namespace Tests;

use Firebase\JWT\JWT;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Contracts\Auth\Authenticatable as StoreContract;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function actingAs(StoreContract $store, $guard = null)
    {
        // Only moking is AccessTokenWorking
        Http::fake(function (Request $request) use ($store) {
            if (\strpos($request->body(), 'shop') !== false && \strpos($request->body(), 'name') !== false) {
                return Http::response([
                    'data' => ['shop' => ['name' => $store->name]]
                ], 200);
            }
        });

        return $this->be($store, $guard)
            ->withToken(JWT::encode(['dest' => "https://{$store->name}/admin"], \config('services.shopify.secret'), 'HS256'));
    }
}
