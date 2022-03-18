<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromotionCostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promotion_costs', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('brand_id');
            $table->unsignedInteger('promotion_id');
            $table->unsignedInteger('customer_id');
            $table->string('username');
            $table->decimal('amount',12,2)->default(0.00);
            $table->decimal('bonus',12,2)->default(0.00);
            $table->integer('status')->default(1); //0 active //1 pass 
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
        Schema::dropIfExists('promotion_costs');
    }
}
