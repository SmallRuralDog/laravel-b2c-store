<?php

namespace SmallRuralDog\Store\Models;

use Illuminate\Database\Eloquent\Model;

class GoodsAttr extends Model
{

    protected $guarded = [];

    public function __construct(array $attributes = [])
    {

        $this->setTable(config('store.database.table_prefix').'goods_attr');

        parent::__construct($attributes);
    }

    /**
     * 销售属性值列表
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function values()
    {
        return $this->hasMany(GoodsAttrValue::class, 'goods_attr_id');
    }


}
