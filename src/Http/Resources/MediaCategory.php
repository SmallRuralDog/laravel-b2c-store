<?php
namespace SmallRuralDog\Store\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use SmallRuralDog\LaravelCustom\Resources\CustomResource;

class MediaCategory extends Resource
{
    use CustomResource;

    public function toArray($request)
    {
        return $this->filterFields([
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'is_system' => $this->store_id == 0,
            'count' => $this->whenLoaded('medias', function () {
                return collect($this->medias)->count();
            })
        ]);
    }
}
