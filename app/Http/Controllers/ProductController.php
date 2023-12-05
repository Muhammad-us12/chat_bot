<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Clients\Shopify\Client;

class ProductController extends Controller
{
    public function productList(Request $request,Client $shopify)
    {
        $store = $request->get('store');
        $shopify = app(Client::class, ['store' => $store]);
        $allProducts = $shopify->getAllProduct();
        return response()->json([
            'error' => false,
             'message' => '',
             'data' => [
                'products' => $allProducts
             ]
             ]);
        // dd($allProducts);
    }
}
