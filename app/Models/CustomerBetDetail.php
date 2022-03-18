<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerBetDetail extends Model
{
    //
    protected $fillable = [
        'username',
        'detail',
        'game',
        'bet_count',
        'turn_over',
        'win_loss',
        'bet',
        'total',
        'bet_date',
        'status',
        'status_invite'
    ];

    protected $dates = [
        'bet_date',
    ];

    public function customerBet() {
        return $this->hasMany(CustomerBet::class,'username');
    }
}
