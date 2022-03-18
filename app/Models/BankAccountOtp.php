<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankAccountOtp extends Model
{
    //
    protected $fillable = [
        'refer',
        'otp',
        'status'
    ];  
    
}
