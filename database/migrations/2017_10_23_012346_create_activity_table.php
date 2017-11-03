<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fsdp_activity_list_201710', function (Blueprint $table) {
            $table->string('id')->comment('订单编号')->primary();
            $table->dateTime('createDate')->comment('创建日期');
            $table->unsignedInteger('Flags')->comment('标记');
            $table->string('resultCode')->comment('结果码');
            $table->string('orderID')->comment('输入订单号');
            $table->unsignedInteger('needCnfm')->comment('需要Cnfm');
            $table->string('custid')->comment('custid');
            $table->string('spID')->comment('标识符');
            $table->unsignedInteger('devType')->comment('开发类型');
            $table->string('devNO')->comment('dev没有');
            $table->string('CARegionCode')->comment('CARegion代码');
            $table->string('serviceid')->comment('服务标识');
            $table->string('streamingNO')->comment('streaming NO');
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
        Schema::dropIfExists('fsdp_activity_list_201710');
    }
}
