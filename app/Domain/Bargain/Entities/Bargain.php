<?php

namespace App\Domain\Bargain\Entities;

use Database\Factories\BargainFactory;
use Illuminate\Database\Eloquent\Model;
use Domain\Bargain\Entities\ProductGroup;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bargain extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'value', 'product_group_id'];

    protected static function newFactory(): Factory
    {
        return BargainFactory::new();
    }

    public function productGroup(): BelongsTo
    {
        return $this->belongsTo(ProductGroup::class);
    }
}
