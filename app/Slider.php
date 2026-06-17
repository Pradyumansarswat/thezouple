<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $table = 'sliders';

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
