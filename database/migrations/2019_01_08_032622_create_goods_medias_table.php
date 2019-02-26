<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodsMediasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('store.database.table_prefix') . 'goods_medias', function (Blueprint $table) {
            $table->integer('goods_id')->index()->comment('产品ID');
            $table->integer('media_id')->comment('媒体ID');
            $table->integer('index')->default(0);

            $table->unique(['goods_id', 'media_id', 'index']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(config('store.database.table_prefix') . 'goods_medias');
    }
}
