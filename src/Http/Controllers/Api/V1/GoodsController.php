<?php

namespace SmallRuralDog\Store\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use SmallRuralDog\Store\Http\Controllers\Api\ApiController;
use SmallRuralDog\Store\Http\Resources\Goods;
use SmallRuralDog\Store\Http\Resources\GoodsCollection;
use SmallRuralDog\Store\Services\GoodsService;

class GoodsController extends ApiController
{
    public $goodsService;

    public function __construct(GoodsService $goodsService)
    {
        $this->goodsService = $goodsService;
    }

    public function goodsList(Request $request)
    {

        $sorter = $request->input('sorter');

        $store = $this->store();

        $orm = $this->goodsService->getGoodsListByStore($store);

        $orm->with(['medias']);

        if ($sorter) {
            $field = $sorter['field'];

            if (!in_array($field, ['price', 'created_time'])) {
                abort(400, '排序字段错误');
            }

            $field = $field == 'created_time' ? 'created_at' : $field;

            $order = $sorter['order'] == "descend" ? 'desc' : 'asc';

            $orm->orderBy($field, $order);
        } else {
            $orm->orderByDesc('id');
        }

        $data = GoodsCollection::make($orm->paginate());

        return $this->success($data);
    }

    public function editGoodsBase(Request $request)
    {
        $id = $this->getId(false);
        $user = $this->user();
        $store = $this->store();
        try {

            $goods = $this->goodsService->getGoods($id, ['medias', 'attrs','skus']);
            if (!$this->goodsService->checkGoodsByStore($goods, $store)) {
                abort(400, '产品不存在');
            }
            $data = Goods::make($goods);
            return $this->success($data);
        } catch (\Exception $exception) {
            return $this->failed($exception->getMessage());
        }
    }


    /**
     * 保存产品基本数据
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function saveGoods(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required|max:50',
            'description' => 'max:30',
            'medias' => ['required', 'array', 'max:10']
        ], [
            'attr_id.required' => '请输入规格ID',
            'name.required' => '请输入名称',
        ]);
        if ($validator->fails()) {
            return $this->failed($validator->errors()->first());
        }
        $data = $request->all();
        $user = $this->user();
        $store = $this->store();
        $id = $this->getId(false);
        $oldGoods = false;
        if ($id) {
            $oldGoods = $this->goodsService->getGoods($id);
            if (!$this->goodsService->checkGoodsByStore($oldGoods, $store)) {
                return $this->failed('产品无法修改');
            }
        }
        $goods = $this->goodsService->saveGoods($user, $store, $oldGoods, $data);
        return $this->success($goods);
    }

}