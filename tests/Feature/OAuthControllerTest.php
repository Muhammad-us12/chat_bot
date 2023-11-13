<?php

namespace Tests\Feature;

use Shopify\Utils;
use Tests\TestCase;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Queue;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OAuthControllerTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function testBeginAuthForAppInsideIframe(): void
    {
        $payload = [
            'shop' => 'https://' . $this->faker()->word() . '.myshopify.com',
            'embedded' => 1,
            'host' => $this->faker()->uuid(),
        ];
        $response = $this->get('/auth/begin?' . \http_build_query($payload));

        $payload['redirectUri'] = urlencode(\route('auth.begin', ['shop' => Utils::sanitizeShopDomain($payload['shop'])]));
        $response->assertRedirect('/ExitIframe?' . \http_build_query($payload));
    }

    public function testBeginAuthForAppOutsideIframe(): void
    {
        $payload = [
            'shop' => 'https://' . $this->faker()->word() . '.myshopify.com',
        ];

        $response = $this->get('/auth/begin?' . \http_build_query($payload));

        $response->assertRedirect();
        $redirectTargetUrl = \parse_url($response->baseResponse->getTargetUrl());

        $this->assertStringContainsString(
            'https://' . Utils::sanitizeShopDomain($payload['shop']) . '/admin/oauth/authorize',
            $redirectTargetUrl['scheme'] . '://' . $redirectTargetUrl['host'] . $redirectTargetUrl['path']
        );
    }

    public function testOauthCallback()
    {
        Queue::fake();
        $payload = [
            'shop' => 'https://' . $this->faker()->word() . '.myshopify.com',
            'code' => $this->faker()->uuid(),
            'host' => $this->faker()->uuid()
        ];
        $state = Str::uuid()->toString();
        Cache::put(Utils::sanitizeShopDomain($payload['shop']) . '_state', $state, 300);
        $payload['state'] = $state;
        $payload['hmac'] = hash_hmac('sha256', http_build_query($payload), \config('services.shopify.secret'));
        
        Http::fake([
            "*" => Http::response(['access_token' => $this->faker()->uuid(), 'scope' => \config('services.shopify.scopes')]),
        ]);

        $response = $this->get('/auth/callback/?' . \http_build_query($payload));

        $response->assertRedirect();
        $this->assertDatabaseHas('stores', ['name' => Utils::sanitizeShopDomain($payload['shop'])]);
    }
}
