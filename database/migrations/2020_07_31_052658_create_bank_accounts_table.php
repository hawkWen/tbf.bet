<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('bank_id');
            $table->unsignedInteger('brand_id');
            $table->string('name');
            $table->string('account');
            $table->decimal('amount',12,2)->default(0.00);
            $table->string('username')->nullable(); //scb username 
            $table->string('password')->nullable(); //scb password
            $table->string('pin')->nullable(); //scb password
            $table->string('otp')->nullable(); //scb password
            $table->string('refer')->nullable(); //scb passwords
            $table->string('app_id')->nullable();
            $table->string('token')->nullable();
            $table->text('url_data')->nullable();
            $table->integer('type')->default(1); //0 mixauto //1 ขาเข้า //2 สำรองแมนวล //3 ขาออก //4 ขาออกสำรอง //5 กลาง //6 ขาเข้า SCBEASY SMS // 7 ขาออก SCBEASY SMS //8 ขาเข้า truemoney //9 ขาเข้า SCB PIN //10 ขาออก SCB PIN //11 MIX SCB PIN
            $table->integer('status')->default(1);
            $table->integer('status_bot')->default(0);
            $table->integer('status_transaction')->default(0);
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
        Schema::dropIfExists('bank_accounts');
    }
}
