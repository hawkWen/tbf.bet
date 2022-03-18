<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWheelSlotConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wheel_slot_configs', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('wheel_config_id');
            $table->unsignedInteger('promotion_id');
            $table->string('type'); //1 เครดิตฟรี  // ของรางวัลอื่นๆ 
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
        Schema::dropIfExists('wheel_slot_configs');
    }
}
