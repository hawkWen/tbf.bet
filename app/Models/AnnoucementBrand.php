<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnnoucementBrand extends Model
{
    //
    protected $fillable = [
        'brand_id',
        'annoucement_id',
        'active'
    ];

    public function brand() {
        return $this->belongsTo(Brand::class);
    }

    public function annoucement() {
        return $this->belongsTo(Annoucement::class);
    }
}
