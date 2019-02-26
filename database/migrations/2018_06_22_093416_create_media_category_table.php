<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMediaCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('store.database.table_prefix') . 'media_category', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('store_id')->comment('店铺ID');
            $table->integer('parent_id')->default(0)->comment('上级ID');
            $table->string('name')->comment('名称');
            $table->enum('type', ['image', 'video', 'audio'])->comment('类型');
            $table->timestamps();
            $table->index(['store_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(config('store.database.table_prefix') . 'media_category');
    }
}
