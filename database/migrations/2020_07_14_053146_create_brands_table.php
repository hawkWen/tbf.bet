<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBrandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('game_id');
            $table->string('logo');
            $table->string('logo_url');
            $table->string('name');
            $table->string('line_id')->nullable();
            $table->string('website')->nullable();
            $table->string('telephone')->nullable();
            $table->text('line_token')->nullable();
            $table->text('line_channel_secret')->nullable();
            $table->text('line_liff_connect')->nullable();
            $table->text('line_liff_connect_react')->nullable();
            $table->text('line_liff_register')->nullable();
            $table->text('line_liff_transfer')->nullable();
            $table->text('line_liff_info')->nullable();
            $table->text('line_channel_secret')->nullable();
            $table->text('line_menu_register')->nullable();
            $table->text('line_menu_member')->nullable();
            $table->string('subdomain');
            $table->string('agent_username')->nullable();
            $table->string('agent_password')->nullable();
            $table->string('agent_username_2')->nullable();
            $table->string('agent_password_2')->nullable();
            $table->decimal('agent_credit', 12,2)->default(0.00);
            $table->text('agent_member_value')->nullable();
            $table->decimal('credit_remain', 12,2)->default(0.00);
            $table->decimal('cost_service', 12,2)->default(0.00);
            $table->decimal('cost_working',12,2)->default(0.00);
            $table->decimal('deposit_min',12,2)->default(0.00);
            $table->decimal('withdraw_min',12,2)->default(0.00);
            $table->decimal('withdraw_auto_max',12,2)->default(0.00);
            $table->decimal('stock',12,2)->default(0.00);
            $table->integer('type_deposit')->default(1); // 1 BOT //2 Slip;
            $table->integer('status_telephone')->default(1); //เก็บข้อมูลเบอร์โทรศัพท์
            $table->integer('status_line_id')->default(1); //เก็บข้อมูลเบอร์โทรศัพท์
            $table->integer('noty_register')->default(1);
            $table->integer('noty_deposit')->default(1);
            $table->integer('noty_withdraw')->default(1);
            $table->integer('invite')->default(0); //0 close //1 open
            $table->decimal('invite_min',12,2)->default(0);
            $table->integer('invite_deposit_type')->default(1); //1 register //2 deposits
            $table->integer('invite_type')->default(1); //1 deposit //2 winloss (manual)
            $table->integer('invite_cost',12,2)->default(0);
            $table->string('bot_ip')->nullable();
            $table->integer('status_bot')->default(0);
            $table->integer('status_bot_bank')->default(1);
            $table->integer('status_bot_deposit')->default(1);
            $table->integer('status_bot_withdraw')->default(1);
            $table->dateTime('last_update_credit_remain')->nullable();
            $table->integer('type_api')->default(1); //1 yeahteam //2 askmebet;
            $table->integer('status_rank')->defaul(0); //0 close //1 open;
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
        Schema::dropIfExists('brands');
    }
}
