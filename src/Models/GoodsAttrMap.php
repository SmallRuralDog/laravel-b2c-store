<?php

namespace SmallRuralDog\Store\Models;

use Illuminate\Database\Eloquent\Model;

class GoodsAttrMap extends Model
{

    protected $guarded = [];
    public $timestamps = false;

    public function __construct(array $attributes = [])
    {

        $this->setTable(config('store.database.table_prefix') . 'goods_attr_map');

        parent::__construct($attributes);
    }

    /**
     * 产品销售属性关联本体
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function attr()
    {
        return $this->belongsTo(GoodsAttr::class, 'attr_id');
    }

    /**
     * 产品销售属性值列表
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function values()
    {
        return $this->hasMany(GoodsAttrValueMap::class, 'attr_map_id', 'id')
            ->orderBy('index');
    }


}
