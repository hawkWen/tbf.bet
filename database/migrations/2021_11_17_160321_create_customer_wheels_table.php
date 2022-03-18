<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerWheelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_wheels', function (Blueprint $table) {
            $table->id();
            $table->string('customer_id');
            $table->string('wheel_config_id');
            $table->string('wheel_slot_config_id');
            $table->string('chance'); //defaul 100%;
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
        Schema::dropIfExists('customer_wheels');
    }
}
