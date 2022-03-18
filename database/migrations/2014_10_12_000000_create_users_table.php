<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_role_id');
            $table->unsignedInteger('brand_id');
            $table->string('img')->nullable();
            $table->string('img_url')->nullable();
            $table->string('name');
            $table->string('username')->unique();
            $table->string('password');
            $table->dateTime('last_login')->nullable();
            $table->string('browser')->nullable();
            $table->string('operation')->nullable();
            $table->string('ip')->nullable();
            $table->integer('status')->default(1);
            $table->string('last_session')->nullable();
            $table->softDeletes();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
