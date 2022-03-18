<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankAccountTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 'bank_id',
        // 'brand_id',
        // 'bank_account_id',
        // 'customer_deposit_id',
        // 'code_date',
        // 'description',
        // 'amount',
        // 'status',
        // 'user_id',
        // 'remark',
        // 'remark_description',
        // 'transfer_at',
        // 'bank_account',
        // 'unix_time'
        Schema::create('bank_account_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('bank_id');
            $table->unsignedInteger('brand_id');
            $table->unsignedInteger('bank_account_id');
            $table->unsignedInteger('user_id')->default(0);
            $table->unsignedInteger('customer_deposit_id')->nullable();
            $table->string('code_bank')->nullable();
            $table->string('code_date')->nullable();
            $table->string('bank_account');
            $table->string('description')->nullable();
            $table->decimal('amount',12,2);
            $table->text('remark')->nullable();
            $table->integer('status')->default(0); //0 not link //1 wait manual //2 linked //3 api error //4 account not found //5 unique; //7 ติดโปร รอดำเนินการ
            $table->string('unix_time');
            $table->string('transfer_at');
            $table->string('log');
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
        Schema::dropIfExists('bank_account_transactions');
    }
}
