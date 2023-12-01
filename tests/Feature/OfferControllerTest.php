<?php

namespace Tests\Feature;

use App\Domain\Bargain\Entities\Bargain;
use App\Events\OfferApprovedEvent;
use App\Events\OfferDeniedEvent;
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

        $product = Shopify::productWithGraphQl();
        $replaceVarient = ['id' => $product['variants']['edges'][0]['node']['id'], 'price' => $product['variants']['edges'][0]['node']['price'], 'product' => ['id' => $product['id']]];
        $varient = Shopify::variantWithGraphQl($replaceVarient);

        $productFakeResponse = ['product' => $product];
        $variantFakeResponse = ['productVariant' => $varient];
        $customerFakeResponse = ['customerCreate' => Shopify::customerWithGraphQl()];

        Http::fake([
            '*graphql.json' => Http::sequence()
                ->push(['data' => $productFakeResponse], 200)
                ->push(['data' => $variantFakeResponse], 200)
                ->push(['data' => $customerFakeResponse], 200)
        ]);

        $store = Store::factory()->create();
        ProductGroup::factory()->for($store)->create();

        $browsers = ['Google Chrome', 'Mozila', 'Safari'];
        $operatingSystems = ['Windows', 'Linux', 'Android', 'Iphone'];
        $payload = ['username' => $this->faker()->name, 'email' => $this->faker()->email, 'variant_offered_amount' => $varient['price'] - 10, 'variant_actual_amount' =>  $varient['price'], 'variant_id' => $product['variants']['edges'][0]['node']['id'], 'product_id' => $product['id'], 'browser' => $this->faker()->randomElements($browsers, 1)[0], 'operating_system' => $this->faker->randomElements($operatingSystems, 1)[0], 'store_name' => $store['name'], 'variant_name' => $varient['title'], 'product_name' => $product['title']];

        $response = $this->actingAs($store)->post('api/offer', $payload);

        $customer_shop = Customer::first();
        $this->assertDatabaseHas('customers', ['name' => $payload['username'], 'email' => $payload['email'], 'shopify_id' => $customer_shop['shopify_id']]);
        $this->assertDatabaseHas('offers', ['customer_id' => Customer::first()['id'], 'variant_id' => $product['variants']['edges'][0]['node']['id'], 'variant_name' => $varient['title'], 'product_id' => $product['id'], 'variant_offered_amount' => $payload['variant_offered_amount'], 'variant_actual_amount' => $payload['variant_actual_amount']]);
        $response->assertStatus(200);
        $this->assertEquals('Your offer has been created successfully', $response['message']);
    }

    public function testCreateNewOfferButBargainNotAddedAgainstProductGroup()
    {
        $product = Shopify::productWithGraphQl();
        $replaceVarient = ['id' => $product['variants']['edges'][0]['node']['id'], 'price' => $product['variants']['edges'][0]['node']['price'], 'product' => ['id' => $product['id']]];
        $varient = Shopify::variantWithGraphQl($replaceVarient);

        $productFakeResponse = ['product' => $product];
        $variantFakeResponse = ['productVariant' => $varient];
        $customerFakeResponse = ['customerCreate' => Shopify::customerWithGraphQl()];

        Http::fake([
            '*graphql.json' => Http::sequence()
                ->push(['data' => $productFakeResponse], 200)
                ->push(['data' => $variantFakeResponse], 200)
                ->push(['data' => $customerFakeResponse], 200)
        ]);

        $store = Store::factory()->create();
        $productGroup = ProductGroup::factory()->for($store)->create();

        $variantId = Client::getVariantIdFromGraphQlID($product['variants']['edges'][0]['node']['id']);
        Product::factory()->create(['product_group_id' => $productGroup['id'], 'shopify_id' => $variantId]);

        $browsers = ['Google Chrome', 'Mozila', 'Safari'];
        $operatingSystems = ['Windows', 'Linux', 'Android', 'Iphone'];
        $payload = ['username' => $this->faker()->name, 'email' => $this->faker()->email, 'variant_offered_amount' => $varient['price'] - 10, 'variant_actual_amount' =>  $varient['price'], 'variant_id' => $product['variants']['edges'][0]['node']['id'], 'product_id' => $product['id'], 'browser' => $this->faker()->randomElements($browsers, 1)[0], 'operating_system' => $this->faker->randomElements($operatingSystems, 1)[0], 'store_name' => $store['name'], 'variant_name' => $varient['title'], 'product_name' => $product['title']];

        $response = $this->actingAs($store)->post('api/offer', $payload);
        $customer_shop = Customer::first();
        $this->assertDatabaseHas('customers', ['name' => $payload['username'], 'email' => $payload['email'], 'shopify_id' => $customer_shop['shopify_id']]);
        $this->assertDatabaseHas('offers', ['customer_id' => Customer::first()['id'], 'variant_id' => $product['variants']['edges'][0]['node']['id'], 'variant_name' => $varient['title'], 'product_id' => $product['id'], 'variant_offered_amount' => $payload['variant_offered_amount'], 'variant_actual_amount' => $payload['variant_actual_amount']]);
        $response->assertStatus(200);
        $this->assertEquals('Your offer has been created successfully', $response['message']);
    }

    public function testOfferAutomaticallyAprrovedWithBargainFixedDiscount()
    {
        $product = Shopify::productWithGraphQl();
        $replaceVarient = ['id' => $product['variants']['edges'][0]['node']['id'], 'price' => $product['variants']['edges'][0]['node']['price'], 'product' => ['id' => $product['id']]];
        $varient = Shopify::variantWithGraphQl($replaceVarient);

        $productFakeResponse = ['product' => $product];
        $variantFakeResponse = ['productVariant' => $varient];
        $customerFakeResponse = ['customerCreate' => Shopify::customerWithGraphQl()];
        $priceRuleFakeResponse = ['priceRuleCreate' => Shopify::priceRuleWithGraphQl()];

        Http::fake([
            '*graphql.json' => Http::sequence()
                ->push(['data' => $productFakeResponse], 200)
                ->push(['data' => $variantFakeResponse], 200)
                ->push(['data' => $customerFakeResponse], 200)
                ->push(['data' => $priceRuleFakeResponse,], 200)
        ]);

        $store = Store::factory()->create();
        $productGroup = ProductGroup::factory()->for($store)->create();

        $variantId = Client::getVariantIdFromGraphQlID($product['variants']['edges'][0]['node']['id']);
        Product::factory()->create(['product_group_id' => $productGroup['id'], 'shopify_id' => $variantId]);
        Bargain::factory()->create(['product_group_id' => $productGroup['id'], 'type' => 'fixed', 'value' => 20]);

        $browsers = ['Google Chrome', 'Mozila', 'Safari'];
        $operatingSystems = ['Windows', 'Linux', 'Android', 'Iphone'];
        $payload = ['username' => $this->faker()->name, 'email' => $this->faker()->email, 'variant_offered_amount' => $varient['price'] - 10, 'variant_actual_amount' =>  $varient['price'], 'variant_id' => $product['variants']['edges'][0]['node']['id'], 'product_id' => $product['id'], 'browser' => $this->faker()->randomElements($browsers, 1)[0], 'operating_system' => $this->faker->randomElements($operatingSystems, 1)[0], 'store_name' => $store['name'], 'variant_name' => $varient['title'], 'product_name' => $product['title']];

        $response = $this->actingAs($store)->post('api/offer', $payload);
        $customer_shop = Customer::first();
        $this->assertDatabaseHas('customers', ['name' => $payload['username'], 'email' => $payload['email'], 'shopify_id' => $customer_shop['shopify_id']]);
        $this->assertDatabaseHas('offers', ['customer_id' => Customer::first()['id'], 'variant_id' => $product['variants']['edges'][0]['node']['id'], 'variant_name' => $varient['title'], 'product_id' => $product['id'], 'variant_offered_amount' => $payload['variant_offered_amount'], 'variant_actual_amount' => $payload['variant_actual_amount']]);
        $response->assertStatus(200);
        $this->assertEquals('Your offer has been accepted', $response['message']);
    }

    public function testOfferAutomaticallyAprrovedWithBargainPercentageDiscount()
    {
        $product = Shopify::productWithGraphQl();
        $replaceVarient = ['id' => $product['variants']['edges'][0]['node']['id'], 'price' => $product['variants']['edges'][0]['node']['price'], 'product' => ['id' => $product['id']]];
        $varient = Shopify::variantWithGraphQl($replaceVarient);

        $productFakeResponse = ['product' => $product];
        $variantFakeResponse = ['productVariant' => $varient];
        $customerFakeResponse = ['customerCreate' => Shopify::customerWithGraphQl()];
        $priceRuleFakeResponse = ['priceRuleCreate' => Shopify::priceRuleWithGraphQl()];

        Http::fake([
            '*graphql.json' => Http::sequence()
                ->push(['data' => $productFakeResponse], 200)
                ->push(['data' => $variantFakeResponse], 200)
                ->push(['data' => $customerFakeResponse], 200)
                ->push(['data' => $priceRuleFakeResponse,], 200)
        ]);

        $store = Store::factory()->create();
        $productGroup = ProductGroup::factory()->for($store)->create();

        $variantId = Client::getVariantIdFromGraphQlID($product['variants']['edges'][0]['node']['id']);
        Product::factory()->create(['product_group_id' => $productGroup['id'], 'shopify_id' => $variantId]);
        Bargain::factory()->create(['product_group_id' => $productGroup['id'], 'type' => 'percentage', 'value' => 30]);

        $browsers = ['Google Chrome', 'Mozila', 'Safari'];
        $operatingSystems = ['Windows', 'Linux', 'Android', 'Iphone'];
        $payload = ['username' => $this->faker()->name, 'email' => $this->faker()->email, 'variant_offered_amount' => $varient['price'] - 10, 'variant_actual_amount' =>  $varient['price'], 'variant_id' => $product['variants']['edges'][0]['node']['id'], 'product_id' => $product['id'], 'browser' => $this->faker()->randomElements($browsers, 1)[0], 'operating_system' => $this->faker->randomElements($operatingSystems, 1)[0], 'store_name' => $store['name'], 'variant_name' => $varient['title'], 'product_name' => $product['title']];

        $response = $this->actingAs($store)->post('api/offer', $payload);
        $customer_shop = Customer::first();
        $this->assertDatabaseHas('customers', ['name' => $payload['username'], 'email' => $payload['email'], 'shopify_id' => $customer_shop['shopify_id']]);
        $this->assertDatabaseHas('offers', ['customer_id' => Customer::first()['id'], 'variant_id' => $product['variants']['edges'][0]['node']['id'], 'variant_name' => $varient['title'], 'product_id' => $product['id'], 'variant_offered_amount' => $payload['variant_offered_amount'], 'variant_actual_amount' => $payload['variant_actual_amount']]);
        $response->assertStatus(200);
        $this->assertEquals('Your offer has been accepted', $response['message']);
    }


    public function testErrorIsThrownIfRequiredFieldsAreLeftEmpty()
    {
        $store = Store::factory()->create();
        $payload = ['email' => $this->faker()->email(), 'variant_actual_amount' => $this->faker()->randomNumber(3), 'variant_offered_amount' => $this->faker()->randomNumber(3)];

        $response = $this->actingAs($store)->post('api/offer', $payload);
        $response->assertSessionHasErrors('username', 'The username field is required.');
    }

    public function testErrorIsThrownIfActualAmountOfVariantGetsDifferentFromOriginalPrice()
    {
        $product = Shopify::productWithGraphQl();
        $replaceVarient = ['id' => $product['variants']['edges'][0]['node']['id'], 'price' => $product['variants']['edges'][0]['node']['price'], 'product' => ['id' => $product['id']]];
        $varient = Shopify::variantWithGraphQl($replaceVarient);

        $productFakeResponse = ['product' => $product];
        $variantFakeResponse = ['productVariant' => $varient];

        Http::fake([
            '*graphql.json' => Http::sequence()
                ->push(['data' => $productFakeResponse], 200)
                ->push(['data' => $variantFakeResponse], 200)
        ]);

        $store = Store::factory()->create();
        $browsers = ['Google Chrome', 'Mozila', 'Safari'];
        $operatingSystems = ['Windows', 'Linux', 'Android', 'Iphone'];
        $payload = ['username' => $this->faker()->name, 'email' => $this->faker()->email, 'variant_offered_amount' => $varient['price'] - 10, 'variant_actual_amount' =>  $this->faker()->randomNumber(3), 'variant_id' => $product['variants']['edges'][0]['node']['id'], 'product_id' => $product['id'], 'browser' => $this->faker()->randomElements($browsers, 1)[0], 'operating_system' => $this->faker->randomElements($operatingSystems, 1)[0], 'store_name' => $store['name'], 'variant_name' => $varient['title'], 'product_name' => $product['title']];

        $response = $this->actingAs($store)->post('api/offer', $payload);

        $response->assertSessionHasErrors("variant_actual_amount", "Variant's actual amount is different");
    }

    public function testErrorIsThrownIfTheOfferedPriceIsGreaterActualPrice()
    {
        $product = Shopify::productWithGraphQl();
        $replaceVarient = ['id' => $product['variants']['edges'][0]['node']['id'], 'price' => $product['variants']['edges'][0]['node']['price'], 'product' => ['id' => $product['id']]];
        $varient = Shopify::variantWithGraphQl($replaceVarient);

        $productFakeResponse = ['product' => $product];
        $variantFakeResponse = ['productVariant' => $varient];

        Http::fake([
            '*graphql.json' => Http::sequence()
                ->push(['data' => $productFakeResponse], 200)
                ->push(['data' => $variantFakeResponse], 200)
        ]);

        $store = Store::factory()->create();
        $browsers = ['Google Chrome', 'Mozila', 'Safari'];
        $operatingSystems = ['Windows', 'Linux', 'Android', 'Iphone'];
        $payload = ['username' => $this->faker()->name, 'email' => $this->faker()->email, 'variant_offered_amount' => $varient['price'] + 10, 'variant_actual_amount' =>  $varient['price'], 'variant_id' => $product['variants']['edges'][0]['node']['id'], 'product_id' => $product['id'], 'browser' => $this->faker()->randomElements($browsers, 1)[0], 'operating_system' => $this->faker->randomElements($operatingSystems, 1)[0], 'store_name' => $store['name'], 'variant_name' => $varient['title'], 'product_name' => $product['title']];

        $response = $this->actingAs($store)->post('api/offer', $payload);

        $response->assertSessionHasErrors("variant_offered_amount", "Variant's offered amount should be less than variant's actual amount");
    }

    public function testAnEventForSendingMailForReceiveingOfferWasDispatached()
    {
        Event::fake();
        $email = $this->faker()->email();
        $status = 'pending';
        event(new OfferReceivedEvent($email, $status));

        Event::assertDispatched(OfferReceivedEvent::class, function ($event) use ($email, $status) {
            return $event->email === $email && $event->status === $status;
        });
    }

    public function testGetAllOffers()
    {
        $store = Store::factory()->create();
        Offer::factory(2)->create();
        $response = $this->actingAs($store)->get('offers');
        $responseData = $response->json();

        $this->assertFalse($responseData['error']);
        $this->assertArrayHasKey('data', $responseData);
        $this->assertArrayHasKey('offers', $responseData['data']);
    }

    public function testGetOfferOnProductVariant()
    {
        $store = Store::factory()->create();
        $productFakeResponse = ['product' => Shopify::productWithGraphQl()];

        Http::fake([
            '*graphql.json' =>  Http::response(['data' => $productFakeResponse])
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

    public function testAdminAcceptOfferAndStoredDiscountCode()
    {
        $priceRuleFakeResponse = ['priceRuleCreate' => Shopify::priceRuleWithGraphQl()];

        Http::fake([
            '*graphql.json' =>  Http::response(['data' => $priceRuleFakeResponse])
        ]);

        $store = Store::factory()->create();
        $customer = Customer::factory()->create(['store_id' => $store['id'], 'shopify_id' => $this->faker()->randomNumber(8)]);
        $product = Shopify::productWithGraphQl();
        $offer = Offer::factory()->create(['customer_id' => $customer['id'], 'variant_id' => $product['variants']['edges'][0]['node']['id'], 'product_id' => $product['id'], 'variant_offered_amount' => $this->faker()->randomNumber(3), 'variant_actual_amount' => $this->faker()->randomNumber(3), 'status' => 'pending']);

        $response = $this->actingAs($store)->get("offer-accept/{$offer['id']}");

        $responseData = $response->json();
        $varaintId = Client::getVariantIdFromGraphQlID($offer['variant_id']);
        $this->assertDatabaseHas('discount_codes', ['variant_id' => $varaintId, 'code' => $responseData['data']['discountCode']]);
        $this->assertEquals('Offer has been Approved successfully', $response['message']);
    }

    public function testAnEventForSendingMailWasDispatachedOnApprovalOffer()
    {
        Event::fake();
        $email = $this->faker()->email();
        $code = $this->faker()->randomNumber(4);
        $fakeUrl = $this->faker()->url();
        event(new OfferApprovedEvent($email, $code, $fakeUrl));

        Event::assertDispatched(OfferApprovedEvent::class, function ($event) use ($email, $code, $fakeUrl) {
            return $event->email === $email && $event->code == $code;
        });
    }

    public function testAdminDeniedOffer()
    {
        $store = Store::factory()->create();
        $customer = Customer::factory()->create(['store_id' => $store['id'], 'shopify_id' => $this->faker()->randomNumber(8)]);
        $product = Shopify::productWithGraphQl();
        $offer = Offer::factory()->create(['customer_id' => $customer['id'], 'variant_id' => $product['variants']['edges'][0]['node']['id'], 'product_id' => $product['id'], 'variant_offered_amount' => $this->faker()->randomNumber(3), 'variant_actual_amount' => $this->faker()->randomNumber(3), 'status' => 'pending']);

        $response = $this->actingAs($store)->get("offer-deny/{$offer['id']}");
        $this->assertEquals('Offer Denied Successfully', $response['message']);
    }

    public function testAnEventForSendingMailWasDispatachedOnDeniedOffer()
    {
        Event::fake();
        $email = $this->faker()->email();
        event(new OfferDeniedEvent($email));

        Event::assertDispatched(OfferDeniedEvent::class, function ($event) use ($email) {
            return $event->email === $email;
        });
    }
}
