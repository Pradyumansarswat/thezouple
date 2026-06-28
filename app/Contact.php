<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use SoftDeletes;

    protected $table = 'contact';
    protected $primaryKey = 'contact_id';
    protected $fillable = [
        'name',
        'email',
        'title',
        'subject',
        'message'
    ];
    protected $hidden = [
        '_token',
    ];
}
