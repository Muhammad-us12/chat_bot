<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;
    
    protected $fillable = ['user_id', 'name', 'email', 'country', 'city', 'browser', 'os','shopify_id','offer_count'];

    public function offers()
    {
        return $this->hasMany(Offer::class);
    }

    public function discountCodes()
    {
        return $this->hasMany(DiscountCode::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customerOffered()
    {
        return $this->hasMany(Offer::class,'customer_id');
    }


    public function hasOffered($productId, $variantId, $shop)
    {
        if (Offer::where('customer_id', $this->id)->where('product_id', $productId)->where('variant_id', $variantId)->where('store_id', $shop['id'])->where('status', 'pending')->exists()) {
            return true;
        } elseif (Offer::where('customer_id', $this->id)->where('product_id', $productId)->where('variant_id', $variantId)->where('store_id', $shop['id'])->where('status', 'denied')->where('enable_offer', '=', 0)->exists()) {
            return true;
        } elseif (Offer::where('customer_id', $this->id)->where('product_id', $productId)->where('variant_id', $variantId)->where('store_id', $shop['id'])->where('status', 'denied')->where('enable_offer', 1)->exists()) {
            return false;
        }
        return false;
    }
}
