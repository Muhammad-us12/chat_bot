<?php

namespace App\Models;

use App\Models\Customer;
use App\Models\Store;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PriceBeatOffer extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id', 'competitor_url', 'store_id', 'variant_id', 'variant_name', 'product_name', 'product_id', 'offered_amount', 'actual_amount', 'status'
    ];

    public function getDiscountAmount(): float
    {
        return $this->actual_amount - $this->offered_amount;
    }

    /**
     * How much percentage discount is being asked
     */
    public function getPercentageDiscount(): float
    {
        return ($this->offered_amount * 100) / $this->actual_amount;;
    }

    public function Store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function discountCode(): HasOne
    {
        return $this->hasOne(DiscountCode::class, 'offer_id');
    }
}
