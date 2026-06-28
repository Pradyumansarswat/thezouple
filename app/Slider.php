<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Slider extends Model
{
    use SoftDeletes;

    protected $table = 'sliders';
    protected $primaryKey = 'slider_id';

    protected $fillable = [
        'image',
        'is_active',
        'heading',
        'name',
        'description',
        'alignment'

    ];
    protected $hidden = [
        '_token',
    ];
}
