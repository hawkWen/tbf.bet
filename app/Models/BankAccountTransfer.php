<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class BankAccountTransfer extends Model
{
    //
    protected $fillable = [
        'brand_id',
        'user_id',
        'bank_account_to_id',
        'bank_account_from_id',
        'slip',
        'slip_url',
        'amount',
        'remark',
    ];

    public function brand() {
        return $this->belongsTo(Brand::class);
    }

    public function user() {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function bankAccountTo() {
        return $this->belongsTo(BankAccount::class,'bank_account_to_id');
    }

    public function bankAccountFrom() {
        return $this->belongsTo(BankAccount::class,'bank_account_from_id');
    }
}
