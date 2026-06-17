<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PriceCoupon extends Model
{
    protected $primaryKey = 'price_coupon_id';
    protected $table = 'price_coupon';
    protected $fillable = [
        'rupee_min',
        'rupee_max',
        'doller_min',
        'doller_max',
        'euro_min',
        'euro_max',
        'coupen_code',
        'rupee_discount',
        'doller_discount',
        'euro_discount',
        'coupen_valid',
        'description',
        'is_active',
            ];
    protected $hidden = [
        '_token',
    ];
}