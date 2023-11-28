<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Store;
use Facades\Tests\Fake\Shopify;
use App\Models\VariantDiscount;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OfferControllerTest extends TestCase
{
    use
    RefreshDatabase,
    WithFaker;

    public function testUserCreateNewOfferOnProduct()
    {
        
        $fakeProduct = Shopify::fakeProductForCreateOffer();
        $fakeProductResponse = ['product'=> $fakeProduct];
        $fakeVariant = Shopify::fakevariantForCreateOffer();
        $fakeVariantResponse = ['variant'=> $fakeVariant];
        $fakePriceRuleResponse = ['price_rule' => Shopify::priceRule()];
        $fakeCustomerResponse = ['customer' => Shopify::Customer()];
        $discountCodeResponse = ['discount_code' => Shopify::DiscountCodeWithRestApi()];
        
        
        Http::fake([
            "*products/*.json" => Http::response($fakeProductResponse),
            "*variants/*.json" => Http::response($fakeVariantResponse),
            "*price_rules.json" => Http::response($fakePriceRuleResponse),
            "*unstable/customers.json" => Http::response($fakeCustomerResponse),
            "*price_rules/*/discount_codes.json" => Http::response($discountCodeResponse),
            
        ]);

        VariantDiscount::create([
            'variant_id' => $fakeVariant['id'],
            'discount_percentage' => 5
        ]);

        $store = Store::factory()->create(['name' => 'usamaasghar.myshopify.com']);
        $payload = ['username' => 'usama', 'email' => 'usama@gmail.com', 'variant_offered_amount' => 2, 'variant_actual_amount' => $fakeVariant['price'], 'variant_id' => $fakeProduct['variants'][0]['id'], 'product_id' => $fakeProduct['id'], 'browser' => 'Google Chrome', 'operating_system' => 'Windows', 'store_name' => 'usamaasghar.myshopify.com', 'variant_name' => $fakeProduct['variants'][0]['title'], 'product_name' => $fakeProduct['title']];

        $response = $this->actingAs($store)->post('api/offer', $payload);

        $customer_shop = Customer::first();
        // dd($customer_shop['shopify_id']);
        $this->assertDatabaseHas('customers', ['name' => 'usama', 'email' => 'usama@gmail.com', 'shopify_id' =>$customer_shop['shopify_id'] ]);
        $this->assertDatabaseHas('offers', ['customer_id' => Customer::first()['id'], 'variant_id' => $fakeProduct['variants'][0]['id'], 'variant_name' => $fakeProduct['variants'][0]['title'], 'product_id' => $fakeProduct['id'], 'variant_offered_amount' => $payload['variant_offered_amount'], 'variant_actual_amount' => $payload['variant_actual_amount']]);
        $response->assertStatus(200);
        $this->assertEquals('Your offer has been created successfully', $response['message']);
    }
}
