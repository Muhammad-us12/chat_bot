<?php

namespace App\Http\Controllers;

use App\Clients\Shopify\Client;
use App\Models\Store;
use App\Models\VariantDiscount;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function store(Request $request, int $variantId, Client $shopify){
        
        $store = $request->get('store');
        $shopify = app(Client::class, ['store' => $store]);
        if (VariantDiscount::where('variant_id', $variantId)->exists()) {
            return response()->json(['error' => true, 'message' => 'You can not make discount on this variant twice']);
        }

        $variant = $shopify->getVariant($variantId);
        if (empty($variant)) {
            return response()->json(['error' => true, 'message' => 'No variant exists with this Id']);
        }
        VariantDiscount::create([
            'variant_id' => $variantId,
            'discount_percentage' => $request['discount_percentage']
        ]);
        return response()->json(['error' => false, 'message' => 'Variant Discount has been created']);
    }
}
