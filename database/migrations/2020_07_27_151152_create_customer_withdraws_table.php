<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerWithdrawsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_withdraws', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('brand_id');
            $table->unsignedInteger('customer_id');
            $table->unsignedInteger('game_id');
            $table->unsignedInteger('user_id'); //0 bot // etc manual
            $table->unsignedInteger('promotion_id');
            $table->unsignedInteger('promotion_cost_id');
            $table->unsignedInteger('bank_account_id');
            $table->string('username');
            $table->string('name');
            $table->decimal('amount',12,2)->default(0.00);
            $table->decimal('amount_promotion',12,2)->default(0.00);    
            $table->integer('type_withdraw')->default(1); //1 bot // 2manual
            $table->integer('status_credit')->default(0); //0 not cut //1 cut already
            $table->integer('status')->default(0); //0 wait // 1 manual // 2 success // 3 bot not online // 4 bank api error // 5 cancel
            $table->text('remark')->nullable();
            $table->string('refer')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_withdraws');
    }
}
