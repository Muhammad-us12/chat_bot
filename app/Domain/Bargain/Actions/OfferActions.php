<?php

namespace Domain\Bargain\Actions;

use App\Clients\Shopify\Client;
use App\Models\DiscountCode;
use App\Models\Offer;

class OfferActions
{
    public function ApprovedOffer(Offer $offer, Client $shopify, $store)
    {
        $shopify = app(Client::class, ['store' => $store]);
        if ($offer['status'] === 'pending') {
            $value = $offer['variant_offered_amount'] - $offer['variant_actual_amount'];
            $customer = $offer['customer'];
            $shopify_id = "gid://shopify/Customer/" . $customer['shopify_id'];
            $priceRuleResponse = $shopify->createPriceRuleAndGetDiscountCodeGraphQl($value, $offer['variant_id'], $shopify_id);
            if (!isset($priceRuleResponse['data']['priceRuleCreate'])) {
                return false;
            }

            $priceRuleData = $priceRuleResponse['data']['priceRuleCreate'];
            $this->storeDiscountCode($offer, $priceRuleData['priceRuleDiscountCode']['code']);
            $discountCode = $priceRuleData['priceRuleDiscountCode']['code'];
            $url = env('APP_URL') . '/checkout?product=' . $offer['product_name'] . '&variant=' . $offer['variant_name'] . '&shop=' . $store['name'] . '&code=' . $discountCode . '&customerId=' . $customer['id'];
            // event(new OfferApprovedEvent($customer['email'], $priceRule['code'], $url));
            $offer->approve();

            return true;
        }
    }

    public function storeDiscountCode(Offer $offer, string $code)
    {
        $variantId = (int) substr($offer['variant_id'], strpos($offer['variant_id'], 't/') + 2);
        DiscountCode::create([
            'offer_id' => $offer['id'],
            'variant_id' => $variantId,
            'shopify_id' => $offer['customer_id'],
            'code' => $code,
        ]);
    }
}
