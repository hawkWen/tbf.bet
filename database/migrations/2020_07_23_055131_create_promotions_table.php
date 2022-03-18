<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromotionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('brand_id');
            $table->string('img')->nullable();
            $table->string('img_url')->nullable();
            $table->string('name');
            $table->decimal('cost')->default(0.00);
            $table->decimal('min')->default(0.00);
            $table->decimal('min_break_promotion')->default(20.00);
            $table->decimal('max')->default(0.00);
            $table->decimal('withdraw_max')->default(0.00);
            $table->decimal('turn_over')->default(0.00);
            $table->integer('type_turn_over')->default(1); //1 คิดตามจำนวนเงิน //2 คิดตาม win-loss
            $table->integer('type_cost')->default(1); //1 เพิ่มเป็นจำนวนเปอร์เซ็นต์ //2 เพิ่มเป็นจำนวนบาท
            $table->integer('type_promotion')->default(1); //1 เติมเงิน //2 ครั้งเดียวต่อวัน ปิดรอบ 11:00 //3 สมัครสมาชิก //4 คืนยอดเสีย //5 แนะนำเพื่อน //6 เครดิตฟรี
            $table->integer('type_promotion_invite')->default(0); //0 normal //1 ยอดฝากแรก // 2 เทิร์นโอเวอร์ //3 ยอดเสีย
            $table->integer('type_promotion_cost')->default(1); //1 ชิบเป็น // ชิบตาย (ดึงโบนัสคืน) //3 ให้โบนัสตอนทำเทิร์นครบ
            $table->integer('status')->default(0); //1 เปิด //0 ปิด
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
        Schema::dropIfExists('promotions');
    }
}
