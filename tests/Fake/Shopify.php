<?php

namespace Tests\Fake;

use Tests\Fake\TestFaker;

class Shopify extends TestFaker
{
    public static function variant(array $attrs = []): array
    {
        return array_merge([
            'id' => self::faker()->randomNumber(3),
            'title' => self::faker()->words(asText: true),
            'price' => self::faker()->randomDigitNotNull(),
            'product_id' => self::faker()->randomNumber(3),
        ], $attrs);
    }

    public static function product(array $attrs = []): array
    {
        return array_merge([
            'id' => self::faker()->randomNumber(3),
            'title' => self::faker()->words(asText: true),
            'vendor' => self::faker()->word(),
            'variants' => [self::variant(), self::variant()],
        ], $attrs);
    }

    public static function discountCode(array $attrs = []): array
    {
        $code = self::faker()->uuid . self::faker()->randomNumber(3, true);
        return array_merge(['data' => [
            'discountCodeBasicCreate' => [
                'codeDiscountNode' => [
                    'id' => "gid://shopify/DiscountCodeNode/" . self::faker()->randomNumber(5) . self::faker()->randomNumber(5),
                    'codeDiscount' => ['codes' => ['nodes' => [['code' => $code]]]]
                ]
            ]
        ]], $attrs);
    }

    public static function fakevariantForCreateOffer(array $attrs = []): array
    {
        return array_merge(['id' => '32222705483865', 'title' => 'RangRover121', 'price' => 230, 'product_id' => '13222270548386512'], $attrs);
    }

    public static function fakeProductForCreateOffer(array $attrs = []): array
    {
        return array_merge(['id' => '13222270548386512', 'title' => 'RangRover', 'vendor' => 'robustweb', 'variants' => [['id' => '32222705483865', 'price' => 230,'title' => 'RangRover121', 'displayName' => 'Rang Rover']], 'tags' => ['OLA-Product']], $attrs);
    }

    public function priceRule(){
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

    public function Customer()
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

    public function DiscountCodeWithRestApi(){
        return [
            "id" => 1054381139,
            "price_rule_id" => 996606715,
            "code" => "SUMMERSALE10OFF",
            "usage_count" => 0,
            "created_at" => "2023-10-03T13:22:41-04:00",
            "updated_at" => "2023-10-03T13:22:41-04:00"
        ];
    }

    public function productWithGraphQl(){
        return [
                "id" => "gid://shopify/Product/7847013154973",
                "title" => "The 3p Fulfilled Snowboard",
                "handle" => "the-3p-fulfilled-snowboard",
                "vendor" => "UsamaAsgharStore",
                "tags" => ["Accessory","Sport","Winter"],
                "variants" => [
                    "edges" => [
                        [
                            "node"=>[
                                "id" => "gid://shopify/ProductVariant/44091188215965",
                                "displayName" => "The 3p Fulfilled Snowboard - Default Title",
                                "price" => "2629.95",
                            ]
                        ]
                    ]
                ]
            ];
    }

    public function variantWithGraphQl(){
        return [
            "id" => "gid://shopify/ProductVariant/44091188215965",
            "title"=> "Default Title",
            "price" => '2629.95',
            "product" => [
                "id"=> "gid://shopify/Product/7847013154973"
            ],
        ];
    }

    public function customerWithGraphQl(){
        return [
            "customer" => [
                "id" => "gid://shopify/Customer/6808641241245",
                "email"=> "usamaas@gmail.com",
            ]
        ];
    }

    public function priceRuleWithGraphQl(){
        return [
            "priceRule" => [
                "id" => "gid://shopify/PriceRule/1074339741853",
            ],
            "priceRuleDiscountCode" => [
                "id" => "gid://shopify/PriceRuleDiscountCode/14258988089501",
                "code" => "2343"
            ]
        ];
    }

    public static function fakeVariantForCreateOfferWithGraphQl(array $attrs = []): array
    {
        return array_merge(['id' => 'gid://shopify/ProductVariant/44091188215965', 'title' => 'Default Title', 'price' => 230, 'product' => ['id' => 'gid://shopify/Product/7847013154973']], $attrs);
    }

    public static function fakeProductForCreateOfferWithGraphQl(array $attrs = []): array
    {
        return array_merge(['id' => 'gid://shopify/Product/7847013154973', 'title' => 'RangRover', 'vendor' => 'robustweb', 'variants' => ['edges' => [['node' => ['id' => 'gid://shopify/ProductVariant/44091188215965', 'price' => 230, 'displayName' => 'Rang Rover']]]], 'tags' => ['OLA-Product']], $attrs);
    }
}
