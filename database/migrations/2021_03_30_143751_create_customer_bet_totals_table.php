<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerBetTotalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_bet_totals', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('customer_id')->nullable();
            $table->string('username');
            $table->string('detail')->nullable();
            $table->integer('bet_count')->default(0);
            $table->decimal('turn_over',12,2)->default(0.00);
            $table->decimal('win_loss',12,2)->default(0.00);
            $table->decimal('bet',12,2)->default(0.00);
            $table->dateTime('start_date')->default(date('Y-m-d 00:00:00'));
            $table->dateTime('end_date')->default(date('Y-m-d 23:59:59'));
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
        Schema::dropIfExists('customer_bet_totals');
    }
}
