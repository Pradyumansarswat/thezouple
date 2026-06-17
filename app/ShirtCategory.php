<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShirtCategory extends Model
{
    protected $primaryKey = 'febric_id';
    protected $table = 'febric';
    protected $fillable = [
        'slug',
        'name',
        'price',
        'image'
            ];
    protected $hidden = [
        '_token',
    ];
}
