<?php


namespace SmallRuralDog\Store\Models;


use Illuminate\Database\Seeder;

class StoreTablesSeeder extends Seeder
{

    /**
     * 运行数据库填充。
     *
     * @return void
     */
    public function run()
    {
        //$this->_initGoodsClass();
        $this->_initStore();
        $this->_initGoodsAttr();
        //$this->_initMedia();
        //$this->_initArea();
    }

    private function _initStore()
    {
        if (Store::query()->count() <= 0) {
            Store::query()->create([
                'user_id' => 0,
                'class_id' => 0,
                'store_name' => '默认店铺'
            ]);
        }
    }

    private function _initMedia()
    {
        if (MediaCategory::query()->where('store_id', 0)->count() <= 0) {
            MediaCategory::query()->create([
                'store_id' => 0,
                'parent_id' => 0,
                'name' => '未分组',
                'type' => 'image'
            ]);
            MediaCategory::query()->create([
                'store_id' => 0,
                'parent_id' => 0,
                'name' => '未分组',
                'type' => 'video'
            ]);
            MediaCategory::query()->create([
                'store_id' => 0,
                'parent_id' => 0,
                'name' => '未分组',
                'type' => 'audio'
            ]);
        }
    }

    private function _initGoodsAttr()
    {
        if (GoodsAttr::query()->where('store_id', 0)->count() <= 0) {
            GoodsAttr::query()->insert([
                [
                    'store_id' => 0,
                    'name' => '颜色',
                    'sort' => 1
                ],
                [
                    'store_id' => 0,
                    'name' => '尺寸',
                    'sort' => 1
                ],
                [
                    'store_id' => 0,
                    'name' => '尺码',
                    'sort' => 1
                ],
                [
                    'store_id' => 0,
                    'name' => '款式',
                    'sort' => 1
                ],
                [
                    'store_id' => 0,
                    'name' => '种类',
                    'sort' => 1
                ],
                [
                    'store_id' => 0,
                    'name' => '版本',
                    'sort' => 1
                ],
                [
                    'store_id' => 0,
                    'name' => '容量',
                    'sort' => 1
                ]
            ]);
        }
    }

    private function _initGoodsClass()
    {
        //产品分类数据初始化
        if (GoodsClass::query()->count() <= 0) {
            GoodsClass::query()->insert([
                [
                    'name' => '数码办公',
                    'sort' => 1
                ],
                [
                    'name' => '礼品箱包',
                    'sort' => 2
                ],
                [
                    'name' => '家居家装',
                    'sort' => 3
                ],
                [
                    'name' => '服饰鞋帽',
                    'sort' => 4
                ],
                [
                    'name' => '家用电器',
                    'sort' => 5
                ],
                [
                    'name' => '个护化妆',
                    'sort' => 6
                ],
                [
                    'name' => '食品饮料',
                    'sort' => 7
                ],
                [
                    'name' => '厨房餐饮',
                    'sort' => 8
                ],
                [
                    'name' => '珠宝手表',
                    'sort' => 9
                ],
                [
                    'name' => '运动健康',
                    'sort' => 10
                ],
                [
                    'name' => '汽车用品',
                    'sort' => 11
                ],
                [
                    'name' => '玩具乐器',
                    'sort' => 12
                ],
                [
                    'name' => '母婴用品',
                    'sort' => 1
                ]
            ]);
        }
    }

    private function _initArea()
    {
        $josn = file_get_contents('https://os.alipayobjects.com/rmsportal/ODDwqcDFTLAguOvWEolX.json');
        $data = json_decode($josn, true);

        $this->_saveArea($data, 0, 1);
    }


    private function _saveArea($data, $value, $deep)
    {
        foreach ($data as $v) {
            Area::query()->updateOrCreate(['value' => $v['value']], [
                'value' => $v['value'],
                'label' => $v['label'],
                'parent_id' => $value,
                'deep' => $deep
            ]);
            try {
                if ($v['children']) {
                    $this->_saveArea($v['children'], $v['value'], $deep + 1);
                }
            } catch (\Exception $s) {

            }

        }

    }
}