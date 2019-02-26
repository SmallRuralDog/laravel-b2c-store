<?php
/**
 * Created by PhpStorm.
 * User: zw
 * Date: 2019/2/23
 * Time: 9:31
 */

namespace SmallRuralDog\Store\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;
use SmallRuralDog\LaravelCustom\Resources\CustomResource;

class GoodsSku extends JsonResource
{
    use CustomResource;

    public function toArray($request)
    {

        return $this->filterFields([
            'id' => $this->id,
            'attr_key' => $this->attr_key,
            'price' => (float)$this->price,
            'sold_num' => (int)$this->sold_num,
            'code' => $this->code,
            'cost_price' => (float)$this->cost_price,
            'stock_num' => (int)$this->whenLoaded('stock', function () {
                return $this->stock->quantity;
            })
        ]);
    }
}
