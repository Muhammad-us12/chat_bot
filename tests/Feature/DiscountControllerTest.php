<?php

namespace Tests\Feature;

use App\Models\Store;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class DiscountControllerTest extends TestCase
{
    use
    RefreshDatabase,
    WithFaker;

    public function fakeVariant()
    {
        return ['id' => '32222705483865', 'title' => 'RangRover121', 'price' => 230, 'product' => ['id' => '13222270548386512']];
    }

    public function testVariantDiscountStored()
    {
        Http::fake([
            "*variants/*.json" => Http::response(['variant'=> $this->fakeVariant()]),
            
        ]);

        $store = Store::factory()->create(['name' => 'usamaasghar.myshopify.com']);
        $variantId = $this->fakeVariant()['id'];
        $payload = ['discount_percentage' => 5];

        $response = $this->actingAs($store)->post("variant-discount/{$variantId}", $payload);

        $this->assertEquals('Variant Discount has been created', $response['message']);
        $this->assertDatabaseHas('variant_discounts', ['variant_id' => $variantId, 'discount_percentage' => 5]);
    }
}
