<?php

namespace App\Clients\Objects\GraphQl;

class CreatePriceRuleAndGetDiscountCode
{
    public $query;

    public function __construct(int $value, string $variantId, string $customerId)
    {
        $usageLimit = 1;
        $this->query = 'mutation {
  priceRuleCreate(priceRule: {customerSelection:{customerIdsToAdd :"' . $customerId . '"} ,target: LINE_ITEM,allocationMethod: ACROSS,validityPeriod :{start: "2019-07-03T20:47:55Z"},value:{fixedAmountValue :' . $value . '},usageLimit:' . $usageLimit . ', title: "First Discount", itemEntitlements : {productVariantIds:["' . $variantId . '"],targetAllLineItems: false}},priceRuleDiscountCode: {code:"' . $this->generateRandomDiscountCode(4) . '"}) {
    priceRule {
      id
    }
    priceRuleDiscountCode {
      id
      code
    }
    priceRuleUserErrors {
      code
      field
      message
    }
  }
}
';

    }

    public function __toString()
    {
        return $this->query;
    }

    public function generateRandomDiscountCode($length = 20)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return 'BARGAIN_' . $randomString;
    }
}
