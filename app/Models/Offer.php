<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Offer extends Model
{
    use HasFactory;
    protected $fillable = ['customer_id', 'variant_id', 'variant_name', 'product_name', 'product_id', 'store_id', 'variant_offered_amount', 'variant_actual_amount', 'status'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function deny(Request $request)
    {
        $this->status = 'denied';
        if ($request['disable_user'] === 'on') {
            $this->enable_offer = 0;
        } else {
            $this->enable_offer = 1;
        }
        $this->save();
    }

    public function approve()
    {
        $this->status = 'Approved';
        $this->enable_offer = 1;
        $this->save();
    }

}
