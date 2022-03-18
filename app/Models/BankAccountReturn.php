<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class BankAccountReturn extends Model
{
    //
    protected $fillable = [
        'brand_id',
        'bank_account_id',
        'user_id',
        'bank_id',
        'bank_account',
        'slip',
        'slip_url',
        'amount',
        'remark',
    ];

    public function bankAccount() {
        return $this->belongsTo(BankAccount::class)->withTrashed();
    }

    public function brand() {
        return $this->belongsTo(Brand::class);
    }

    public function user() {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function bank() {
        return $this->belongsTo(Bank::class);
    }
}
