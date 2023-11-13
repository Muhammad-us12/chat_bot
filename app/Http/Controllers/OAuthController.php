<?php

namespace App\Http\Controllers;

use Closure;
use Shopify\Utils;
use App\Models\Store;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Jobs\AfterAuthenticateJob;
use Illuminate\Support\Facades\Cache;

class OAuthController extends Controller
{
    public function begin(Request $request)
    {
        $shop = \rescue(fn () => Utils::sanitizeShopDomain($request->get('shop', false)), false, false);
        if (!$shop) {
            return redirect(route('auth.login'));
        };

        // Redirecting from client side. (inside app.)
        if ($request->query("embedded", false) === "1") {
            $redirectUri = urlencode(\secure_url(\route('auth.begin', \compact('shop'), false)));
            $queryString = http_build_query(array_merge($request->query(), \compact('redirectUri')));

            return \redirect("/ExitIframe?$queryString", secure: true);
        }
        $state = Str::uuid()->toString();
        Cache::put($shop . '_state', $state, 300); // Setting state as cache.

        $query = [
            'client_id' => \config('services.shopify.key'),
            'scope' => \config('services.shopify.scopes'),
            'redirect_uri' => \url('/auth/callback', secure: true),
            'state' => $state,
            'grant_options[]' => '',
        ];

        return \redirect("https://{$shop}/admin/oauth/authorize?" . http_build_query($query), secure: true);
    }

    public function callback(Request $request)
    {
        $request->validate([
            'shop' => ['string', 'required', function (string $attribute, mixed $value, Closure $fail) {
                $shop = \rescue(fn () => Utils::sanitizeShopDomain($value), false, false);
                if (!$shop) {
                    $fail("The {$attribute} is invalid.");
                };
            }],
            'code' => ['string', 'required'],
            'host' => ['string', 'required'],
            'state' => ['string', 'required'],
        ]);

        $shop = Utils::sanitizeShopDomain($request->get('shop'));

        // Validating hmac.
        \abort_if(!(
            (strcmp(\cache($shop . '_state'), $request->get('state')) === 0) &&
            Utils::validateHmac($request->query(), config('services.shopify.secret'))
        ), 400);

        $accessTokenRes = \App\Clients\Shopify\Client::fetchAccessToken($shop, $request->query('code'));

        $store = Store::withTrashed()->updateOrCreate(
            ['name' => $shop],
            ['access_token' => $accessTokenRes['access_token'], 'scopes' => $accessTokenRes['scope']]
        );

        // If trashed, restore.
        !$store->trashed() ?: $store->restore();


        $decodedHost = base64_decode($request->get('host'), true);
        $redirectUrl = "https://$decodedHost/apps/" . \config('services.shopify.key');

        // Dispatching after authenticate job.
        AfterAuthenticateJob::dispatch($store);

        return redirect($redirectUrl, secure: true);
    }
}
