<?php


namespace SmallRuralDog\Store\Services;


use SmallRuralDog\Store\Models\Store;

class StoreService
{
    public function createStore($data, $user)
    {
        return Store::query()->create([
            'user_id' => $user->id,
            'class_id' => $data['class_id'],
            'store_name' => $data['store_name'],
        ]);
    }

    public function initStore($user)
    {
        return $this->createStore([
            'class_id' => 1,
            'store_name' => $user->name . '的店铺',
        ], $user);
    }

}