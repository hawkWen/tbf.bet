<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Promotion extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'brand_id',
        'img',
        'img_url',
        'name',
        'cost',
        'min',
        'min_break_promotion',
        'max',
        'withdraw_max',
        'withdraw_max_type',
        'turn_over',
        'type_game',
        'type_turn_over',
        'type_cost',
        'type_promotion',
        'type_promotion_invite',
        'type_promotion_cost',
        'amount_per_day',
        'status',
        'active',
    ];
}
