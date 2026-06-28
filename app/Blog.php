<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blog extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'blog_id';
    protected $table = 'blog';
    protected $fillable = [
        'slug',
        'heading',
        'date',
        'image',
        'description'
            ];
    protected $hidden = [
        '_token',
    ];
}
