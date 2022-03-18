<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerBetDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_bet_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('customer_bet_id')->nullable();
            $table->unsignedInteger('customer_id')->nullable();
            $table->string('username');
            $table->string('detail')->nullable();
            $table->string('game')->default(0);
            $table->decimal('turn_over',12,2)->default(0.00);
            $table->decimal('win_loss',12,2)->default(0.00);
            $table->decimal('bet',12,2)->default(0.00);
            $table->decimal('total',12,2)->default(0.00);
            $table->dateTime('bet_date')->default(date('Y-m-d 00:00:00'));
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
        Schema::dropIfExists('customer_bet_details');
    }
}
