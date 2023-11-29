<?php

namespace App\Http\Controllers;

use App\Clients\Shopify\Client;
use App\Http\Requests\OfferReceivedRequest;
use Domain\Bargain\Actions\CreateCustomer;
use App\Models\Store;
use App\Models\Offer;
use App\Models\VariantDiscount;
use App\Models\Customer;
use App\Clients\IpLocate\IpLocation;
use App\Events\OfferReceivedEvent;
use App\Models\DiscountCode;
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
           
            if ($this->autoPriceCheck($store,$request['variant_offered_amount'], $request['variant_actual_amount'], $request['variant_id'], $offer, $shopify) == true) {
                return response()->json(['error' => false, 'message' => 'Your offer has been accepted'], 200);
            }

            if ($this->checkforTagDiscount($request['product_id'], $shopify, $request['variant_actual_amount'], $request['variant_offered_amount'], $offer) == true) {
                return response()->json(['error' => false, 'message' => 'Your offer has been accepted'], 200);
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
            $customerData =[
                "first_name" => $request['username'],
                "last_name" => "",
                "email" => $request['email'],
                "phone" => "",
                "verified_email" => true,
                "addresses" => [],
                "password" => "newpass",
                "password_confirmation" => "newpass",
                "send_email_welcome" => false,
            ];
            
            $shopifyCustomer = $shopify->createCustomer($customerData);
            if (!empty($request->ip())) {            
                return $customer = $createCustomer->execute($store,$request['username'],$request['email'],$request->ip(),$shopifyCustomer['id'],1);
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

    public function autoPriceCheck($store,$offeredAmount, $actualAmount, $variantId, $offer, $shopify)
    {
        if (VariantDiscount::where('variant_id', $variantId)->exists()) {
            if ($offeredAmount <= $this->getVariantDiscountValue($actualAmount, $variantId)) {
                if ($this->canAcceptOffer($store,$offer, $shopify)) {
                    return true;
                } else {
                    return false;
                }
            }
            return false;
        }
        return false;
    }

    public function getVariantDiscountValue($actualAmount, $variantId)
    {
        $variantDiscount = VariantDiscount::where('variant_id', $variantId)->first();
        return $this->getPercentageValue($actualAmount, $variantDiscount['discount_percentage']);
    }

    public function getPercentageValue($actualAmount, $discountPercentage)
    {
        $ratio = $discountPercentage / 100;
        $percentageValue = $ratio * $actualAmount;

        return $actualValue = $actualAmount - $percentageValue;
    }

    public function canAcceptOffer($store,$offer, $shopify)
    {
        if ($this->acceptOffer($store,$offer, $shopify)) {
            return true;
        }

        return false;
    }

    public function acceptOffer($store,Offer $offer, Client $shopify)
    {
        $shopify = app(Client::class, ['store' => $store]);

        if ($offer['status'] === 'pending') {
            $value = $offer['variant_offered_amount'] - $offer['variant_actual_amount'];
            $customer = $offer['customer'];
            
            $priceRule = [
                'title' => "Customer Bargain",
                'target_type' => "line_item",
                'target_selection' => "all",
                'allocation_method' => "across",
                'value_type' => "fixed_amount",
                'value' => $value,
                'customer_selection' => "all",
                'entitled_variant_ids' => [$offer['variant_id']],
                'starts_at' => "2017-01-19T17:59:10Z",
            ];

            $priceRule = $shopify->createPriceRule($priceRule);

            $discountCodePayloads = [
                'price_rule_id' => $priceRule['id'],
                'code' => "SUMMERSALE10OFF",
            ];

            $discountCode = $shopify->createDiscountCode($priceRule['id'],$discountCodePayloads);
            $this->storeDiscountCode($offer, $discountCode['code']);
            $url = env('APP_URL').'/checkout?product='.$offer['product_name'].'&variant='.$offer['variant_name'].'&shop='.$store['name'].'&code='.$discountCode['code'].'&customerId='.$customer['id'];
            $offer->approve();

            return true;
        }

        return response()->json(['error' => true, 'message' => 'You can not perform this action']);
    }

    public function storeDiscountCode(Offer $offer, string $code)
    {
        DiscountCode::create([
            'offer_id' => $offer['id'],
            'variant_id' => $offer['variant_id'],
            'shopify_id' => $offer['customer_id'],
            'code' => $code,
        ]);
    }
}
