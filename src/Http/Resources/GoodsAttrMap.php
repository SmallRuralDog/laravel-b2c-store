<?php

namespace SmallRuralDog\Store\Http\Resources;


use Illuminate\Http\Resources\Json\Resource;
use SmallRuralDog\LaravelCustom\Resources\CustomResource;

class GoodsAttrMap extends Resource
{
    use CustomResource;

    public function toArray($request)
    {
        return $this->filterFields([
            'attr_id' => $this->attr_id,
            'alias' => (string)$this->alias,
            'is_image' => (boolean)$this->is_image,
            'name' => $this->whenLoaded('attr', function () {
                return $this->attr->name;
            }),
            'values' => $this->whenLoaded('values', function () {
                return GoodsAttrValueMapCollection::make($this->values);
            })
        ]);
    }

}