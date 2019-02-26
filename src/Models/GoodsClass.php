<?php

namespace SmallRuralDog\Store\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

class GoodsClass extends Model
{
    protected $table = "goods_classes";

    /**
     * @param Builder $query
     * @return mixed
     */
    public function scopeProvince($query)
    {
        return $query->where('parent_id', 0);
    }
}
