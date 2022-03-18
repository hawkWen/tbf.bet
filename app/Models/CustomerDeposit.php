<?php

namespace App\Models;

use App\User;
use App\Models\Promotion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerDeposit extends Model
{
    use SoftDeletes;
    //
    // $table->unsignedInteger('brand_id');
    // $table->unsignedInteger('customer_id');
    // $table->unsignedInteger('game_id');
    // $table->unsignedInteger('promotion_id');
    // $table->unsignedInteger('user_id'); //0 bot // etc manual
    // $table->string('slip')->nullable();
    // $table->string('slip_url')->nullable();
    // $table->string('username');
    // $table->string('name');
    // $table->decimal('amount',12,2)->default(0.00);
    // $table->decimal('bonus',12,2)->default(0.00);
    // $table->integer('type_deposit')->default(1); //1 bot //2 manual slip
    // $table->integer('status')->default(0); //wait //1complete 
    protected $fillable = [
        'brand_id',
        'customer_id',
        'game_id',
        'promotion_id',
        'user_id',
        'bank_account_id',
        'slip',
        'slip_url',
        'username',
        'name',
        'amount',
        'bonus',
        'type_deposit',
        'type_manual',
        'status',
        'status_invite',
        'remark',
        'transfer_at'
    ];

    public function brand() {
        return $this->belongsTo(Brand::class);
    }

    public function customer() {
        return $this->belongsTo(Customer::class);
    }

    public function promotion() {
        return $this->belongsTo(Promotion::class)->withTrashed();
    }

    public function user() {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function bankAccount() {
        return $this->belongsTo(BankAccount::class)->withTrashed();
    }
}
