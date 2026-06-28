<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $table = 'order_system';
    protected $primaryKey = 'order_id';
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
