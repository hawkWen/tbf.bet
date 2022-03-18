<?php

namespace App;

use App\UserRole;
use App\Models\Brand;
use App\Models\UserEvent;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use SoftDeletes;
    use Notifiable;

    protected $connection = 'mysql';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_role_id',
        'brand_id',
        'img',
        'img_url',
        'name',
        'username',
        'password',
        'last_login',
        'browser',
        'operation',
        'ip',
        'status',
        'last_session',
    ];

    protected $dates = [ 
        'last_login',
        'deleted_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function userRole() {
        return $this->belongsTo(UserRole::class);
    }

    public function brand() {
        return $this->belongsTo(Brand::class)->withTrashed();
    }
    
    public function events() {
        return $this->hasMany(UserEvent::class);
    }

    /**
     * Get all of the creditFrees for the Customer
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function creditFrees(): HasMany
    {
        return $this->hasMany(CreditFree::class, 'foreign_key', 'local_key');
    }
}
