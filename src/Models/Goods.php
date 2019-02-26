<?php

namespace SmallRuralDog\Store\Models;

use Illuminate\Database\Eloquent\Model;

class Goods extends Model
{

    protected $guarded = [];

    public function __construct(array $attributes = [])
    {

        $this->setTable(config('store.database.table_prefix') . 'goods');

        parent::__construct($attributes);
    }


    /**
     * 产品媒体列表
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function medias()
    {
        return $this->hasManyThrough(Media::class, GoodsMedia::class, 'goods_id', 'id', 'id', 'media_id')->orderBy('index');
    }

    /**
     * 产品销售属性列表
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attrs()
    {
        return $this->hasMany(GoodsAttrMap::class, 'goods_id')
            ->with([
                'values',
                'values.media',
                'values.value',
                'attr'
            ]);
    }

    /**
     * 产品 SKU
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function skus()
    {
        return $this->hasMany(GoodsSku::class, 'goods_id')->with(['stock']);
    }

    /**
     * 产品 库存列表
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function stock()
    {
        return $this->hasMany(GoodsSkuStock::class, 'goods_id');
    }
}
