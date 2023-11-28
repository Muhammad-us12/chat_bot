<?php

namespace Tests\Feature;

use App\Clients\Shopify\Client;
use App\Models\Customer;
use App\Models\Store;
use App\Models\VariantDiscount;
use Facades\Tests\Fake\Shopify;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class OfferControllerGraphQlTest extends TestCase
{
    use
    RefreshDatabase,
    WithFaker;

    public function testUserCreateNewOfferOnProductGraphQl()
    {
        $productFakeResponse = ['product' => Shopify::productWithGraphQl()];
        $variantFakeResponse = ['productVariant' => Shopify::variantWithGraphQl()];
        $customerFakeResponse = ['customerCreate' => Shopify::customerWithGraphQl()];
        $priceRuleFakeResponse = ['priceRuleCreate' => Shopify::priceRuleWithGraphQl()];
        
        Http::fake([
            '*graphql.json' => Http::sequence()
                ->push(['data' => $productFakeResponse], 200)
                ->push(['data' => $variantFakeResponse], 200)
                ->push(['data' => $customerFakeResponse], 200)
                ->push(['data' => $priceRuleFakeResponse,], 200)
        ]);
       

        VariantDiscount::create([
            'variant_id' => '44091188215965',
            'discount_percentage' => 5
        ]);

        $product = Shopify::fakeProductForCreateOfferWithGraphQl();
        $varient = Shopify::fakeVariantForCreateOfferWithGraphQl();
        $store = Store::factory()->create(['name' => 'usamaasgharstore.myshopify.com','access_token'=>'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']);
        $payload = ['username' => 'usama', 'email' => 'usama@gmail.com', 'variant_offered_amount' => 200, 'variant_actual_amount' => 2629.95, 'variant_id' => $product['variants']['edges'][0]['node']['id'], 'product_id' => $product['id'], 'browser' => 'Google Chrome', 'operating_system' => 'Windows', 'store_name' => $store['name'], 'variant_name' => 'RangRover121', 'product_name' => 'RangRover'];

        $response = $this->actingAs($store)->post('api/offer-graphQl', $payload);

        $customer_shop = Customer::first();
        $this->assertDatabaseHas('customers', ['name' => 'usama', 'email' => 'usama@gmail.com', 'shopify_id' =>$customer_shop['shopify_id'] ]);
        $this->assertDatabaseHas('offers', ['customer_id' => Customer::first()['id'], 'variant_id' => $product['variants']['edges'][0]['node']['id'], 'variant_name' => $varient['title'], 'product_id' => $product['id'], 'variant_offered_amount' => $payload['variant_offered_amount'], 'variant_actual_amount' => $payload['variant_actual_amount']]);
        $response->assertStatus(200);
        $this->assertEquals('Your offer has been created successfully', $response['message']);
    }
}
