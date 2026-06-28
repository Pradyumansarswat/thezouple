<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pincode extends Model
{
    protected $table = 'pincodes';
    protected $fillable = [
        'pincode',
        'city',
        'state',
    ];
    protected $hidden = [
        '_token',
    ];
}
