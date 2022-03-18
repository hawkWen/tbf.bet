<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class UserEvent extends Model
{
    //
    protected $fillable = [
        'brand_id',
        'user_id',
        'description'
    ];

    public function user() {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function brand() {
        return $this->belongsTo(Brand::class);
    }
}
