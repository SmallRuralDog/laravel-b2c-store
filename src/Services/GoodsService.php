<?php


namespace SmallRuralDog\Store\Services;


use SmallRuralDog\Store\Models\Goods;
use SmallRuralDog\Store\Models\GoodsAttrMap;
use SmallRuralDog\Store\Models\GoodsAttrValueMap;
use SmallRuralDog\Store\Models\GoodsMedia;
use SmallRuralDog\Store\Models\GoodsSku;
use SmallRuralDog\Store\Models\GoodsSkuStock;

class GoodsService
{


    public function getGoodsList()
    {
        return Goods::query();
    }

    /**
     *
     * 获取店铺商品列表
     * @param $store
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getGoodsListByStore($store)
    {
        return $this->getGoodsList()->where('store_id', $store->id);
    }

    /**
     * 根据ID获取产品
     * @param $id
     * @param array $with
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    public function getGoods($id, $with = [])
    {
        try {
            return Goods::query()->with($with)->findOrFail($id);
        } catch (\Exception $exception) {
            abort(400, '产品不存在');
        }

    }

    /**
     * @param $user
     * @param $store
     * @param Goods $goods
     * @param $data
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     * @throws \Exception
     */
    public function saveGoods($user, $store, $goods, $data)
    {
        $baseData = [
            'user_id' => $user->id,
            'store_id' => $store->id,
            'name' => $data['name'],
            'description' => isset($data['description']) ? $data['description'] : '',
            'price' => isset($data['price']) ? $data['price'] : 0
        ];

        \DB::beginTransaction();
        try {
            if ($goods) {
                $goods->update($baseData);
            } else {
                $goods = Goods::query()->create($baseData);
            }
            isset($data['medias']) && $this->saveGoodsMedia($goods, $data['medias']);
            isset($data['goods_sku_group']) && $this->saveGoodsAttr($goods, $data['goods_sku_group']);
            isset($data['goods_skus']) && $this->saveGoodsSku($goods, $data['goods_skus']);
            \DB::commit();
            return $goods;
        } catch (\Exception $exception) {
            \DB::rollBack();
            abort(400, $exception->getMessage());
        }
    }


    /**
     * 保存产品媒体数据
     * @param $goods
     * @param $medias
     */
    private function saveGoodsMedia($goods, $medias)
    {
        try {
            GoodsMedia::query()->where('goods_id', $goods->id)->delete();
            $items = collect($medias)->map(function ($media, $index) use ($goods) {
                return [
                    'goods_id' => $goods->id,
                    'media_id' => $media['id'],
                    'index' => $index
                ];
            })->all();
            GoodsMedia::query()->insert($items);
        } catch (\Exception $exception) {
            abort(400, '媒体保存失败' . config('app.debug') ? $exception->getMessage() : '');
        }

    }

    /**
     * 保存产品销售属性
     * @param $goods
     * @param $attrs
     */
    private function saveGoodsAttr($goods, $attrs)
    {
        try {
            GoodsAttrMap::query()->where('goods_id', $goods->id)->delete();
            GoodsAttrValueMap::query()->where('goods_id', $goods->id)->delete();

            collect($attrs)->map(function ($attr, $index) use ($goods) {
                $attr_map = GoodsAttrMap::query()->create([
                    'goods_id' => $goods->id,
                    'attr_id' => $attr['id'],
                    'alias' => $attr['alias'],
                    'is_image' => (boolean)$attr['is_image'],
                    'index' => $index
                ]);

                $values = collect($attr['sku_list'])->map(function ($value, $index) use ($attr_map) {
                    return [
                        'attr_map_id' => $attr_map->id,
                        'goods_id' => $attr_map->goods_id,
                        'attr_id' => $attr_map->attr_id,
                        'attr_value_id' => $value['id'],
                        'alias' => $value['alias'],
                        'media_id' => isset($value['image']) && is_array($value['image']) ? $value['image']['id'] : 0,
                        'index' => $index
                    ];
                })->all();
                GoodsAttrValueMap::query()->insert($values);
            });


        } catch (\Exception $exception) {
            abort(400, '销售属性保存失败' . config('app.debug') ? $exception->getMessage() : '');
        }
    }

    /**
     * 保存产品SKU
     * @param $goods
     * @param $skus
     */
    private function saveGoodsSku($goods, $skus)
    {
        try {
            //首先将原有的删除
            $this->setSkuStatus($goods, -1);

            collect($skus)->filter(function ($item){
                return is_array($item);
            })->map(function ($sku) use ($goods) {
                //更新或创建
                $goods_sku = GoodsSku::query()
                    ->where('goods_id', $goods->id)
                    ->where('attr_key', $sku['attr_key'])
                    ->updateOrCreate([], [
                        'goods_id' => $goods->id,
                        'attr_key' => $sku['attr_key'],
                        'is_image' => $sku['is_image'],
                        'media_id' => $sku['media_id'],
                        'price' => $sku['price'],
                        'cost_price' => $sku['cost_price'],
                        'code' => $sku['code'],
                        'status' => 1
                    ]);
                //更新库存
                $this->setSkuStock($goods, $goods_sku, $sku['stock_num']);

                //TODO 根据订单关联，更新销量
            });

        } catch (\Exception $exception) {
            abort(400, 'SKU保存失败' . config('app.debug') ? $exception->getMessage() : '');
        }
    }


    /**
     * 设置SKU状态
     * @param $goods
     * @param $status
     */
    public function setSkuStatus($goods, $status)
    {
        if (in_array($status, GoodsSku::status)) {
            //修改sku表
            GoodsSku::query()->where('goods_id', $goods->id)->update([
                'status' => $status
            ]);
            //修改库存表
            GoodsSkuStock::query()->where('goods_id', $goods->id)->update([
                'status' => $status
            ]);
        } else {
            abort(400, 'SKU状态错误');
        }
    }


    /**
     * 设置SKU库存
     * @param $goods
     * @param $goods_sku
     * @param $quantity
     */
    public function setSkuStock($goods, $goods_sku, $quantity)
    {

        GoodsSkuStock::query()
            ->where('goods_id', $goods->id)
            ->where('sku_id', $goods_sku->id)
            ->updateOrCreate([], [
                'sku_id' => $goods_sku->id,
                'goods_id' => $goods->id,
                'quantity' => $quantity,
                'status' => 1
            ]);
        //TODO 记录库存变更操作日志

    }


    /**
     * 检查店铺产品
     * @param $goods
     * @param $store
     * @return bool
     */
    public function checkGoodsByStore($goods, $store)
    {
        return $goods->store_id == $store->id;
    }

}