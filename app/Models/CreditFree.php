<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class CreditFree extends Model
{
    //
    protected $fillable = [
        'brand_id',
        'customer_id',
        'promotion_id',
        'code',
        'user_id',
        'status',
    ];

    /**
     * Get the user that owns the CreditFree
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Get the customer that owns the CreditFree
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the promotion that owns the CreditFree
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function promotion()
    {
        return $this->belongsTo(Promotion::class)->withTrashed();
    }

    /**
     * Get the user that owns the CreditFree
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
