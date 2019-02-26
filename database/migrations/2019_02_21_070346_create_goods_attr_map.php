<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodsAttrMap extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('store.database.table_prefix') .'goods_attr_map', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('goods_id')->index()->comment('产品ID');
            $table->integer('attr_id')->comment('属性ID');
            $table->string('alias')->nullable()->comment('别名');
            $table->boolean('is_image')->default(false)->comment('可选择图片');
            $table->integer('index')->default(0)->comment('排序 asc');
        });

        Schema::create(config('store.database.table_prefix') .'goods_attr_value_map', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('attr_map_id')->index()->comment('产品属性关系ID');
            $table->integer('goods_id')->index()->comment('产品ID');
            $table->integer('attr_id')->comment('属性ID');
            $table->integer('attr_value_id')->comment('属性值ID');
            $table->string('alias')->nullable()->comment('别名');
            $table->integer('media_id')->default(0)->comment('媒体ID');
            $table->integer('index')->default(0)->comment('排序 asc');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(config('store.database.table_prefix') .'goods_attr_map');
        Schema::dropIfExists(config('store.database.table_prefix') .'goods_attr_value_map');
    }
}
