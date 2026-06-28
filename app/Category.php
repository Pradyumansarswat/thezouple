<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $table = 'categorys';
    protected $primaryKey = 'category_id';
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
