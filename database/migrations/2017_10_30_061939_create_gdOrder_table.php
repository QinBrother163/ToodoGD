<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGdOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_201710', function (Blueprint $table) {
            $table->string('order_id')->comment('订单编号')->primary();
            $table->string('user_id')->comment('用户id');
            $table->unsignedInteger('app_type')->comment('应用类型');
            $table->unsignedInteger('app_id')->comment('请求的应用id');
            $table->unsignedInteger('currency_type')->comment('货币类型');
            $table->string('fee')->comment('金额');
            $table->dateTime('date')->comment('订单生成时间');
            $table->unsignedInteger('state')->comment('订单情况,');
            $table->unsignedInteger('event')->comment('订单处理的事件');
            $table->string('title')->comment('订单标题');
            $table->string('real_name')->comment('收货人姓名');
            $table->string('phone')->comment('收货人联系电话');
            $table->string('address')->comment('收货地址 ');
            $table->dateTime('update_time')->comment('最后更新时间 ');
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
        Schema::dropIfExists('order_201710');
    }
}
