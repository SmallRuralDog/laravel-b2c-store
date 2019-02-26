<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoodsAttrValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('store.database.table_prefix') . 'goods_attr_values', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('goods_attr_id')->comment('规格ID');
            $table->integer('store_id')->comment('店铺ID');
            $table->string('name')->comment('属性名称');
            $table->smallInteger('sort')->default(0)->comment('排序');
            $table->timestamps();

            $table->index(['goods_attr_id', 'store_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(config('store.database.table_prefix') . 'goods_attr_values');
    }
}
