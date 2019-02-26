<?php

namespace SmallRuralDog\Store\Models;

use Illuminate\Database\Eloquent\Model;

class GoodsSkuStock extends Model
{
    protected $guarded = [];

    public function __construct(array $attributes = [])
    {
        $this->setTable(config('store.database.table_prefix') . 'goods_sku_stock');
        parent::__construct($attributes);
    }

}