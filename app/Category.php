<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categorys';
    protected $fillable = [
        'image',
        'is_active',
        'title',
        'slug',
        'parent_id',
        'meta_title',
        'meta_keyword',
        'description',
        'attributesvalue'
    ];
    protected $hidden = [
        '_token',
    ];
    
    public function childs() {
        return $this->hasMany('App\Category','parent_id','category_id') ;
    }
}
