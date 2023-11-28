<?php

namespace App\Http\Controllers\Product;

use Domain\Bargain\Actions\CreateProductGroup;
use App\Models\User;
use App\Models\Store;
use Domain\Bargain\Entities\Product;
use Domain\Bargain\Entities\ProductGroup;
use App\Http\Controllers\Controller;
use Domain\Bargain\Enums\ProductGroupType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Domain\Bargain\Aggregates\ProductGroupAggregate;

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

    public function addProduct(Request $request, ProductGroup $group)
    {
        $requestParams = $request->validate([
            'shopify_id' => ['numeric', 'required'],
            'name' => ['string', 'required'],
        ]);
        $product = new Product(['product_group_id' => $group->id] + $requestParams);
        $productGroupAggregate = new ProductGroupAggregate($group);
        $result = $productGroupAggregate->addProduct($product);
        if($result){
            return \response()->json([
                'error' => false,
                'msg' => 'Product Added successfully',
                'data' => []
            ]);
        }

    }
}
