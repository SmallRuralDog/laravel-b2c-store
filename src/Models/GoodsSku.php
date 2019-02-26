<?php


namespace SmallRuralDog\Store\Models;


use Illuminate\Database\Eloquent\Model;

class GoodsSku extends Model
{
    protected $guarded = [];

    public function __construct(array $attributes = [])
    {
        $this->setTable(config('store.database.table_prefix') . 'goods_sku');
        parent::__construct($attributes);
    }


    /**
     * SKU 库存
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function stock()
    {
        return $this->hasOne(GoodsSkuStock::class, 'sku_id');
    }


    const status = [-1, 0, 1];
}