<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pincode extends Model
{
    protected $table = 'pincodes';
    protected $fillable = [
        'pincode',
    ];
    protected $hidden = [
        '_token',
    ];
}
