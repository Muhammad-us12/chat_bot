<?php

namespace Domain\Bargain\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use App\Domain\Bargain\Entities\Bargain;
use Database\Factories\ProductGroupFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id', 'name', 'type'
    ];

    protected static function newFactory(): Factory
    {
        return ProductGroupFactory::new();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function bargain(): HasOne
    {
        return $this->hasOne(Bargain::class);
    }
}
