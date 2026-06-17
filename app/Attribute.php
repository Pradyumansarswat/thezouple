<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    protected $primaryKey = 'attribute_id';
    protected $table = 'attribute';
    protected $fillable = [
        'slug',
        'name'
            ];
    protected $hidden = [
        '_token',
    ];
}
