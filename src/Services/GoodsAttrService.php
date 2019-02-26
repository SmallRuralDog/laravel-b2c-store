<?php


namespace SmallRuralDog\Store\Services;


use SmallRuralDog\Store\Models\GoodsAttr;
use SmallRuralDog\Store\Models\GoodsAttrValue;

class GoodsAttrService
{
    public function createAttr($name, $store)
    {
        $attr = GoodsAttr::query()->create([
            'store_id' => $store->id,
            'name' => $name,
            'sort' => 1
        ]);

        return [$attr, $this->getListByStore($store)];
    }

    public function createAttrValue($name, $goods_attr, $store)
    {
        $attr_value = GoodsAttrValue::query()->create([
            'goods_attr_id' => $goods_attr->id,
            'store_id' => $store->id,
            'name' => $name,
        ]);

        return [$attr_value, $this->getListByStore($store)];
    }

    public function getOneByStore($id, $store)
    {
        $item = GoodsAttr::query()->with([
            'values' => function ($query) use ($store) {
                $query->whereIn('store_id', [0, $store->id]);
            }
        ])->whereIn('store_id', [0, $store->id])->orderByDesc('sort')->findOrFail($id);
        return $item;
    }

    public function getListByStore($store)
    {
        $list = GoodsAttr::query()->with([
            'values' => function ($query) use ($store) {
                $query->whereIn('store_id', [0, $store->id]);
            }
        ])->whereIn('store_id', [0, $store->id])->orderByDesc('sort')->get();
        return $list;
    }

}