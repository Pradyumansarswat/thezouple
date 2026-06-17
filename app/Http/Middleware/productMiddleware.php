<?php

namespace App\Http\Middleware;

use Closure;
use View;
use DB;
use App\Otherpage;
use App\Category;
use Auth;
use config;

class productMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $pros = DB::table('products')->get();
        foreach($pros as $data)
        {
            $gst = $data->product_gst;
            $proTitle[$data->product_id] = $data->product_title;
            
            $amt = DB::table('product_quantity')->where('product_id',$data->product_id)->min('rupee_net_amount');
            
            $netAmount[$data->product_id] = round((DB::table('product_quantity')->where('product_id',$data->product_id)->min('rupee_net_amount') * $gst/100) + DB::table('product_quantity')->where('product_id',$data->product_id)->min('rupee_net_amount'));
            
            $proPrice[$data->product_id] = round((DB::table('product_quantity')->where('product_id',$data->product_id)->where('rupee_net_amount',$amt)->min('rupee_price') * $gst/100 )+DB::table('product_quantity')->where('product_id',$data->product_id)->where('rupee_net_amount',$amt)->min('rupee_price'));
            
            
            
            $proDiscount[$data->product_id] = DB::table('product_quantity')->where('product_id',$data->product_id)->min('product_discount');
             $proQty[$data->product_id] = DB::table('product_quantity')->where('product_id',$data->product_id)->where('rupee_net_amount',$amt)->value('product_quantity');
          /*  $proDiscount[$data->product_id] = $data->product_discount;*/
            
            $proImage[$data->product_id] = $data->product_header_image;
        }
        if(!$pros->isEmpty())
        {
            View::share('proTitle', $proTitle);
            View::share('proPrice', $proPrice);
            View::share('netAmount', $netAmount);
            View::share('proDiscount', $proDiscount);

            View::share('proQty', $proQty);
         /* View::share('proDiscount', $proDiscount);*/
            
            View::share('proImage', $proImage);
        }
        
        //Product Attributes //
        /*$pros = DB::table('products')->get();
        foreach($pros as $data)
        {
            $pro_id = $data->product_id;
            $pro_Attributes[$data->product_id] = DB::table('product_attributes')->where('product_id',$pro_id)->get();
        }
        if(!$pros->isEmpty())
        {
            View::share('proAttributes', $pro_Attributes);
        }*/
        
        return $next($request);
    }
}
