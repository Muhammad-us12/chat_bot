<?php

namespace App\Http\Controllers;

use App\Domain\Bargain\Actions\NewPriceBeatOffer;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;

class PriceBeatController extends Controller
{
    public function create(Request $request, NewPriceBeatOffer $newPriceBeatOffer)
    {
        
        $priceBeatRequestParams = $request->validate([
            'name' => ['string', 'required', 'min:2'],
            'email' => ['string', 'required', 'email'],
            'competitor_url' => ['string', 'required', 'url'],
            'variant_id' => ['numeric', 'required', 'exists:products,shopify_id']
        ]);

        $priceBeatRequestParams['ip'] = $request->ip();
       
        $store = $request->get('store');
        
        try {
            $store->shopifyClient()->getVariant($priceBeatRequestParams['variant_id']);
        } catch (RequestException $e) {
            return back()
                ->withErrors(['variant_id' => "Invalid variant Id"])
                ->withInput();
        }
        $newPriceBeatOffer->execute($store, $priceBeatRequestParams);

        return \response()->json(['msg' => 'Price beat offer received successfully.', 'data' => [], 'error' => false]);
    }
}
