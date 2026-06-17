<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShirtAttribut extends Model
{
    protected $primaryKey = 'element_id';
    protected $table = 'element';
    protected $fillable = [
        'slug',
        'name',
        'order_no',
        'image'
            ];
    protected $hidden = [
        '_token',
    ];
}
