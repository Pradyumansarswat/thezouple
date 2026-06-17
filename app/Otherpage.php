<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Otherpage extends Model
{
    protected $table = 'other_page';
    protected $fillable = [
        'page_title',
        'page_slug',
        'description',
        'parent_id',
        'is_active',
    ];
    protected $hidden = [
        '_token',
    ];
    
    public function childs() {
        return $this->hasMany('App\Otherpage','parent_id','other_id');
    }
}
