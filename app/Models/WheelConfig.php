<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WheelConfig extends Model
{
    //
    protected $fillable = [
        'brand_id',
        'user_id',
        'slot_amount',
        'time_hour',
        'type_condition',
        'amount_condition',
        'code_youtube',
        'time_youtube',
        'status'
    ];

    public function brand() {
        return $this->belongsTo(Brand::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function wheelSlotEights() {
        return $this->hasMany(WheelSlotConfig::class)->where('slot_amount','=',8);
    }

    public function wheelSlotTens() {
        return $this->hasMany(WheelSlotConfig::class)->where('slot_amount','=',10);
    }
}
