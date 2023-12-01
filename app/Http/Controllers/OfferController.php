<?php

namespace App\Http\Controllers;

use App\Clients\Shopify\Client;
use App\Http\Requests\OfferReceivedRequest;
use Domain\Bargain\Actions\CreateCustomer;
use App\Models\Store;
use App\Models\Offer;
use App\Models\Customer;
use App\Domain\Bargain\Entities\Bargain;
use App\Events\OfferDeniedEvent;
use App\Events\OfferReceivedEvent;
use Domain\Bargain\Actions\OfferActions;
use Domain\Bargain\Entities\Product;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    public function newOffer(OfferReceivedRequest $request, Client $shopify)
    {
        $store = Store::where('name', $request['store_name'])->first();
        $customer = $this->getOrCreateCustomer($store, $request, $shopify);
        if ($customer->hasOffered($request['product_id'], $request['variant_id'], $store) == false) {

            $offer = Offer::create([
                'customer_id' => $customer['id'],
                'variant_id' => $request['variant_id'],
                'variant_name' => $request['variant_name'],
                'product_name' => $request['product_name'],
                'product_id' => $request['product_id'],
                'store_id' => $store['id'],
                'variant_offered_amount' => $request['variant_offered_amount'],
                'variant_actual_amount' => $request['variant_actual_amount'],
                'status' => 'pending',
            ]);

            $variantId = $shopify->getVariantIdFromGraphQlID($request['variant_id']);
            $product = Product::where('shopify_id', $variantId)->first();

            if (!$product) {
                event(new OfferReceivedEvent($request['email'], 'pending'));
                return response()->json(['error' => false, 'message' => 'Your offer has been created successfully'], 200);
            }

            $bargainData = Bargain::where('product_group_id', $product->product_group_id)->first();
            if (!$bargainData) {
                event(new OfferReceivedEvent($request['email'], 'pending'));
                return response()->json(['error' => false, 'message' => 'Your offer has been created successfully'], 200);
            }


            if ($this->autoPriceCheck($request['variant_offered_amount'], $request['variant_actual_amount'], $bargainData)) {
                $OfferActions = new OfferActions;
                if ($OfferActions->ApprovedOffer($offer, $shopify, $store)) {
                    return response()->json(['error' => false, 'message' => 'Your offer has been accepted'], 200);
                }
            }

            event(new OfferReceivedEvent($request['email'], 'pending'));

            return response()->json(['error' => false, 'message' => 'Your offer has been created successfully'], 200);
        }
        return response()->json(['error' => true, 'message' => 'You can not make offer on this product\'s variant']);
    }

    public function getOrCreateCustomer($store, Request $request, Client $shopify)
    {

        $shopify = app(Client::class, ['store' => $store]);
        $createCustomer = app(CreateCustomer::class);
        if (!Customer::where('email', $request['email'])->where('store_id', $store['id'])->exists()) {

            $shopifyCustomer = $shopify->createCustomerWithGraphQl($request['email']);

            if ($shopifyCustomer['data']['customerCreate']['customer'] == null) {
                $errorMessage = $shopifyCustomer['data']['customerCreate']['userErrors'][0]['message'];
                return response()->json(['error' => true, 'message' => $errorMessage]);
            }

            $shopifyCustomer = $shopifyCustomer['data']['customerCreate']['customer'];

            $customerId = $shopifyCustomer['id'];
            $customerId = (int)substr($customerId, strpos($customerId, "r/") + 2);
            $shopifyCustomer['id'] =  $customerId;
            if (!empty($request->ip())) {
                return $customer = $createCustomer->execute($store, $request['username'], $request['email'], $request->ip(), $shopifyCustomer['id'], 1);
            } else {
                return response()->json(['error' => true, 'message' => 'IP cannot be empty']);
            }
        }

        $customer = Customer::where('email', $request['email'])->where('store_id', $store['id'])->first();
        if ($customer->hasOffered($request['product_id'], $request['variant_id'], $store) == false) {
            $customer->offer_count = $customer->offer_count + 1;
            $customer->save();

            return $customer;
        }

        return $customer;
    }

    public function autoPriceCheck($offeredAmount, $actualAmount, $bargainData)
    {
        if ($bargainData->type == 'fixed') {
            if ($offeredAmount >= ($actualAmount - $bargainData->value)) {
                return true;
            }
        } else {
            if ($offeredAmount >= $this->getPercentageValue($actualAmount, $bargainData->value)) {
                return true;
            }
        }

        return false;
    }

    public function getPercentageValue($actualAmount, $discountPercentage)
    {
        $ratio = $discountPercentage / 100;
        $percentageValue = $ratio * $actualAmount;

        return $actualAmount - $percentageValue;
    }

    public function getAllOffers()
    {
        $allOffers = \App\Models\Offer::all();

        return response()->json([
            'error' => false,
            'data' => [
                'offers' => $allOffers
            ]
        ]);
    }

    public function getOffer(Offer $offer, Client $shopify)
    {
        $store = Store::where('id', $offer['store_id'])->first();
        $shopify = app(Client::class, ['store' => $store]);
        $product = $shopify->getProductWithGraphQl($offer['product_id']);

        return response()->json([
            'error' => false,
            'data' => [
                'offer' => $offer,
                'product' => $product,
            ]
        ]);
    }

    public function acceptOffer(Offer $offer, Client $shopify, Request $request)
    {
        $store = $request->get('store');
        $shopify = app(Client::class, ['store' => $store]);
        $OfferActions = new OfferActions;
        $discountCode = $OfferActions->ApprovedOffer($offer, $shopify, $store);
        if ($discountCode) {
            return response()->json([
                'error' => false,
                'message' => 'Offer has been Approved successfully',
                'data' => [
                    'discountCode' => $discountCode
                ]
            ], 200);
        }
    }

    public function denyOffer(Offer $offer, Request $request)
    {
        if ($offer['status'] === 'pending') {
            event(new OfferDeniedEvent($offer['customer']['email']));
            $offer->deny($request);

            return response()->json(['error' => false, 'message' => 'Offer Denied Successfully']);
        }

        return response()->json(['error' => true, 'message' => 'You can not perform this action']);
    }
}
