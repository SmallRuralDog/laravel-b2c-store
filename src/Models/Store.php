<?php

namespace SmallRuralDog\Store\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{

    protected $guarded = [];

    protected $casts = [
        'area' => 'array',
    ];

    public function __construct(array $attributes = [])
    {

        $this->setTable(config('store.database.table_prefix') . 'stores');

        parent::__construct($attributes);
    }



}
