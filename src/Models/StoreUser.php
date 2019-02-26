<?php


namespace SmallRuralDog\Store\Models;


use Encore\Admin\Auth\Database\Administrator;

class StoreUser extends Administrator
{
    public function store()
    {
        return $this->hasOne(Store::class, 'user_id');
    }
}