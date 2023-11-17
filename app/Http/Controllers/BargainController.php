<?php

namespace App\Http\Controllers;

use App\Domain\Bargain\Entities\Bargain;
use Domain\Bargain\Aggregates\ProductGroupAggregate;
use Domain\Bargain\Entities\ProductGroup;
use Illuminate\Http\Request;

class BargainController extends Controller
{
    public function create(Request $request)
    {
        $bargainRequestParams = $request->validate([
            'product_group_id' => ['required', 'numeric', 'exists:'.ProductGroup::class.',id'],
            'value' => ['required', 'numeric',],
            'type' => ['required', 'string', \Illuminate\Validation\Rule::in(['percentage', 'fixed'])],
        ]);

        $productGroupAggregate = new ProductGroupAggregate(ProductGroup::find($bargainRequestParams['product_group_id']));
        $productGroupAggregate->addBargainRule(new Bargain($bargainRequestParams));

        return \response()->json([
            'error' => false,
            'msg' => 'Bargain rule created successfully',
            'data' => []
        ], 201);
    }
}
