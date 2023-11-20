<?php

namespace App\Clients\IpLocate;

use Illuminate\Support\Facades\Http;

class IpLocation
{
    public static function getLocation(string $ip)
    {
        return Http::retry(3, 5)->throw()->get('https://www.iplocate.io/api/lookup/' . $ip)->json();
    }
}
