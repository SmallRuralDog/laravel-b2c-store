<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoodsSkuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('store.database.table_prefix') . 'goods_sku', function (Blueprint $table) {
            $table->increments('id')->comment('自增 sku_id');
            $table->integer('goods_id')->index()->comment('产品ID');
            $table->string('attr_key')->index()->comment('销售属性标识 - 链接，按小到大排序');
            $table->boolean('is_image')->comment('是否有图片');
            $table->integer('media_id')->comment('媒体ID');
            $table->decimal('price')->comment('价格');
            $table->decimal('cost_price')->nullable()->comment('成本价');
            $table->string('code')->nullable()->comment('编码');
            $table->integer('sold_num')->default(0)->comment('销量');
            $table->tinyInteger('status')->default(1)->comment('状态 1:enable, 0:disable, -1:deleted');
            $table->timestamps();

            $table->index(['goods_id', 'attr_key']);
        });
        Schema::create(config('store.database.table_prefix') . 'goods_sku_stock', function (Blueprint $table) {
            $table->increments('id')->comment('自增ID');
            $table->integer('sku_id')->comment('SKU ID');
            $table->integer('goods_id')->comment('产品 ID');
            $table->integer('quantity')->comment('库存');
            $table->tinyInteger('status')->default(1)->comment('状态 1:enable, 0:disable, -1:deleted');
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
        Schema::dropIfExists(config('store.database.table_prefix') . 'goods_sku');
        Schema::dropIfExists(config('store.database.table_prefix') . 'goods_sku_stock');
    }
}
