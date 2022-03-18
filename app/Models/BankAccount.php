<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankAccount extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'bank_id',
        'brand_id',
        'tmn_one_id',
        'name',
        'account',
        'app_id',
        'token',
        'username',
        'password',
        'otp',
        'refer',
        'pin',
        'url_data',
        'status',
        'status_bot',
        'status_web',
        'type',
        'amount',
        'logs',
        'active',
        'last_execution_time',
    ]; 

    public function bank() {
        return $this->belongsTo(Bank::class);
    }

    public function brand() {
        return $this->belongsTo(Brand::class);
    }

    public function transactions() {
        return $this->hasMany(BankAccountTransaction::class);
    }
}
