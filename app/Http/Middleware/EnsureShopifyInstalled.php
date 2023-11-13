<?php

namespace App\Http\Middleware;

use Closure;
use Shopify\Utils;
use App\Models\Store;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureShopifyInstalled
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $shop = $request->query('shop') ? Utils::sanitizeShopDomain($request->query('shop')) : null;

        $appInstalled = $shop && Store::where('name', $shop)->where('access_token', '<>', null)->exists();
        $isExitingIframe = preg_match("/^ExitIframe/i", $request->path());

        return ($appInstalled || $isExitingIframe) ? $next($request) : \redirect(\route('auth.begin', $request->query()), secure: true);
    }
}
