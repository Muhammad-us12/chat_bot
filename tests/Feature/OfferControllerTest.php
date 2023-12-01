<?php

namespace Tests\Feature;

use App\Domain\Bargain\Entities\Bargain;
use Facades\App\Clients\Shopify\Client;
use App\Events\OfferReceivedEvent;
use App\Models\Customer;
use App\Models\Offer;
use App\Models\Store;
use Domain\Bargain\Entities\Product;
use Domain\Bargain\Entities\ProductGroup;
use Facades\Tests\Fake\Shopify;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class OfferControllerTest extends TestCase
{
    use
        RefreshDatabase,
        WithFaker;

    public function testCreateNewOfferButVariantNotExistsInProductGroup()
    {
        $productFakeResponse = ['product' => Shopify::productWithGraphQl()];
        $variantFakeResponse = ['productVariant' => Shopify::variantWithGraphQl()];
        $customerFakeResponse = ['customerCreate' => Shopify::customerWithGraphQl()];

        Http::fake([
            '*graphql.json' => Http::sequence()
                ->push(['data' => $productFakeResponse], 200)
                ->push(['data' => $variantFakeResponse], 200)
                ->push(['data' => $customerFakeResponse], 200)
        ]);

        $product = Shopify::fakeProductForCreateOfferWithGraphQl();
        $varient = Shopify::fakeVariantForCreateOfferWithGraphQl();
        $store = Store::factory()->create(['name' => 'usamaasgharstore.myshopify.com', 'access_token' => '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']);
        ProductGroup::factory()->for($store)->create();

        $payload = ['username' => 'usama', 'email' => 'usama@gmail.com', 'variant_offered_amount' => 200, 'variant_actual_amount' => 2629.95, 'variant_id' => $product['variants']['edges'][0]['node']['id'], 'product_id' => $product['id'], 'browser' => 'Google Chrome', 'operating_system' => 'Windows', 'store_name' => $store['name'], 'variant_name' => 'RangRover121', 'product_name' => 'RangRover'];

        $response = $this->actingAs($store)->post('api/offer', $payload);
        $customer_shop = Customer::first();
        $this->assertDatabaseHas('customers', ['name' => 'usama', 'email' => 'usama@gmail.com', 'shopify_id' => $customer_shop['shopify_id']]);
        $this->assertDatabaseHas('offers', ['customer_id' => Customer::first()['id'], 'variant_id' => $product['variants']['edges'][0]['node']['id'], 'variant_name' => $varient['title'], 'product_id' => $product['id'], 'variant_offered_amount' => $payload['variant_offered_amount'], 'variant_actual_amount' => $payload['variant_actual_amount']]);
        $response->assertStatus(200);
        $this->assertEquals('Your offer has been created successfully', $response['message']);
    }

    public function testCreateNewOfferButBargainNotAddedAgainstProductGroup()
    {
        $productFakeResponse = ['product' => Shopify::productWithGraphQl()];
        $variantFakeResponse = ['productVariant' => Shopify::variantWithGraphQl()];
        $customerFakeResponse = ['customerCreate' => Shopify::customerWithGraphQl()];

        Http::fake([
            '*graphql.json' => Http::sequence()
                ->push(['data' => $productFakeResponse], 200)
                ->push(['data' => $variantFakeResponse], 200)
                ->push(['data' => $customerFakeResponse], 200)
        ]);

        $product = Shopify::fakeProductForCreateOfferWithGraphQl();
        $varient = Shopify::fakeVariantForCreateOfferWithGraphQl();
        $store = Store::factory()->create(['name' => 'usamaasgharstore.myshopify.com', 'access_token' => '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']);
        $productGroup = ProductGroup::factory()->for($store)->create();

        $variantId = Client::getVariantIdFromGraphQlID($product['variants']['edges'][0]['node']['id']);
        Product::factory()->create(['product_group_id' => $productGroup['id'], 'shopify_id' => $variantId]);
        $payload = ['username' => 'usama', 'email' => 'usama@gmail.com', 'variant_offered_amount' => 200, 'variant_actual_amount' => 2629.95, 'variant_id' => $product['variants']['edges'][0]['node']['id'], 'product_id' => $product['id'], 'browser' => 'Google Chrome', 'operating_system' => 'Windows', 'store_name' => $store['name'], 'variant_name' => 'RangRover121', 'product_name' => 'RangRover'];

        $response = $this->actingAs($store)->post('api/offer', $payload);
        $customer_shop = Customer::first();
        $this->assertDatabaseHas('customers', ['name' => 'usama', 'email' => 'usama@gmail.com', 'shopify_id' => $customer_shop['shopify_id']]);
        $this->assertDatabaseHas('offers', ['customer_id' => Customer::first()['id'], 'variant_id' => $product['variants']['edges'][0]['node']['id'], 'variant_name' => $varient['title'], 'product_id' => $product['id'], 'variant_offered_amount' => $payload['variant_offered_amount'], 'variant_actual_amount' => $payload['variant_actual_amount']]);
        $response->assertStatus(200);
        $this->assertEquals('Your offer has been created successfully', $response['message']);
    }

    public function testOfferAutomaticallyAprrovedWithBargainFixedDiscount()
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

        $product = Shopify::fakeProductForCreateOfferWithGraphQl();
        $varient = Shopify::fakeVariantForCreateOfferWithGraphQl();
        $store = Store::factory()->create(['name' => 'usamaasgharstore.myshopify.com', 'access_token' => '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']);
        $productGroup = ProductGroup::factory()->for($store)->create();

        $variantId = Client::getVariantIdFromGraphQlID($product['variants']['edges'][0]['node']['id']);
        Product::factory()->create(['product_group_id' => $productGroup['id'], 'shopify_id' => $variantId]);
        Bargain::factory()->create(['product_group_id' => $productGroup['id'], 'type' => 'fixed']);
        $payload = ['username' => 'usama', 'email' => 'usama@gmail.com', 'variant_offered_amount' => 200, 'variant_actual_amount' => 2629.95, 'variant_id' => $product['variants']['edges'][0]['node']['id'], 'product_id' => $product['id'], 'browser' => 'Google Chrome', 'operating_system' => 'Windows', 'store_name' => $store['name'], 'variant_name' => 'RangRover121', 'product_name' => 'RangRover'];

        $response = $this->actingAs($store)->post('api/offer', $payload);
        $customer_shop = Customer::first();
        $this->assertDatabaseHas('customers', ['name' => 'usama', 'email' => 'usama@gmail.com', 'shopify_id' => $customer_shop['shopify_id']]);
        $this->assertDatabaseHas('offers', ['customer_id' => Customer::first()['id'], 'variant_id' => $product['variants']['edges'][0]['node']['id'], 'variant_name' => $varient['title'], 'product_id' => $product['id'], 'variant_offered_amount' => $payload['variant_offered_amount'], 'variant_actual_amount' => $payload['variant_actual_amount']]);
        $response->assertStatus(200);
        $this->assertEquals('Your offer has been accepted', $response['message']);
    }

    public function testOfferAutomaticallyAprrovedWithBargainPercentageDiscount()
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

        $product = Shopify::fakeProductForCreateOfferWithGraphQl();
        $varient = Shopify::fakeVariantForCreateOfferWithGraphQl();
        $store = Store::factory()->create(['name' => 'usamaasgharstore.myshopify.com', 'access_token' => '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']);
        $productGroup = ProductGroup::factory()->for($store)->create();

        $variantId = Client::getVariantIdFromGraphQlID($product['variants']['edges'][0]['node']['id']);
        Product::factory()->create(['product_group_id' => $productGroup['id'], 'shopify_id' => $variantId]);
        Bargain::factory()->create(['product_group_id' => $productGroup['id'], 'type' => 'percentage']);
        $payload = ['username' => 'usama', 'email' => 'usama@gmail.com', 'variant_offered_amount' => 200, 'variant_actual_amount' => 2629.95, 'variant_id' => $product['variants']['edges'][0]['node']['id'], 'product_id' => $product['id'], 'browser' => 'Google Chrome', 'operating_system' => 'Windows', 'store_name' => $store['name'], 'variant_name' => 'RangRover121', 'product_name' => 'RangRover'];

        $response = $this->actingAs($store)->post('api/offer', $payload);
        $customer_shop = Customer::first();
        $this->assertDatabaseHas('customers', ['name' => 'usama', 'email' => 'usama@gmail.com', 'shopify_id' => $customer_shop['shopify_id']]);
        $this->assertDatabaseHas('offers', ['customer_id' => Customer::first()['id'], 'variant_id' => $product['variants']['edges'][0]['node']['id'], 'variant_name' => $varient['title'], 'product_id' => $product['id'], 'variant_offered_amount' => $payload['variant_offered_amount'], 'variant_actual_amount' => $payload['variant_actual_amount']]);
        $response->assertStatus(200);
        $this->assertEquals('Your offer has been accepted', $response['message']);
    }


    public function testErrorIsThrownIfRequiredFieldsAreLeftEmpty()
    {
        $store = Store::factory()->create(['name' => 'usamaasgharstore.myshopify.com', 'access_token' => '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']);
        $payload = ['email' => 'haroon@gmail.com', 'variant_actual_amount' => 400, 'variant_offered_amount' => 200];

        $response = $this->actingAs($store)->post('api/offer', $payload);
        $response->assertSessionHasErrors('username', 'The username field is required.');
    }

    public function testErrorIsThrownIfActualAmountOfVariantGetsDifferentFromOriginalPrice()
    {
        $productFakeResponse = ['product' => Shopify::productWithGraphQl()];
        $variantFakeResponse = ['productVariant' => Shopify::variantWithGraphQl()];

        Http::fake([
            '*graphql.json' => Http::sequence()
                ->push(['data' => $productFakeResponse], 200)
                ->push(['data' => $variantFakeResponse], 200)
        ]);

        $store = Store::factory()->create(['name' => 'usamaasgharstore.myshopify.com', 'access_token' => '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']);
        $payload = ['username' => 'harry\'s', 'email' => 'haroon@gmail.com', 'variant_offered_amount' => 200, 'variant_actual_amount' => 100, 'variant_id' => 'asas', 'product_id' => 'asasa', 'browser' => 'Google Chrome', 'operating_system' => 'Windows', 'store_name' => 'usamaasgharstore.myshopify.com'];
        $response = $this->actingAs($store)->post('api/offer', $payload);

        $response->assertSessionHasErrors("variant_actual_amount", "Variant's actual amount is different");
    }

    public function testErrorIsThrownIfTheOfferedPriceIsGreaterActualPrice()
    {
        $productFakeResponse = ['product' => Shopify::productWithGraphQl()];
        $variantFakeResponse = ['productVariant' => Shopify::variantWithGraphQl()];

        Http::fake([
            '*graphql.json' => Http::sequence()
                ->push(['data' => $productFakeResponse], 200)
                ->push(['data' => $variantFakeResponse], 200)
        ]);

        $store = Store::factory()->create(['name' => 'usamaasgharstore.myshopify.com', 'access_token' => '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']);
        $payload = ['username' => 'harry', 'email' => 'haroon@gmail.com', 'variant_offered_amount' => 3000, 'variant_actual_amount' => 2629.95, 'variant_id' => 'asas', 'product_id' => 'asasa', 'browser' => 'Google Chrome', 'operating_system' => 'Windows', 'store_name' => 'usamaasgharstore.myshopify.com'];
        $response = $this->actingAs($store)->post('api/offer', $payload);

        $response->assertSessionHasErrors("variant_offered_amount", "Variant's offered amount should be less than variant's actual amount");
    }

    public function testAnEventForSendingMailForReceiveingOfferWasDispatached()
    {
        Event::fake();
        $email = 'uasghar992@gmail.com';
        $status = 'pending';
        event(new OfferReceivedEvent($email, $status));

        Event::assertDispatched(OfferReceivedEvent::class, function ($event) use ($email, $status) {
            return $event->email === $email && $event->status === $status;
        });
    }

    public function testGetAllOffers()
    {
        $store = Store::factory()->create(['name' => 'usamaasgharstore.myshopify.com']);
        Offer::factory(2)->create();
        $response = $this->actingAs($store)->get('offers');
        $responseData = $response->json();

        $this->assertFalse($responseData['error']);
        $this->assertArrayHasKey('data', $responseData);
        $this->assertArrayHasKey('offers', $responseData['data']);
    }

    public function testGetOfferOnProductVariant()
    {
        $store = Store::factory()->create(['name' => 'usamaasgharstore.myshopify.com']);
        $productFakeResponse = ['product' => Shopify::productWithGraphQl()];

        Http::fake([
            '*graphql.json' => Http::sequence()
                ->push(['data' => $productFakeResponse], 200)
                ->push(['data' => $productFakeResponse], 200)
        ]);

        $customer = Customer::factory()->create(['store_id' => $store['id']]);
        $product = Shopify::productWithGraphQl();
        $offer = Offer::factory()->create(['customer_id' => $customer['id'], 'variant_id' => $product['variants']['edges'][0]['node']['id'], 'product_id' => $product['id'], 'variant_offered_amount' => 200, 'variant_actual_amount' => 230, 'status' => 'pending', 'store_id' => $store['id']]);

        $response = $this->actingAs($store)->get("offers/{$offer['id']}");

        $responseData = $response->json();
        $this->assertFalse($responseData['error']);
        $this->assertArrayHasKey('data', $responseData);
        $this->assertArrayHasKey('offer', $responseData['data']);
        $this->assertArrayHasKey('product', $responseData['data']);
    }

    // public function test_admin_can_accept_offer_on_an_product_variant_and_discount_codes_are_stored()
    // {
    //     $priceRuleFakeResponse = ['priceRuleCreate' => Shopify::priceRuleWithGraphQl()];

    //     Http::fake([
    //         '*graphql.json' => Http::sequence()
    //             ->push(['data' => $priceRuleFakeResponse,], 200)
    //     ]);
    //     $store = Store::factory()->create(['name' => 'usamaasgharstore.myshopify.com']);
    //     $customer = Customer::factory()->create(['store_id' => $store['id']]);
    //     $product = Shopify::productWithGraphQl();
    //     $offer = Offer::factory()->create(['customer_id' => $customer['id'], 'variant_id' => $product['variants']['edges'][0]['node']['id'], 'product_id' => $product['id'], 'variant_offered_amount' => 200, 'variant_actual_amount' => 230, 'status' => 'pending']);

    //     $response = $this->actingAs($store)->get("offer-accept/{$offer['id']}");
    //     dd($response);
    //     $this->assertDatabaseHas('discount_codes', ['variant_id' => $offer['variant_id'], 'customer_id' => $offer['customer_id'], 'code' => $this->fakePriceRuleAndDiscountCode()['code']]);
    //     $response->assertStatus(302);
    // }
}
