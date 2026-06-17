<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Email_template extends Model
{
   protected $fillable = [
        'name',      
        'subject',
        'body',
        'template_constants'
    ];
    protected $hidden = [
        '_token',
    ];
}
