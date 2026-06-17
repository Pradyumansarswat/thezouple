<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductQty extends Model
{
    protected $primaryKey = 'product_quantity_id';
    protected $table = 'product_quantity';
    protected $fillable = [
        'product_id',
        'attributes_value',
        'product_quantity',
        'rupee_price',
        'dollar_price',
        'euro_price',
        'doller_net_amount',
        'euro_net_amount',
        'doller_net_with_gst',
        'euro_net_with_gst',
        'product_discount',
        'net_amount',
        'net_with_gst',
        'product_weight'
            ];
    protected $hidden = [
        '_token',
    ];
    
 
}
