<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class BankAccountReceive extends Model
{
    //
    protected $fillable = [
        'brand_id',
        'user_id',
        'bank_account_id',
        'amount',
        'remark',
        'slip',
        'slip_url'
    ];

    public function brand() {
        return $this->belongsTo(Brand::class);
    }

    public function user() {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function bankAccount() {
        return $this->belongsTo(BankAccount::class,'bank_account_id');
    }
}
