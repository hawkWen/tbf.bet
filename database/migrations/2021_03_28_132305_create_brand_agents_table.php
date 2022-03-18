<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBrandAgentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('brand_agents', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('brand_id');
            $table->string('agent_prefix');
            $table->string('agent_username');
            $table->string('agent_password');
            $table->integer('agent_order');
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
        Schema::dropIfExists('brand_agents');
    }
}
