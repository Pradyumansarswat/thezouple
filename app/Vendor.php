<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model
{
    use SoftDeletes;

    protected $table = 'vendors';
    protected $primaryKey = 'vendor_id';

    protected $fillable = [
       
        'vendor_name',
       

    ];
    protected $hidden = [
        '_token',
    ];
}
