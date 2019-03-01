<?php

namespace SmallRuralDog\Store\Services;

use Illuminate\Http\UploadedFile;
use Intervention\Image\ImageManager;
use SmallRuralDog\Store\Models\Media;
use SmallRuralDog\Store\Models\MediaCategory;


class MediaService
{
    public function getMediaCategoryListByStore($store, $type)
    {
        $list = MediaCategory::query()->with([
            'medias'
        ])->where('type', $type)->whereIn('store_id', [0, $store->id])->get();
        return $list;
    }

    public function getOneByStore($id, $store)
    {
        $obj = MediaCategory::query()->where('store_id', $store->id)->findOrFail($id);
        return $obj;
    }

    public function getOneByDefault($type)
    {
        $obj = MediaCategory::query()->where('type', $type)->where('store_id', 0)->firstOrCreate([
            'name' => '未分组',
            'type' => $type,
            'store_id' => 0,
            'parent_id' => 0
        ]);
        return $obj;
    }


    /**
     * @param $name
     * @param $store
     * @param $type
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     * @throws \Exception
     */
    public function createListByStore($name, $store, $type)
    {
        if ($this->checkName($name, $store, $type)) {
            abort(400, '名称已存在');
        }

        $obj = MediaCategory::query()->create([
            'store_id' => $store->id,
            'parent_id' => 0,
            'name' => $name,
            'type' => $type
        ]);
        return $obj;
    }


    /**
     * @param $id
     * @param $store
     * @throws \Throwable
     */
    public function delMediaCategoryByStore($id, $store)
    {
        $mc = $this->getOneByStore($id, $store);
        $default = $this->getOneByDefault($mc->type);
        \DB::transaction(function () use ($default, $mc) {
            $mc->medias()->update(['mc_id' => $default->id]);
            $mc->delete();
        });
    }

    public function checkName($name, $store, $type)
    {
        $item = MediaCategory::query()->where('name', $name)->where('type', $type)->whereIn('store_id', [0, $store->id])->first();

        return $item ? true : false;
    }

    /**
     * @param $store
     * @param $type
     * @param bool|int $mc_id
     * @param array|bool $ids
     * @return \Illuminate\Database\Eloquent\Builder
     */

    public function getListByStore($store, $type, $mc_id = false, $ids = false)
    {
        $mc = Media::query()->orderByDesc('updated_at')->where('is_delete', 0)->where('type', $type)->whereIn('store_id', [0, $store->id]);
        if ($mc_id) {
            $mc->where('mc_id', $mc_id);
        }
        if ($ids) {
            $mc->whereIn('id', $ids);
        }
        return $mc;

    }

    public function getMediaByStore($id, $store)
    {
        return Media::query()->where('store_id', $store->id)->findOrFail($id);
    }


    public function delMediaByStore($ids, $store, $type)
    {
        return $this->getListByStore($store, $type, false, $ids)->update([
            'is_delete' => 1
        ]);
    }

    public function changeMediaByStore($ids, $type, $store, $key, $value)
    {
        return $this->getListByStore($store, $type, false, $ids)->update([
            $key => $value
        ]);
    }

    /**
     * 上传
     * @param UploadedFile $file
     * @param $mc_id
     * @param $store
     * @return \Illuminate\Database\Eloquent\Builder|Model
     */
    public function upload(UploadedFile $file, $mc_id, $store)
    {

        $mime_type = $file->getMimeType();
        $type_info = $this->_getTypeInfoByMimeType($mime_type);

        //配置上传信息
        config([
            'filesystems.default' => config('union.disk', 'public')
        ]);

        $disk = config('filesystems.default');

        $bucket = $disk == 'qiniu' ? config('filesystems.disks.qiniu.bucket') : null;
        $folder = 'upload_files/' . date("Y/m/d");//保存文件夹
        $path = $file->store($folder);

        $meta = $this->_getMeta($disk, $file, $path, $type_info);


        $data = [
            'mc_id' => $mc_id,
            'store_id' => $store->id,
            'path' => $path,
            'file_name' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
            'type' => $type_info['type'],
            'file_ext' => $type_info['suffix'],
            'disk' => $disk,
            'bucket' => $bucket,
            'meta' => $meta
        ];
        return Media::query()->create($data);
    }

    private function _getMeta($disk, $file, $path, $type_info)
    {
        switch ($type_info['type']) {
            case 'image':
                $manager = new ImageManager();
                $image = $manager->make($file);
                $meta = [
                    'format' => $type_info['suffix'],
                    'suffix' => $file->getClientOriginalExtension(),
                    'size' => $file->getSize(),
                    'width' => $image->getWidth(),
                    'height' => $image->getHeight()
                ];
                break;
            default :
                $meta = [];
        }
        return $meta;
    }

    private function _getTypeInfoByMimeType($mt)
    {
        $arr = explode('/', $mt);
        return [
            'type' => $arr[0],
            'suffix' => $arr[1]
        ];
    }
}