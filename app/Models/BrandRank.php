<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrandRank extends Model
{
    //
    protected $fillable = [
        'brand_id',
        'rank',
        'min',
        'reward',
        'description',
    ];

    public function brand() {
        return $this->belongsTo(Brand::class);
    }
}
