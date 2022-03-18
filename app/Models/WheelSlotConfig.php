<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WheelSlotConfig extends Model
{
    //
    protected $fillable = [
        'wheel_config_id',
        'slot_amount',
        'promotion_id',
        'promotion_other',
        'credit',
        'chance',
        'type',
    ];

    public function promotion() {
        return $this->belongsTo(Promotion::class);
    }
}
