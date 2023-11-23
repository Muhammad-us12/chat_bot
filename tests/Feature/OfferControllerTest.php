<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Store;
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

    public function fakeProduct()
    {
        return ['id' => '13222270548386512', 'title' => 'RangRover', 'vendor' => 'robustweb', 'variants' => ['edges' => [['node' => ['id' => '32222705483865', 'price' => 230, 'displayName' => 'Rang Rover']]]], 'tags' => ['OLA-Product']];
    }

    public function fakeVariant()
    {
        return ['id' => '32222705483865', 'title' => 'RangRover121', 'price' => 230, 'product' => ['id' => '13222270548386512']];
    }

    

    public function fakeCustomerResponse()
    {
        return [
            'id' => 1073339467,
            'email' => 'usama@gmail.com',
            'accepts_marketing' => false,
            'created_at' => '2023-10-03T13:36:34-04:00',
            'updated_at' => '2023-10-03T13:36:34-04:00',
            'first_name' => 'usama',
            'last_name' => '',
            'orders_count' => 0,
            'state' => 'enabled',
            'total_spent' => '0.00',
            'last_order_id' => NULL,
            'note' => NULL,
            'verified_email' => true,
            'multipass_identifier' => NULL,
            'tax_exempt' => false,
            'tags' => '',
            'last_order_name' => NULL,
            'currency' => 'USD',
            'phone' => '+15142546011',
            'addresses' => [],
            'accepts_marketing_updated_at' => '2023-10-03T13:36:34-04:00',
            'marketing_opt_in_level' => NULL,
            'tax_exemptions' => [],
            'email_marketing_consent' => [
                'state' => 'not_subscribed',
                'opt_in_level' => 'single_opt_in',
                'consent_updated_at' => NULL,
                ],
            'sms_marketing_consent' => [
                'state' => 'not_subscribed',
                'opt_in_level' => 'single_opt_in',
                'consent_updated_at' => NULL,
                'consent_collected_from' => 'OTHER',
                ],
            'admin_graphql_api_id' => 'gid://shopify/Customer/1073339467',
            'default_address' => [],
          ];
    }

    public function priceRuleResponse(){
        return [
                "id" => 996606715,
                "value_type" => "percentage",
                "value" => "-100.0",
                "customer_selection" => "all",
                "target_type" => "line_item",
                "target_selection" => "entitled",
                "allocation_method" => "each",
                "allocation_limit" => 3,
                "once_per_customer" => false,
                "usage_limit" => null,
                "starts_at" => "2018-03-21T20:00:00-04:00",
                "ends_at" => null,
                "created_at" => "2023-07-11T18:37:53-04:00",
                "updated_at" => "2023-07-11T18:37:53-04:00",
                "entitled_product_ids" => [
                  921728736
                ],
                "entitled_variant_ids" => ['32222705483865'],
                "entitled_collection_ids" => [],
                "entitled_country_ids" => [],
                "prerequisite_product_ids" => [],
                "prerequisite_variant_ids" => [],
                "prerequisite_collection_ids" => [
                  841564295
                ],
                "customer_segment_prerequisite_ids" => [],
                "prerequisite_customer_ids" => [],
                "prerequisite_subtotal_range" => null,
                "prerequisite_quantity_range" => null,
                "prerequisite_shipping_price_range" => null,
                "prerequisite_to_entitlement_quantity_ratio" => [
                  "prerequisite_quantity" => 2,
                  "entitled_quantity" => 1
                ],
                "prerequisite_to_entitlement_purchase" => [
                  "prerequisite_amount" => null
                ],
                "title" => "Buy2iPodsGetiPodTouchForFree",
                "admin_graphql_api_id" => "gid://shopify/PriceRule/996606715"
             ];
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

    public function testUserCreateNewOfferOnProduct()
    {
        Http::fake([
            "*products/*.json" => Http::response(['product'=> $this->fakeProduct()]),
            "*variants/*.json" => Http::response(['variant'=> $this->fakeVariant()]),
            "*price_rules.json" => Http::response(['price_rule'=> $this->priceRuleResponse()]),
            "*unstable/customers.json" => Http::response(['customer'=> $this->fakeCustomerResponse()]),
            "*price_rules/*/discount_codes.json" => Http::response(['discount_code'=> $this->DiscountCodeResponse()]),
            
        ]);

        VariantDiscount::create([
            'variant_id' => '32222705483865',
            'discount_percentage' => 5
        ]);

        $product = $this->fakeProduct();
        $store = Store::factory()->create(['name' => 'usamaasghar.myshopify.com']);
        $payload = ['username' => 'usama', 'email' => 'usama@gmail.com', 'variant_offered_amount' => 200, 'variant_actual_amount' => 230, 'variant_id' => $product['variants']['edges'][0]['node']['id'], 'product_id' => $product['id'], 'browser' => 'Google Chrome', 'operating_system' => 'Windows', 'store_name' => 'usamaasghar.myshopify.com', 'variant_name' => 'RangRover121', 'product_name' => 'RangRover'];

        $response = $this->actingAs($store)->post('api/offer', $payload);
        $customer_shop = Customer::first();
        // dd($customer_shop['shopify_id']);
        $this->assertDatabaseHas('customers', ['name' => 'usama', 'email' => 'usama@gmail.com', 'shopify_id' =>$customer_shop['shopify_id'] ]);
        $this->assertDatabaseHas('offers', ['customer_id' => Customer::first()['id'], 'variant_id' => $product['variants']['edges'][0]['node']['id'], 'variant_name' => $this->fakeVariant()['title'], 'product_id' => $product['id'], 'variant_offered_amount' => $payload['variant_offered_amount'], 'variant_actual_amount' => $payload['variant_actual_amount']]);
        $response->assertStatus(200);
        $this->assertEquals('Your offer has been created successfully', $response['message']);
    }
}
