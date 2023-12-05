<?php

namespace Tests\Feature;

use App\Models\Store;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use
        RefreshDatabase,
        WithFaker;

    public function testGetProductList()
    {
        $store = Store::factory(['name'=>'usamaasgharstore.myshopify.com',
        'access_token'=>'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'])->create();
        $response = $this->actingAs($store)->get('product-list');
        $responseData = $response->json();

        $this->assertArrayHasKey('data', $responseData);
        $this->assertArrayHasKey('products', $responseData['data']);
       
    }
}
