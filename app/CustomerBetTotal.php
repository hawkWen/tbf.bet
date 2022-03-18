<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerBetTotal extends Model
{
    //
    protected $fillable = [
        'username',
        'detail',
        'bet_count',
        'turn_over',
        'win_loss',
        'bet',
        'start_date',
        'end_date'
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
