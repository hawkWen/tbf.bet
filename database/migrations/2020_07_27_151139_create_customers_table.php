<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('brand_id');
            $table->unsignedInteger('game_id');
            $table->unsignedInteger('bank_id');
            $table->unsignedInteger('bank_transfer_id')->default(0);
            $table->unsignedInteger('promotion_id')->nullable();
            $table->unsignedInteger('invite_id')->nullable();
            $table->string('line_user_id')->nullable();
            $table->unsignedInteger('app_id')->nullable();
            $table->string('code_bank')->nullalbe();
            $table->text('img')->nullable();
            $table->text('img_url')->nullable();
            $table->text('line_token')->nullable();
            $table->string('name');
            $table->string('telephone')->nullable();
            $table->string('line_id')->nullable();
            $table->string('email')->nullable();
            $table->string('username');
            $table->string('password');
            $table->string('password_generate');
            $table->string('bank_account');
            $table->string('bank_account_scb');
            $table->string('bank_account_krungsri');
            $table->string('bank_account_kbank');
            $table->string('from_type');
            $table->string('from_type_remark');
            $table->string('line_menu_member')->nullable();
            $table->string('last_login');
            $table->string('operation');
            $table->string('browser');
            $table->string('ip');
            $table->decimal('credit',12,2)->default(0.00);
            $table->dateTime('last_update_credit')->nullable();
            $table->dateTime('last_update_password')->nullable();
            $table->string('invite_url')->nullable();
            $table->decimal('invite_bonus')->nullable();
            $table->integer('status')->default(1); //1 active //0 unactive
            $table->integer('type')->default(1); //1 normal //0 blacklist
            $table->integer('status_credit')->default(1); //1 active //0 unactive
            $table->integer('status_password')->default(1); //0 please reset password //1 reset password already
            $table->integer('status_invite')->default(0); //0 not recive // 1 received
            $table->integer('agent_order')->default(1);
            $table->text('update_log');
            $table->rememberToken();
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
        Schema::dropIfExists('customers');
    }
}
