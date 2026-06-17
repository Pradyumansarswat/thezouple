<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Offerbanner extends Model
{
    protected $table = 'offerbanners';
    protected $fillable = [
        'image',
        'description',
        'banner_name',
        'for_product',
        'slug'
    ];
    protected $hidden = [
        '_token',
    ];
}
