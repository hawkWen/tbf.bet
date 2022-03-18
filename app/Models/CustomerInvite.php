<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerInvite extends Model
{
    //
    protected $fillable = [
        'invite_id',
        'username',
        'telephone',
        'url',
        'status',
    ];
}
