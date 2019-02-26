<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('store.database.table_prefix') . 'stores', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unique()->comment('用户ID');
            $table->integer('class_id')->comment('店铺经营类型ID');
            $table->string('store_name')->comment('店铺名称');
            $table->string('logo')->nullable()->comment('店铺logo');
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
        Schema::dropIfExists(config('store.database.table_prefix') . 'stores');
    }
}
