<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Offerbanner extends Model
{
    use SoftDeletes;

    protected $table = 'offerbanners';
    protected $primaryKey = 'offerbanners_id';
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
