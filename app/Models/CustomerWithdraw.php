<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerWithdraw extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'brand_id',
        'customer_id',
        'game_id',
        'user_id',
        'promotion_id',
        'promotion_cost_id',
        'bank_account_id',
        'username',
        'name',
        'amount',
        'amount_promotion',
        'type_withdraw',
        'status_credit',
        'status',
        'remark',
        'refer',
    ];

    public function brand() {
        return $this->belongsTo(Brand::class);
    }

    public function customer() {
        return $this->belongsTo(Customer::class);
    }

    public function promotion() {
        return $this->belongsTo(Promotion::class)->withTrashed();
    }

    public function promotionCost() {
        return $this->belongsTo(PromotionCost::class);
    }

    public function user() {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function bankAccount() {
        return $this->belongsTo(BankAccount::class);
    }
}
