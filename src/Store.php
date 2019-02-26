<?php

namespace SmallRuralDog\Store;

use Encore\Admin\Extension;

class Store extends Extension
{
    public $name = 'store';

    public $views = __DIR__ . '/../resources/views';

    public $assets = __DIR__ . '/../resources/assets';

    public $migrations = __DIR__ . '/../database/migrations';

    public $menu = [
        'title' => '商城',
        'path'  => 'store',
        'icon'  => 'fa-gears',
    ];
}