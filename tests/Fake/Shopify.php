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
}
