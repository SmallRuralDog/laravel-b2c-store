<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('store.database.table_prefix') . 'media', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('mc_id')->default(0)->comment('分组ID');
            $table->integer('store_id')->comment("店铺ID");
            $table->enum('type', ['image', 'video', 'audio'])->comment('类型');
            $table->string('bucket')->nullable()->comment("bucket");
            $table->string('disk')->comment("磁盘");
            $table->string('path')->comment('文件路径');
            $table->Integer('size')->comment('文件大小');
            $table->string('file_ext')->comment("文件后缀");
            $table->string('file_name')->comment("文件名称");
            $table->json('meta')->comment("属性");
            $table->boolean('is_delete')->default(false)->comment('是否删除');
            $table->timestamps();
            $table->softDeletes();
            $table->index(['mc_id', 'store_id', 'type']);
            $table->index(['file_name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(config('store.database.table_prefix') . 'media');
    }
}
