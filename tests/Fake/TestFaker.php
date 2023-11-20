<?php

namespace Tests\Fake;

use Faker\Factory;
use Faker\Generator;

class TestFaker
{
    private static $faker;

    protected static function faker(): Generator
    {
        if (!isset(self::$faker)) {
            self::$faker = Factory::create();
        }

        return self::$faker;
    }
}
