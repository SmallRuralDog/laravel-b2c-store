<?php
namespace SmallRuralDog\Store\Http\Resources;




use Illuminate\Http\Resources\Json\Resource;
use SmallRuralDog\LaravelCustom\Resources\CustomResource;

class GoodsAttr extends Resource
{
    use CustomResource;

    public function toArray($request)
    {
        return $this->filterFields([
            'id' => $this->id,
            'name' => $this->name,
            'is_system'=> $this->store_id==0,
            'values' => $this->whenLoaded('values', function () {
                return GoodsAttrValueCollection::make($this->values);
            })
        ]);
    }

}