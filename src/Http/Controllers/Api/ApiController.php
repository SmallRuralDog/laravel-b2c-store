<?php

namespace SmallRuralDog\Store\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use SmallRuralDog\LaravelCustom\Api\ApiResponse;
use SmallRuralDog\Store\Models\Store;
use SmallRuralDog\Store\Models\StoreUser;

class ApiController extends Controller
{
    use ApiResponse;
    public $userInfo;


    public function user()
    {
        $this->userInfo = StoreUser::query()->with(['store'])->find(\Admin::user()->id);
        return $this->userInfo;
    }

    protected function store()
    {
        return Store::query()->first();
    }

    /**
     * 获取ID参数值
     * @param string $key
     * @param bool $throw
     * @return array|\Illuminate\Http\Request|string
     */
    public function getId($throw = true, $key = 'id')
    {
        $id = request($key, 0);
        if ($id == 0 && $throw) {
            abort(400, '参数错误');
        }
        return $id;
    }

}