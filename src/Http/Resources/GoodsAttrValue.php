<?php

namespace SmallRuralDog\Store\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use SmallRuralDog\LaravelCustom\Resources\CustomResource;

class GoodsAttrValue extends Resource
{
    use CustomResource;

    public function toArray($request)
    {
        return $this->filterFields([
            'id' => $this->id,
            'name' => $this->name
        ]);
    }

}