<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VariantDiscount extends Model
{
    protected $fillable = ['variant_id', 'discount_percentage'];
}
