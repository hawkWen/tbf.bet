<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HelperComment extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'helper_id',
        'user_id',
        'comment',
    ];

    public function helper() {
        return $this->belongsTo(Helper::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
