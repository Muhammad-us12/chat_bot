<?php

namespace App\Http\Middleware;

use Closure;
use Shopify\Utils;
use App\Models\Store;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateShopifyStore
{

    public const REDIRECT_HEADER = 'X-Shopify-API-Request-Failure-Reauthorize';
    public const REDIRECT_URL_HEADER = 'X-Shopify-API-Request-Failure-Reauthorize-Url';

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $bearerPresent = preg_match("/Bearer (.*)/", $request->header('Authorization', ''), $bearerMatches);
        \abort_if(!$bearerMatches, 401);


        \Shopify\Context::$API_SECRET_KEY = \config('services.shopify.secret');
        $payload = Utils::decodeSessionToken($bearerMatches[1]);
        $shop = parse_url($payload['dest'], PHP_URL_HOST);

        // Getting store from database.
        $store = Store::where('name', $shop)->first();

        if ($store && $store->hasValidAccessToken()) {
            $request->attributes->set('store', $store);
            return $next($request);
        }
        // This response thing needs to be tested, and see the working of these headers in frontend react hook.
        return response('', 401, [
            self::REDIRECT_HEADER => '1',
            self::REDIRECT_URL_HEADER => \secure_url(\route('auth.begin', \compact('shop'))),
        ]);
    }
}
