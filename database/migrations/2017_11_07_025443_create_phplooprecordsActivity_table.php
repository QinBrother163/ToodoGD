<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhplooprecordsActivityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('phplooprecords_activity_201711', function (Blueprint $table) {
            $table->string('id')->comment('用户id')->primary();
            $table->dateTime('createDate')->comment('时间 ');
            $table->string('completionStatus')->comment('状态');
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
        Schema::dropIfExists('phplooprecords_activity_201711');
    }
}
