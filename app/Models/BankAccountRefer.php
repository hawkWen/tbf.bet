<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankAccountRefer extends Model
{
    //
    protected $fillable = [
        'bank_account_id',
        'refer',
        'account',
        'status',
    ];  
    
}
