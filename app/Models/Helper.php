<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Helper extends Model
{
    //
    use SoftDeletes;

    // $table->unsignedInteger('brand_id');
    // $table->unsignedInteger('user_id');
    // $table->unsignedInteger('user_active_id')->nullable();
    // $table->string('title');
    // $table->string('content');
    // $table->integer('status'); //0 open //1 pending //2 success //3 closed
    protected $fillable = [
        'brand_id',
        'user_id',
        'user_active_id',
        'title',
        'content',
        'status',
        'remark',
    ];

    public function brand() {
        return $this->belongsTo(Brand::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function userActive() {
        return $this->belongsTo(User::class);
    }

    public function comments() {
        return $this->hasMany(HelperComment::class);
    }

}
