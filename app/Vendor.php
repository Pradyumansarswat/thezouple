<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $table = 'vendors';

    protected $fillable = [
       
        'vendor_name',
       

    ];
    protected $hidden = [
        '_token',
    ];
}
