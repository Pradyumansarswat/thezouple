<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;

class orderStatusExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    
    protected $publisherCode;

 function __construct($orderStatus) {
        $this->orderStatus = $orderStatus;
 }

    
    public function collection()
    {
        if($this->orderStatus != "all")
        {
            return DB::table('order_system')->where('order_status', $this->orderStatus)->get();
        }
        else
        {
           return DB::table('order_system')->get(); 
        }
        
    }
    
    public function headings(): array
    {
        return [
            'Sr.no',
            'order_type',
            'query_Text',
            'user_id',
            'billing_address_id',
            'shipping_address_id',
            'order_number',
            'product_details',
            'total_amount',
            'discount',
            'net_amount',
            'product_gst',
            'shipping',
            'purchase_type',
            'coupon_code',
            'coupon_discount',
            'coupenApply',
            'coupon_type',
            'tracking_number',
            'tracking_url',
            'order_status',
            'user_report',
            'user_description',
            'transaction_id',
            'payment_message',
            'payment_status',
            'order_update_date',
            'remark',
            'status',
            'order_date',
            'cust_order_update_date',
            'refund_status', 
            'mail_subject',
            'amount_type',
            'payment_getway',
            'token',
            'create_at',
            'updated_at',
            ];
    }
}
