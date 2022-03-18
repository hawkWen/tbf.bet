<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBrandRanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('brand_ranks', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('brand_id');
            $table->string('rank');
            $table->decimal('min',12,2)->default(0.00);
            $table->decimal('reward',12,2)->default(0.00);
            $table->text('description')->nullable();
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
        Schema::dropIfExists('brand_ranks');
    }
}
