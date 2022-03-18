<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromotionCost extends Model
{
    //
    protected $fillable = [
        'brand_id',
        'promotion_id',
        'customer_id',
        'username',
        'amount',
        'bonus',
        'status'
    ];

    public function brand() {
        return $this->belongsTo(Brand::class);
    }

    public function customer() {
        return $this->belongsTo(Customer::class);
    }

    public function promotion() {
        return $this->belongsTo(Promotion::class)->withTrashed();
    }
}
