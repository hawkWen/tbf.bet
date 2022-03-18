<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankAccountScb extends Model
{
    //
    protected $fillable = [
        'bank_account',
        'name',
        'pin',
        'telephone',
        'device_id',
        'personal_id',
        'remark',
    ];
}
