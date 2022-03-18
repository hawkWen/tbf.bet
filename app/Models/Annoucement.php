<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Annoucement extends Model
{
    //
    protected $fillable = [
        'user_id',
        'title',
        'content',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
