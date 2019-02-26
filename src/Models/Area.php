<?php

namespace SmallRuralDog\Store\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $table = 'areas';
    protected $primaryKey = 'value';
    public $timestamps = false;


    public function getAll()
    {
        return \Cache::remember('area-all', 60, function () {
            $data = Area::query()->get()->toArray();
            return $this->_setArea($data, 0);
        });
    }


    private function _setArea($data, $value)
    {
        return array_values(collect($data)->filter(function ($item) use ($value) {
            return $item['parent_id'] == $value;
        })->transform(function ($item, $key) use ($data) {
            if ($item['deep'] < 3) {
                return [
                    'value' => $item['value'],
                    'label' => $item['label'],
                    'children' => $this->_setArea($data, $item['value'])
                ];
            } else {
                return [
                    'value' => $item['value'],
                    'label' => $item['label']
                ];
            }
        })->all());
    }
}
