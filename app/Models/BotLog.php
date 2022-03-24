<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BotLog extends Model
{
    //
    protected $fillable = [
        'brand_id',
        'logs',
    ];

    public function brand() {
        return $this->belongsTo(Brand::class);
    }
}
