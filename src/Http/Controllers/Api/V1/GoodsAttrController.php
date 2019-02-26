<?php


namespace SmallRuralDog\Store\Http\Controllers\Api\V1;


use Illuminate\Http\Request;
use SmallRuralDog\Store\Http\Controllers\Api\ApiController;
use SmallRuralDog\Store\Http\Resources\GoodsAttrCollection;
use SmallRuralDog\Store\Http\Resources\GoodsAttrValue;
use SmallRuralDog\Store\Services\GoodsAttrService;

class GoodsAttrController extends ApiController
{

    protected $goodsAttrService;

    public function __construct(GoodsAttrService $goodsAttrService)
    {
        $this->goodsAttrService = $goodsAttrService;
    }

    public function getGoodsAttr()
    {
        $store = $this->store();

        $list = $this->goodsAttrService->getListByStore($store);
        $data = GoodsAttrCollection::make($list);
        return $this->success($data);
    }

    public function createGoodsAttr(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required',
        ], [
            'name.required' => '请输入名称',
        ]);
        if ($validator->fails()) {
            return $this->failed($validator->errors()->first());
        }
        $store = $this->store();
        list($attr, $list) = $this->goodsAttrService->createAttr($request->input('name'), $store);
        $data['attr_list'] = GoodsAttrCollection::make($list);
        $data['attr'] = \SmallRuralDog\Store\Http\Resources\GoodsAttr::make($attr);
        return $this->success($data);
    }


    public function createGoodsAttrValue(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'attr_id' => 'required',
            'name' => 'required',
        ], [
            'attr_id.required' => '请输入规格ID',
            'name.required' => '请输入名称',
        ]);
        if ($validator->fails()) {
            return $this->failed($validator->errors()->first());
        }
        $store = $this->store();
        $goods_attr = $this->goodsAttrService->getOneByStore($request->input('attr_id'), $store);
        list($attr_value, $list) = $this->goodsAttrService->createAttrValue($request->input('name'), $goods_attr, $store);
        $data['attr_list'] = GoodsAttrCollection::make($list);
        $data['attr_value'] = GoodsAttrValue::make($attr_value);
        return $this->success($data);
    }
}