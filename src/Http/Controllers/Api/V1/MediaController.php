<?php


namespace SmallRuralDog\Store\Http\Controllers\Api\V1;


use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use SmallRuralDog\Store\Http\Controllers\Api\ApiController;
use SmallRuralDog\Store\Http\Resources\MediaCategoryCollection;
use SmallRuralDog\Store\Http\Resources\MediaCollection;
use SmallRuralDog\Store\Models\Media;
use SmallRuralDog\Store\Services\MediaService;


class MediaController extends ApiController
{

    protected $mediaService;

    public function __construct(MediaService $mediaService)
    {
        $this->mediaService = $mediaService;
    }


    public function getMediaList(Request $request)
    {
        $mc_id = $request->input("query.mc_id", 0);
        $name= $request->input("query.name");
        $pre = $request->input("pre", 18);
        $store = $this->store();
        $orm = $this->mediaService->getListByStore($store, Media::IMAGE, $mc_id);
        if ($name) {
            $orm->where("file_name", "like", "%{$name}%");
        }
        $page = $orm->paginate($pre);
        $data = MediaCollection::make($page);
        return $this->success($data);
    }

    public function getMediaCategoryList(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'type' => ['required', Rule::in([Media::IMAGE, Media::VIDEO, Media::AUDIO])]
        ], [
            'type.required' => '请输入类型',
        ]);
        if ($validator->fails()) {
            return $this->failed($validator->errors()->first());
        }
        $type = $request->input('type', Media::IMAGE);
        $store = $this->store();

        $list = $this->mediaService->getMediaCategoryListByStore($store, $type);
        $data = MediaCategoryCollection::make($list);
        return $this->success($data);
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function createMediaCategoryList(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name' => ['required','max:4'],
            'type' => ['required', Rule::in([Media::IMAGE, Media::VIDEO, Media::AUDIO])]
        ], [
            'name.required' => '请输入名称',
            'type.in' => '类型错误'
        ]);
        if ($validator->fails()) {
            return $this->failed($validator->errors()->first());
        }
        $store = $this->store();
        $name = $request->input('name');
        $type = $request->input('type');

        $obj = $this->mediaService->createListByStore($name, $store, $type);

        $data['item'] = \SmallRuralDog\Store\Http\Resources\MediaCategory::make($obj);
        return $this->success($data);
    }

    /**
     * @return mixed
     * @throws \Throwable
     */
    public function delMediaCategory()
    {
        $id = $this->getId();
        $store = $this->store();
        $this->mediaService->delMediaCategoryByStore($id, $store);

        return $this->message('删除成功');
    }

    /**
     * 删除媒体
     * @param Request $request
     * @return mixed
     */
    public function delMedia(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'ids' => ['required', 'array'],
            'type' => ['required', Rule::in([Media::IMAGE, Media::VIDEO, Media::AUDIO])]
        ]);
        if ($validator->fails()) {
            return $this->failed($validator->errors()->first());
        }
        $ids = $request->input('ids');
        $type = $request->input('type');
        $store = $this->store();
        $this->mediaService->delMediaByStore($ids, $store, $type);
        return $this->message('删除成功');
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function changeMedia(Request $request)
    {

        $store = $this->store();
        $validator = \Validator::make($request->all(), [
            'ids' => ['required', 'array'],
            'type' => ['required', Rule::in([Media::IMAGE, Media::VIDEO, Media::AUDIO])],
            'key' => ['required', Rule::in(['file_name', 'mc_id'])],
            'value' => ['required'],
        ]);
        if ($validator->fails()) {
            return $this->failed($validator->errors()->first());
        }
        $key = $request->input("key");
        $value = $request->input("value");
        $ids = $request->input("ids");
        $type = $request->input("type");
        $this->mediaService->changeMediaByStore($ids, $type, $store, $key, $value);
        return $this->message('修改成功');
    }

    /**
     * 上传文件
     * @param Request $request
     * @return mixed
     */
    public function upload(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'mc_id' => 'required',
            'type' => 'required',
            'file' => 'mimetypes:video/mp4,image/jpeg,image/png,image/gif,audio/silk'
        ], [
            'required' => '参数有误',
            'mimetypes' => '文件类型不符',
        ]);
        if ($validator->fails()) {
            return $this->failed($validator->errors()->first());
        }

        $file = $request->file('file');

        $mc_id = $request->input('mc_id');
        $store = $this->store();
        $media_obj = $this->mediaService->upload($file, $mc_id, $store);
        $data = \SmallRuralDog\Store\Http\Resources\Media::make($media_obj);
        return $this->success($data);

    }

}