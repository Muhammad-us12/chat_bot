<?php

namespace App\Http\Controllers\Product;

use Domain\Bargain\Actions\CreateProductGroup;
use App\Models\User;
use App\Models\Store;
use App\Http\Controllers\Controller;
use Domain\Bargain\Enums\ProductGroupType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;


class ProductGroupController extends Controller
{
    //
    public function create(Request $request, CreateProductGroup $createProductGroup){
        $requestParams = $request->validate([
            'name' => ['string', 'required', 'min:1', 'max:255'],
            'type' => ['required', Rule::in(array_column(ProductGroupType::cases(), 'value'))]
        ]);

        
        
        $store = Store::firstWhere('name', $request->get('store')->name);
        $createProductGroup->execute($store, $requestParams['name'], ProductGroupType::from($requestParams['type']));

        return \response()->json([
            'error' => false,
            'msg' => 'Group created successfully',
            'data' => []
        ]);
    }
}
