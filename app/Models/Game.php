<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    //
    protected $connection = 'mysql';

    protected $fillable = [
        'name',
        'logo',
        'logo_url',
        'description',
        'url_web',
        'url_android',
        'url_ios'
    ];
    
    public function brands() {
        return $this->hasMany(Brand::class);
    }
}
