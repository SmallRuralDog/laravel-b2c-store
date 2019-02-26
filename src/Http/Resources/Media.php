<?php


namespace SmallRuralDog\Store\Http\Resources;


use Illuminate\Http\Resources\Json\Resource;
use SmallRuralDog\LaravelCustom\Resources\CustomResource;
use SmallRuralDog\Store\Helpers\MediaTool;

class Media extends Resource
{
    use CustomResource;

    public function toArray($request)
    {
        switch ($this->type) {
            case 'image':
                return $this->_image();
                break;
            default:
                return [];
        }

    }


    private function _image()
    {
        return $this->filterFields([
            'id' => $this->id,
            //'mc_id' => $this->mc_id,
            'media_type' => $this->type,
            'name' => $this->file_name,
            'size' => MediaTool::setSize($this->size),
            //'file_ext' => $this->file_ext,
            //'path' => $this->path,
            'url' => MediaTool::storeMedia($this),
            'image_url' => MediaTool::storeMedia($this),
            'meta' => $this->meta,
            //'disk' => $this->disk,
            'width' => $this->meta['width'],
            'height' => $this->meta['height'],
        ]);
    }
}