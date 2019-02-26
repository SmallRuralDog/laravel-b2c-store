<?php

namespace SmallRuralDog\Store\Http\Controllers;

use Encore\Admin\Layout\Content;
use Illuminate\Routing\Controller;

class StoreController extends Controller
{
    public function index(Content $content)
    {


        return $content->header('商城')->body(view('store::index'));

    }

    public function manage()
    {
        return view('store::manage');
    }

}