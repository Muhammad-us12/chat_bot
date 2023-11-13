<?php

namespace App\Http\Middleware;

use Closure;
use Shopify\Utils;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CspHeader
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $shop = Utils::sanitizeShopDomain($request->query('shop', ''));

        $domainHost = $shop ? "https://$shop" : "*.myshopify.com";
        $allowedDomains = "$domainHost https://admin.shopify.com";

        /** @var Response $response */
        $response = $next($request);

        $currentHeader = $response->headers->get('Content-Security-Policy');
        if ($currentHeader) {
            $values = preg_split("/;\s*/", $currentHeader);

            // Replace or add the URLs the frame-ancestors directive
            $found = false;
            foreach ($values as $index => $value) {
                if (mb_strpos($value, "frame-ancestors") === 0) {
                    $values[$index] = preg_replace("/^(frame-ancestors)/", "$1 $allowedDomains", $value);
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                $values[] = "frame-ancestors $allowedDomains";
            }

            $headerValue = implode("; ", $values);
        } else {
            $headerValue = "frame-ancestors $allowedDomains;";
        }


        $response->headers->set('Content-Security-Policy', $headerValue);

        return $response;
    }
}
