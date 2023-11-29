<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TagDiscount extends Model
{
    protected $fillable = ['product_id', 'tag_name', 'discount_percentage'];
}
