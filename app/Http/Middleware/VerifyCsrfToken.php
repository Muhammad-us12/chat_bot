<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
  
    protected $except = [
        '*/product-group',
        '*/product/*',
        '*/bargain',
        '*/bargain/*',
        '*/price-beat-offer',
        '*/offer',
        '*/variant-discount/*'
    ];
}
