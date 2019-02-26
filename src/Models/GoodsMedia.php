<?php


namespace SmallRuralDog\Store\Models;

use Illuminate\Database\Eloquent\Model;

class GoodsMedia extends Model
{
    protected $guarded = [];

    public function __construct(array $attributes = [])
    {

        $this->setTable(config('store.database.table_prefix').'goods_medias');

        parent::__construct($attributes);
    }
}
