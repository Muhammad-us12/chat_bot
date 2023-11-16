<?php

namespace Tests\Feature\Product;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Domain\Bargain\Entities\ProductGroup;
use App\Models\Store;
use Domain\Bargain\Enums\ProductGroupType;
use Tests\TestCase;

class ProductGroupControllerTest extends TestCase
{
    use RefreshDatabase;

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
}
