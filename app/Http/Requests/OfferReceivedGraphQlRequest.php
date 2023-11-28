<?php

namespace App\Http\Requests;

use App\Clients\Objects\GraphQl\GetAProduct;
use App\Clients\Objects\GraphQl\GetProductVariant;
use App\Clients\Shopify\Client;
use App\Models\Store;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class OfferReceivedGraphQlRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'username' => 'required|string',
            'email' => 'required|email',
            'variant_offered_amount' => 'required|numeric',
            'variant_actual_amount' => 'required|numeric',
            'variant_id' => 'string|required',
            'product_id' => 'string|required',
            'browser' => 'required|string',
            'operating_system' => 'required|string',
            'store_name' => 'required|string'
        ];
    }

    public function passedValidation()
    {
        $store = Store::where('name', $this->get('store_name'))->first();
        $shopify = app(Client::class, ['store' => $store]);

        $shopify->shop = $store;

        $product = $shopify->getProductWithGraphQl($this->get('product_id'));
        $product = $product['data']['product'];

        if ($product === null) {
            throw ValidationException::withMessages(['product_id' => 'No product associated with this product ID']);
        }

        $productVariant = $shopify->getVariantWithGraphQl($this->get('variant_id'));
        $productVariant = $productVariant['data']['productVariant'];

        if ($productVariant === null) {
            throw ValidationException::withMessages(['variant_id' => 'No variant associated with this variant ID']);
        }


        if ($product['id'] !== $productVariant['product']['id']) {
            throw ValidationException::withMessages(['product_id' => 'This variant doesnot belongs to the product']);
        }
        
        if ($productVariant['price'] != $this->get('variant_actual_amount')) {
            throw ValidationException::withMessages(['variant_actual_amount' => 'Variant\'s actual amount is different']);
        }
        if ($this->get('variant_actual_amount') < $this->get('variant_offered_amount')) {
            throw ValidationException::withMessages(['variant_offered_amount' => 'Variant\'s offered amount should be less than variant\'s actual amount']);
        }

        
        $this->request->add(['variant_name'=>$productVariant['title']]);
        $this->request->add(['product_name'=>$product['title']]);
    }
}
