<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMgpayrecordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fsdp_mgpayrecord_list_201710', function (Blueprint $table) {
            $table->string('id')->comment('订单编号')->primary();
            $table->dateTime('createDate')->comment('创建日期');
            $table->string('devNO')->comment('设备编号');
            $table->string('CARegionCode')->comment('地区编码');
            $table->string('needCnfm')->comment('输入订单号');
            $table->unsignedInteger('needCnfm')->comment('需要童锁');
            $table->string('price')->comment('价格');
            $table->string('CPID')->comment('经营商');
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
        Schema::dropIfExists('fsdp_mgpayrecord_list_201710');
    }
}
