<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerRefer extends Model
{
    //
    protected $fillable = [
        'brand_id',
        'customer_id',
        'username',
        'refer_id',
        'status',
    ];

    /**
     * Get the user that owns the CustomerRefer
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
