<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'order_system';
    protected $fillable = [
        'user_id',
        'order_number',
        'product_details',
        'amount',
        'order_status',
        'transaction_id',
        'payment_status',
        'order_date',
        'address_id',
        'offer_code'
    ];
    protected $hidden = [
        '_token',
    ];
}
