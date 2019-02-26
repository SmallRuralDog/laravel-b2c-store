<?php

namespace SmallRuralDog\Store\Models;

use Illuminate\Database\Eloquent\Model;

class GoodsAttrValueMap extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    public function __construct(array $attributes = [])
    {

        $this->setTable(config('store.database.table_prefix') . 'goods_attr_value_map');

        parent::__construct($attributes);
    }

    /**
     * 产品销售属性值关联的媒体
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function media(){
        return $this->belongsTo(Media::class);
    }

    /**
     * 产品销售属性值关联的本体（详细信息）
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function value(){
        return $this->belongsTo(GoodsAttrValue::class,'attr_value_id');
    }
}
