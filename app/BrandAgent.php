<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BrandAgent extends Model
{
    //
    protected $fillable = [
        'brand_id',
        'agent_prefix',
        'agent_username',
        'agent_password',
        'agent_order',
    ];

    public function brand() {
        return $this->belongsTo(Brand::class);
    }
}
