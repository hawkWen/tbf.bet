<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerDepositsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_deposits', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('brand_id');
            $table->unsignedInteger('customer_id');
            $table->unsignedInteger('game_id');
            $table->unsignedInteger('promotion_id');
            $table->unsignedInteger('user_id')->default(0); //0 bot // etc manual
            $table->string('slip')->nullable();
            $table->string('slip_url')->nullable();
            $table->string('username');
            $table->string('name');
            $table->decimal('amount',12,2)->default(0.00);
            $table->decimal('bonus',12,2)->default(0.00);
            $table->integer('type_deposit')->default(1); //1 bot //2 manual slip
            $table->integer('status')->default(0); //wait //1complete 
            $table->text('remark')->nullable();
            $table->dateTime('transfer_at');
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
        Schema::dropIfExists('customer_deposits');
    }
}
