<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $primaryKey = 'currency_id';
    protected $table = 'currency';
    protected $fillable = [
        'currency_code',
        'currency'
            ];
    protected $hidden = [
        '_token',
    ];
}
