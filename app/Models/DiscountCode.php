<?php

namespace App\Models;

use App\Models\PriceBeatOffer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DiscountCode extends Model
{
    use HasFactory;
    
    protected $fillable = ['offer_id', 'shopify_id', 'code','variant_id'];

    public function offer()
    {
        return $this->belongsTo(PriceBeatOffer::class);
    }
}
