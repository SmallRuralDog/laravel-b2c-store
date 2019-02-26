<?php

namespace SmallRuralDog\Store\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use SmallRuralDog\LaravelCustom\Resources\CustomResource;

class Goods extends JsonResource
{
    use CustomResource;

    public function toArray($request)
    {
        $data = parent::toArray($request);

        $data = [
            'id' => $data['id'],
            'name' => $data['name'],
            'description' => $data['description'],
            'price' => (float)$data['price'],
            'line_price' => (float)$data['price'],
            'stock_num' => (integer)$data['stock_num'],
            'created_time' => $data['created_at'],
            'sold_num' => 0,
            'is_used' => 0,
            $this->mergeWhen($this->whenLoaded('medias'), function () {
                $data['medias'] = MediaCollection::make($this->medias);
                $data['cover'] = Media::make(collect($this->medias)->filter(function ($media) {
                    return $media->type == \SmallRuralDog\Store\Models\Media::IMAGE;
                })->first());
                return $data;
            }),
            'attrs' => $this->whenLoaded('attrs', function () {
                return GoodsAttrMapCollection::make($this->attrs);
            }),
            'skus' => $this->whenLoaded('skus', function () {
                return GoodsSkuCollection::make($this->skus);
            })
        ];

        return $this->filterFields($data);
    }
}
