<?php

namespace Tests\Feature;

use App\Models\Store;
use Tests\Fake\Shopify;
use Domain\Bargain\Entities\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Clients\Shopify\Client as ShopifyClient;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PriceBeatControllerTest extends TestCase
{
    use
    RefreshDatabase,
    WithFaker;
    public function testSendPriceBeatOffer()
    {
        $store = Store::factory()->create();
        $product = Product::factory()->create();
        $fakeVariantResponse = ['variant' => Shopify::variant()];
        $fakeProductResponse = ['product' => Shopify::product()];
        $fakeShopResponse = ['shop' => ['currency' => 'PKR']];
        $fakeIpLocationRes = ['country' => $this->faker()->country(), 'city' => $this->faker()->city()];
        $fakeCurrencyExRes = ['result' => 200, 'success' => true];
        $sourceCode = file_get_contents(base_path('tests/data/correct_jsonld_microdata.html'));
        $shopifyApiVersion = $store->shopifyClient()->getClientVersion();

        Http::fake([
            "*phantomjscloud.com/*" => Http::response($sourceCode),
            "*.myshopify.com/admin/api/{$shopifyApiVersion}/variants*" => Http::response($fakeVariantResponse),
            "*.myshopify.com/admin/api/{$shopifyApiVersion}/products*" => Http::response($fakeProductResponse),
            "*.myshopify.com/admin/api/{$shopifyApiVersion}/shop*" => Http::response($fakeShopResponse),
            '*iplocate.io/*' => Http::response($fakeIpLocationRes),
            "https://api.exchangerate.host/*" => Http::response($fakeCurrencyExRes)
        ]);
        $payload = [
            'name' => $this->faker()->name(),
            'email' => $this->faker()->email(),
            'competitor_url' => $this->faker()->url(),
            'variant_id' => $product->shopify_id
        ];

        

        $response = $this->actingAs($store)->post('price-beat-offer', $payload);
        // dd($response);
        $response->assertOk();
        $this->assertDatabaseHas('customers', ['name' => $payload['name'], 'email' => $payload['email'], 'country' => $fakeIpLocationRes['country']]);
        $this->assertDatabaseHas('price_beat_offers', ['variant_id' => $payload['variant_id']]);
    }

    public function testSendPriceBeatOfferForInvalidProduct()
    {
        $store = Store::factory()->create();
        
        $payload = [
            'name' => $this->faker()->name(),
            'email' => $this->faker()->email(),
            'competitor_url' => $this->faker()->url(),
            'variant_id' => $this->faker()->randomNumber(5)
        ];

        $response = $this->actingAs($store)->post('price-beat-offer', $payload);
        $response->assertSessionHasErrors('variant_id');
    }

    public function testSendPriceBeatOfferUrlCrawlError()
    {
        $store = Store::factory()->create();
        $product = Product::factory()->create();
        $fakeVariantResponse = ['variant' => Shopify::variant()];
        $fakeProductResponse = ['product' => Shopify::product()];
        $fakeShopResponse = ['shop' => ['currency' => 'PKR']];
        $fakeIpLocationRes = ['country' => $this->faker()->country(), 'city' => $this->faker()->city()];
        $fakeCurrencyExRes = ['result' => 200, 'success' => true];
        $sourceCode = file_get_contents(base_path('tests/data/incorrect_jsonld_microdata.html'));
        $shopifyApiVersion = $store->shopifyClient()->getClientVersion();
                
        Http::fake([
            "*phantomjscloud.com/*" => Http::response($sourceCode),
            "*.myshopify.com/admin/api/{$shopifyApiVersion}/variants*" => Http::response($fakeVariantResponse),
            "*.myshopify.com/admin/api/{$shopifyApiVersion}/products*" => Http::response($fakeProductResponse),
            "*.myshopify.com/admin/api/{$shopifyApiVersion}/shop*" => Http::response($fakeShopResponse),
            '*iplocate.io/*' => Http::response($fakeIpLocationRes),
            "https://api.exchangerate.host/*" => Http::response($fakeCurrencyExRes)
        ]);
        $payload = [
            'name' => $this->faker()->name(),
            'email' => $this->faker()->email(),
            'competitor_url' => $this->faker()->url(),
            'variant_id' => $product->shopify_id
        ];

        $response = $this->actingAs($store)->post('price-beat-offer', $payload);

        $response->assertOk();
        $this->assertDatabaseHas('customers', ['name' => $payload['name'], 'email' => $payload['email'], 'country' => $fakeIpLocationRes['country']]);
        $this->assertDatabaseHas('price_beat_offers', ['variant_id' => $payload['variant_id'], 'offered_amount' => null]);
    }
}
