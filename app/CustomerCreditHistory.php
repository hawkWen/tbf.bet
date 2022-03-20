<?php

namespace App;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;

class CustomerCreditHistory extends Model
{
    //
    protected $fillable = [
        'brand_id',
        'customer_id',
        'customer_deposit_id',
        'custoemr_withdraw_id',
        'promotion_id',
        'amount_before',
        'amount',
        'amount_after',
        'type',
    ];

    public function customer() {
        return $this->belongsTo(Customer::class);
    }

    public function deposit() {
        return $this->belongsTo(CustomerDeposit::class);
    }

    public function promotion() {
        return $this->belongsTo(Promotion::class);
    }
     
}
