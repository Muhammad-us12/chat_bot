<?php

namespace Tests\Feature\Product;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Domain\Bargain\Entities\ProductGroup;
use App\Models\Store;
use Domain\Bargain\Entities\Product;
use Domain\Bargain\Enums\ProductGroupType;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ProductGroupControllerTest extends TestCase
{
    use 
    RefreshDatabase,
    WithFaker;

    public function testCreateCustomProductGroup()
    {
        $store = Store::factory()->create();
        $payload = [
            'type' => ProductGroupType::CUSTOM->value,
            'name' => 'Group 1',
        ];

        $response = $this->actingAs($store)->post('/create-product-group', $payload);

        $response->assertOk();
        $this->assertDatabaseHas(ProductGroup::class, ['store_id' => $store->id, 'type' => $payload['type']]);
    }

    public function testAddProductToAGroup()
    {
        Http::fake([
            "*.myshopify.com/*" => Http::response(['i am response'])
        ]);
        $store = Store::factory()->create();
        
        $productGroup = ProductGroup::factory()->for($store)->create(['type' => 'custom']);
        
        $payload = [
            'shopify_id' => 923874923,
            'name' => $this->faker()->words(3, true)
        ];
        // dd($productGroup->id);
        $response = $this->actingAs($store)->post("add-product/".$productGroup->id."", $payload);
        // dd($response);
        $response->assertOk();
        $this->assertDatabaseHas(Product::class, ['shopify_id' => $payload['shopify_id']]);
    }
}
