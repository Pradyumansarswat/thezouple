<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
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
