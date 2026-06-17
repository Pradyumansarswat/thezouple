<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
     protected $primaryKey = 'product_id';
    protected $table = 'products';
    protected $fillable = [
        'slug',
        'category',
        'vendor_id',
        'product_sku',
        'product_title',
        'product_price',
        'product_weight',
        'product_gst',
        'net_amount',
        'new_arrivals',
        'featured_product',
        'product_header_image',
        'product_images',
        'product_description',
        'product_specification',
        'product_addition_information',
        'is_active',
        'in_stock'
            ];
    protected $hidden = [
        '_token',
    ];
    
    
    
}
