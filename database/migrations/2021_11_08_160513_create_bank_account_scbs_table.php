<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankAccountScbsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_account_scbs', function (Blueprint $table) {
            $table->id();
            $table->string('bank_account');
            $table->string('name');
            $table->string('pin');
            $table->string('telephone');
            $table->string('device_id');
            $table->string('personal_id');
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
        Schema::dropIfExists('bank_account_scbs');
    }
}
