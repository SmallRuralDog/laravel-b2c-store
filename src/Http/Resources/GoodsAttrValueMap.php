<?php

namespace SmallRuralDog\Store\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use SmallRuralDog\LaravelCustom\Resources\CustomResource;

class GoodsAttrValueMap extends Resource
{
    use CustomResource;

    public function toArray($request)
    {
        return $this->filterFields([
            'attr_value_id' => $this->attr_value_id,
            'goods_id' => $this->goods_id,
            'alias' => (string)$this->alias,
            'media' => $this->whenLoaded('media', function () {
                return Media::make($this->media);
            }),
            'name' => $this->whenLoaded('value', function () {
                return $this->value->name;
            }),
        ]);
    }

}