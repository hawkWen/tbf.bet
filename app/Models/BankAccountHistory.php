<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class BankAccountHistory extends Model
{
    //

    // $table->unsignedInteger('brand_id');
    // $table->unsignedInteger('bank_account_id');
    // $table->unsignedInteger('user_id');
    // $table->unsignedInteger('table_id');
    // $table->decimal('amount',12,2);
    // $table->string('table');
    // $table->integer('type')->default(1); //1 plus //2 decrease //3 other;
    // $table->text('remark')->nullable();
    protected $fillable = [
        'brand_id',
        'bank_account_id',
        'user_id',
        'table_id',
        'amount',
        'table',
        'type',
        'remark',
    ];  

    public function bankAccount() {
        return $this->belongsTo(BankAccount::class)->withTrashed();
    }

    public function brand() {
        return $this->belongsTo(Brand::class);
    }

    public function user() {
        return $this->belongsTo(User::class)->withTrashed();
    }
}
