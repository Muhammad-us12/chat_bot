<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;
use Shopify\Clients\HttpHeaders;
use App\Http\Controllers\Controller;
use Shopify\Exception\InvalidWebhookException;

class WebhookController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $headers = $this->validateRequest($request);

        $topic = strtoupper(str_replace(['/', '.'], '_', $headers->get(HttpHeaders::X_SHOPIFY_TOPIC)));
        $shop = $headers->get(HttpHeaders::X_SHOPIFY_DOMAIN);
        $payload = $request->getContent();

        \info("request has come", \compact('topic', 'shop', 'payload'));
        if ($topic == 'APP_UNINSTALLED') {
            $store = Store::where('name', $shop);
            if ($store) {
                \info("Store deleted successfully");
                $store->update(['access_token' => null]);
                $store->delete();
            }
        }

        return \response("");
    }

    private function validateRequest(Request $request): HttpHeaders
    {

        if (empty($request->getContent())) {
            throw new InvalidWebhookException("No body was received when processing webhook");
        }

        $headers = new HttpHeaders($request->header());

        $missingHeaders = $headers->diff(
            [HttpHeaders::X_SHOPIFY_HMAC, HttpHeaders::X_SHOPIFY_TOPIC, HttpHeaders::X_SHOPIFY_DOMAIN],
            false,
        );

        if (!empty($missingHeaders)) {
            $missingHeaders = implode(', ', $missingHeaders);
            throw new InvalidWebhookException(
                "Missing one or more of the required HTTP headers to process webhooks: [$missingHeaders]"
            );
        }
        // validating hmac
        $hmac = $headers->get(HttpHeaders::X_SHOPIFY_HMAC);
        if ($hmac !== base64_encode(hash_hmac('sha256', $request->getContent(), \config('services.shopify.secret'), true))) {
            throw new InvalidWebhookException("Could not validate webhook HMAC");
        }

        return $headers;
    }
}
