<?php

namespace Tests\Feature;

use App\Models\Store;
use Domain\Bargain\Entities\ProductGroup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BargainControllerTest extends TestCase
{
    use 
    RefreshDatabase,
    WithFaker;

    public function testCreateBargain()
    {
        $store = Store::factory()->create();
        $productGroup = ProductGroup::factory()->for($store)->create();

        $payload = [
            'type' => 'percentage',
            'value' => 2,
            'product_group_id' => $productGroup->id,
        ];

        $res = $this->actingAs($store)->post('bargain', $payload);
        $res->assertCreated();
        $this->assertDatabaseHas('bargains', ['value' => 2, 'product_group_id' => $productGroup->id]);
    }

    public function testCreateBargainForFixedAmount()
    {
        $store = Store::factory()->create();
        $productGroup = ProductGroup::factory()->for($store)->create();

        $payload = [
            'type' => 'fixed',
            'value' => 2.2,
            'product_group_id' => $productGroup->id,
        ];

        $res = $this->actingAs($store)->post('bargain', $payload);

        $res->assertCreated();
        $this->assertDatabaseHas('bargains', ['value' => 2.2, 'product_group_id' => $productGroup->id]);
    }
}
