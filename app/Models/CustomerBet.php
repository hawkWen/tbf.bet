<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerBet extends Model
{
    //
    protected $fillable = [
        'customer_refer_id',
        'username',
        'detail',
        'bet_count',
        'turn_over',
        'turn_over_received',
        'win_loss',
        'bet',
        'start_date',
        'end_date',
        'status',
        'status_invite'
    ];

    protected $dates = [
        'start_date',
        'end_date',
    ];

    public function customer() {
        return $this->belongsTo(Customer::class,'username');
    }

    public function betDetails()
    {
        return $this->hasMany(CustomerBetDetail::class,'username');
    }
}
