<?php

namespace App\Models;

use App\User;
use App\Models\Brand;
use Illuminate\Database\Eloquent\Model;

class BankAccountTransaction extends Model
{
    //
    protected $fillable = [
        'bank_id',
        'brand_id',
        'bank_account_id',
        'customer_deposit_id',
        'user_id',
        'code',
        'code_type',
        'code_bank',
        'code_date',
        'description',
        'amount',
        'status',
        'user_id',
        'remark',
        'remark_description',
        'transfer_at',
        'bank_account',
        'unix_time',
        'log',
        'type',
    ];

    protected $dates = [
        'transfer_at',
    ];

    public function bankAccount() {
        return $this->belongsTo(BankAccount::class)->withTrashed();
    }

    public function deposit() {
        return $this->belongsTo(CustomerDeposit::class,'customer_deposit_id');
    }

    public function user() {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function bank() {
        return $this->belongsTo(Bank::class);
    }

    public function brand() {
        return $this->belongsTo(Brand::class);
    }

    public function scbCustomers() {
        return $this->hasMany(Customer::class,'bank_account_scb','bank_account');
    }

}
