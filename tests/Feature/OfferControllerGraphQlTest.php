<?php

namespace Tests\Feature;

use App\Clients\Shopify\Client;
use App\Models\Customer;
use App\Models\Store;
use App\Models\VariantDiscount;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class OfferControllerGraphQlTest extends TestCase
{
    use
    RefreshDatabase,
    WithFaker;

    public function fakeProduct()
    {
        return ['id' => 'gid://shopify/Product/7847013154973', 'title' => 'RangRover', 'vendor' => 'robustweb', 'variants' => ['edges' => [['node' => ['id' => 'gid://shopify/ProductVariant/44091188215965', 'price' => 230, 'displayName' => 'Rang Rover']]]], 'tags' => ['OLA-Product']];
    }

    public function fakeVariant()
    {
        return ['id' => 'gid://shopify/ProductVariant/44091188215965', 'title' => 'Default Title', 'price' => 230, 'product' => ['id' => 'gid://shopify/Product/7847013154973']];
    }

    public function fakeCustomer()
    {
        return ['id' => 'gid//customer/123'];
    }



    public function DiscountCodeResponse(){
        return [
            "id" => 1054381139,
            "price_rule_id" => 996606715,
            "code" => "SUMMERSALE10OFF",
            "usage_count" => 0,
            "created_at" => "2023-10-03T13:22:41-04:00",
            "updated_at" => "2023-10-03T13:22:41-04:00"
        ];
    }

    public function testUserCreateNewOfferOnProductGraphQl()
    {
        
        $this->partialMock(Client::class, function ($mock) {
            // $mock->shouldReceive('createACustomer')->andReturn($this->fakeCustomer());
            $mock->shouldReceive('getProductWithGraphQl')->andReturn($this->fakeProduct());
            $mock->shouldReceive('getVariantWithGraphQl')->andReturn($this->fakeVariant());
        });
       

        VariantDiscount::create([
            'variant_id' => '44091188215965',
            'discount_percentage' => 5
        ]);

        $product = $this->fakeProduct();
        $store = Store::factory()->create(['name' => 'usamaasgharstore.myshopify.com','access_token'=>'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']);
        $payload = ['username' => 'usama', 'email' => 'usama@gmail.com', 'variant_offered_amount' => 200, 'variant_actual_amount' => 2629.95, 'variant_id' => $product['variants']['edges'][0]['node']['id'], 'product_id' => $product['id'], 'browser' => 'Google Chrome', 'operating_system' => 'Windows', 'store_name' => $store['name'], 'variant_name' => 'RangRover121', 'product_name' => 'RangRover'];

        $response = $this->actingAs($store)->post('api/offer-graphQl', $payload);

        $customer_shop = Customer::first();
        $this->assertDatabaseHas('customers', ['name' => 'usama', 'email' => 'usama@gmail.com', 'shopify_id' =>$customer_shop['shopify_id'] ]);
        $this->assertDatabaseHas('offers', ['customer_id' => Customer::first()['id'], 'variant_id' => $product['variants']['edges'][0]['node']['id'], 'variant_name' => $this->fakeVariant()['title'], 'product_id' => $product['id'], 'variant_offered_amount' => $payload['variant_offered_amount'], 'variant_actual_amount' => $payload['variant_actual_amount']]);
        $response->assertStatus(200);
        $this->assertEquals('Your offer has been created successfully', $response['message']);
    }
}
