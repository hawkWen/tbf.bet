<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerWheel extends Model
{
    //
    protected $fillable = [
        'customer_id',
        'wheel_slot_config_id',
        'wheel_slot_config_type',
        'promotion_id',
        'other',
        'credit',
    ];

    public function customer() {
        return $this->belongsTo(Customer::class);
    }

    public function promotion() {
        return $this->belongsTo(Promotion::class);
    }

    public function wheelSlotConfig() {
        return $this->belongsTo(WheelSlotConfig::class);
    }
}
