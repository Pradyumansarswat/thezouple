<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductGalleryImage extends Model
{
    protected $table = 'product_gallery_images';

    protected $fillable = [
        'product_id',
        'image',
        'image_url',
        'public_id',
        'sort_order',
    ];
}
