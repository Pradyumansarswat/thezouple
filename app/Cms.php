<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cms extends Model
{
    use SoftDeletes;

    protected $table = 'cms';
    protected $primaryKey = 'cms_id';

    protected $fillable = [
        'title',
        'slug',
        'description',
        'meta_title',
        'meta_keywords',
        'meta_description',
    ];
    protected $hidden = [
        '_token',
    ];
}
