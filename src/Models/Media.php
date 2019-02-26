<?php

namespace SmallRuralDog\Store\Models;

use Illuminate\Database\Eloquent\Model;


class Media extends Model
{


    protected $guarded = [];
    protected $casts = [
        'meta' => 'array',
    ];


    public function __construct(array $attributes = [])
    {

        $this->setTable(config('store.database.table_prefix') . 'media');
        parent::__construct($attributes);
    }


    const IMAGE = 'image';
    const VIDEO = 'video';
    const AUDIO = 'audio';
}
