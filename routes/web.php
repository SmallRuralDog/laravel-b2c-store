<?php

use SmallRuralDog\Store\Http\Controllers\StoreController;

Route::group([
    'prefix' => config('store.prefix', 'store')
], function () {

    Route::resource('/', StoreController::class);

    Route::get('createdStore', StoreController::class . '@createdStore')->name('store-createdStore');
    Route::get('manage', StoreController::class . '@manage')->name('store-manage');

    Route::group([
        'prefix' => 'api'
    ], function () {
        Route::group([
            'prefix' => 'v1'
        ], function () {
            Route::get('/', StoreController::class . '@index')->name('store-api-v1');


            Route::post('currentUser', 'SmallRuralDog\Store\Http\Controllers\Api\V1\UserController@currentUser');

            //goods
            Route::post('goodsList', 'SmallRuralDog\Store\Http\Controllers\Api\V1\GoodsController@goodsList');

            Route::get("goodsAttr", "SmallRuralDog\Store\Http\Controllers\Api\V1\GoodsAttrController@getGoodsAttr");//获取规格列表
            Route::post("createGoodsAttr", "SmallRuralDog\Store\Http\Controllers\Api\V1\GoodsAttrController@createGoodsAttr");//添加规格
            Route::post("createGoodsAttrValue", "SmallRuralDog\Store\Http\Controllers\Api\V1\GoodsAttrController@createGoodsAttrValue");//添加规格属性值
            Route::post("editGoodsBase", "SmallRuralDog\Store\Http\Controllers\Api\V1\GoodsController@editGoodsBase");//编辑产品基本数据
            Route::post("saveGoods", "SmallRuralDog\Store\Http\Controllers\Api\V1\GoodsController@saveGoods");//产品数据保存


            //media
            Route::any('mediaList', 'SmallRuralDog\Store\Http\Controllers\Api\V1\MediaController@getMediaList');
            Route::post('delMediaCategory', 'SmallRuralDog\Store\Http\Controllers\Api\V1\MediaController@delMediaCategory');
            Route::post('delMedia', 'SmallRuralDog\Store\Http\Controllers\Api\V1\MediaController@delMedia');
            Route::post('changeMedia', 'SmallRuralDog\Store\Http\Controllers\Api\V1\MediaController@changeMedia');
            Route::post('getMediaCategoryList', 'SmallRuralDog\Store\Http\Controllers\Api\V1\MediaController@getMediaCategoryList');
            Route::post('createMediaCategoryList', 'SmallRuralDog\Store\Http\Controllers\Api\V1\MediaController@createMediaCategoryList');
            Route::post('mediaUpload', "SmallRuralDog\Store\Http\Controllers\Api\V1\MediaController@upload")->name("store-media-upload");//上传

        });

    });
});