<?php

namespace SmallRuralDog\Store\Http\Controllers\Api\V1;

use SmallRuralDog\Store\Http\Controllers\Api\ApiController;

class UserController extends ApiController
{

    public function currentUser(){
        $user = \Admin::user();

        return $user;
    }
}