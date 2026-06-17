<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AttributValue extends Model
{
    protected $primaryKey = 'element_value_id';
    protected $table = 'element_value';
    protected $fillable = [
        'slug',
        'shirt_category_id',
        'attribut_name',
        'image'
            ];
    protected $hidden = [
        '_token',
    ];
}
