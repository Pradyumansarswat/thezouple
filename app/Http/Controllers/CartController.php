<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth,Redirect,View,File,Config,Image;
use Validator;
use DB;
use Input;
use App\Helpers\BasicFunction;
use Response;
use Session;
use App\Helper\CurrencyHelper;
use App\Product;
use App\Category;

class CartController extends Controller
{
    private function applyCurrentCartOwner($query, Request $request, $table = 'carts')
    {
        $ip = $request->ip();

        if (Auth::check()) {
            $userId = Auth::user()->id;

            return $query->where(function ($ownerQuery) use ($table, $ip, $userId) {
                $ownerQuery->where($table . '.user_id', $userId)
                    ->orWhere(function ($guestQuery) use ($table, $ip) {
                        $guestQuery->where($table . '.ip_address', $ip)
                            ->where(function ($emptyUserQuery) use ($table) {
                                $emptyUserQuery->whereNull($table . '.user_id')
                                    ->orWhere($table . '.user_id', 0)
                                    ->orWhere($table . '.user_id', '');
                            });
                    });
            });
        }

        return $query->where($table . '.ip_address', $ip)
            ->where(function ($guestQuery) use ($table) {
                $guestQuery->whereNull($table . '.user_id')
                    ->orWhere($table . '.user_id', 0)
                    ->orWhere($table . '.user_id', '');
            });
    }

    // New Cart List --------
    public function cart_list(Request $request)
    {
        
        
        $CouponCode = "";
        $slug="";
        $data['proAttributes'] = [];
        $gst = $this->applyCurrentCartOwner(DB::table('carts')
                     ->join('products', 'products.product_id', '=', 'carts.product_id')
                     , $request)
                    ->max('products.product_gst');
                    
        $data['cart_data'] = $this->applyCurrentCartOwner(DB::table('carts')
             ->join('product_quantity','product_quantity.product_quantity_id','carts.product_qty_id')
             ->join('products', 'products.product_id', '=', 'carts.product_id')
             , $request)
             ->get();
        
        
        $page_title = "Shoping Cart - Zouple";
        
        
        
        $total_net_amount = 0;
        $total_final_amount = 0;
        $total_discount = 0;
        $total_shipping = 0;
        $total_pro_gst = 0;
        $minKg = MINIMUMKG;

        $currencySession = CurrencyHelper::normalizeSessionCurrency(Session::get('currency'));
                            
         if($currencySession == "rupee_price")
         {
             $rupeeShipCh = RUPEESHIPPINCHARGE;
             $rs_dis = "rupee_discount";
             $amt= "rupee_min";

             $sy='INR';
             
         }
         elseif(CurrencyHelper::isDollarCurrency($currencySession))
         {
             $rupeeShipCh = DOLLARSHIPPINCHARGE;
             $rs_dis = "doller_discount";
             $amt= "doller_min";
             $sy='Dollar';
         } 
         elseif($currencySession == "euro_price")
         {
             $rupeeShipCh = EUROSHIPPINCHARGE;
             $rs_dis = "euro_discount";
             $amt= "euro_min";
             $sy='Euro';
         }
         else
         {
             $rupeeShipCh = RUPEESHIPPINCHARGE;
             $rs_dis = "rupee_discount";
             $amt= "rupee_min";
             $sy='INR';
         }
        
        foreach($data['cart_data'] as $dt)
        {

            $proIds[] = $dt->product_id;


            $cat_list = json_decode($dt->category);
            $slug = DB::table('categorys')->where('category_id',$cat_list[0])->value('slug');
            
            
            if($currencySession == "rupee_price")
             {
                 $net_amount = $dt->rupee_net_amount * $dt->product_qty;
             }
             elseif(CurrencyHelper::isDollarCurrency($currencySession))
             {
                 $net_amount = $dt->dollar_net_amount * $dt->product_qty;
             } 
             elseif($currencySession == "euro_price")
             {
                 $net_amount = $dt->euro_net_amount * $dt->product_qty;
             }
             else
             {
                $net_amount = $dt->rupee_net_amount * $dt->product_qty;
             }
            
            
            
            
           
            $pro_gst = $net_amount * $dt->product_gst / 100;
            
            $total_pro_gst = $total_pro_gst+$pro_gst;
            
            $total_net_amount = $total_net_amount+$net_amount;
            
        }
        
        if(isset($proIds))
        {
            $pros = Product::whereIN('product_id', $proIds)->get();
            
            foreach($pros as $datas)
            {
                $pro_id = $datas->product_id;
                $data['proAttributes'][$datas->product_id] = DB::table('product_attributes')->where('product_id',$pro_id)->get();
            }
        }

        foreach($data['cart_data'] as $cartItem)
        {
            if (!isset($data['proAttributes'][$cartItem->product_id])) {
                $data['proAttributes'][$cartItem->product_id] = collect();
            }
        }
           
         $total_final_amount = $total_net_amount + $total_pro_gst;
        
         
        $total_final_amount = $total_net_amount + $total_pro_gst;
        
        
      
        if($currencySession == "rupee_price")
        {
            $priceCouponId = DB::table('price_coupon')->where('is_active', 'ACTIVE')->where('rupee_min', '<', $total_final_amount)->where('rupee_max', '>', $total_final_amount)->value('price_coupon_id');

            $priceCouponIds =  DB::table('price_coupon')->where('is_active', 'ACTIVE')->where('rupee_min', '>', $total_final_amount)->orderBy('rupee_max', 'ASC')->limit(1)->value('price_coupon_id');
            
            $data['discountCouponFetchData'] = DB::table('price_coupon')->where('is_active', 'ACTIVE')->where('rupee_min', '<', $total_final_amount)->where('rupee_max', '>', $total_final_amount)->limit(1)->value('rupee_discount');
            
            
        }
             elseif(CurrencyHelper::isDollarCurrency($currencySession))
        {

            $priceCouponId = DB::table('price_coupon')->where('is_active', 'ACTIVE')->where('doller_min', '<', $total_final_amount)->where('doller_max', '>', $total_final_amount)->value('price_coupon_id');

            $priceCouponIds =  DB::table('price_coupon')->where('is_active', 'ACTIVE')->where('doller_min', '>', $total_final_amount)->orderBy('doller_max', 'ASC')->limit(1)->value('price_coupon_id');
            
            $data['discountCouponFetchData'] = DB::table('price_coupon')->where('is_active', 'ACTIVE')->where('doller_min', '<', $total_final_amount)->where('doller_max', '>', $total_final_amount)->limit(1)->value('doller_discount');
        } 
        elseif($currencySession == "euro_price")
        {

            $priceCouponId = DB::table('price_coupon')->where('is_active', 'ACTIVE')->where('euro_min', '<', $total_final_amount)->where('euro_max', '>', $total_final_amount)->value('price_coupon_id');

            $priceCouponIds =  DB::table('price_coupon')->where('is_active', 'ACTIVE')->where('euro_min', '>', $total_final_amount)->orderBy('euro_max', 'ASC')->limit(1)->value('price_coupon_id');
            
            $data['discountCouponFetchData'] = DB::table('price_coupon')->where('is_active', 'ACTIVE')->where('euro_min', '<', $total_final_amount)->where('euro_max', '>', $total_final_amount)->limit(1)->value('euro_discount');
        }
        else
        {
            $priceCouponId = DB::table('price_coupon')->where('is_active', 'ACTIVE')->where('doller_min', '<', $total_final_amount)->where('doller_max', '>', $total_final_amount)->value('price_coupon_id');

            $priceCouponIds =  DB::table('price_coupon')->where('is_active', 'ACTIVE')->where('doller_min', '>', $total_final_amount)->orderBy('doller_max', 'ASC')->limit(1)->value('price_coupon_id');
            
            $data['discountCouponFetchData'] = DB::table('price_coupon')->where('is_active', 'ACTIVE')->where('doller_min', '<', $total_final_amount)->where('doller_max', '>', $total_final_amount)->limit(1)->value('doller_discount');
        }
        
      
        // Make Payment for Coupon

        $status="null";

        $check_code = 0;
        $CouponCode = "";
        $CouponDiscount = 0;
        
        $check_code = $priceCouponIds;
        
        $CouponCode = DB::table('price_coupon')->where('price_coupon_id',$check_code)->value('coupen_code');
        
        $CouponDiscount = DB::table('price_coupon')->where('price_coupon_id',$check_code)->value($rs_dis);
        $CouponAmount = DB::table('price_coupon')->where('price_coupon_id',$check_code)->value($amt);
        $price_per = DB::table('price_coupon')->where('price_coupon_id',$check_code)->value($rs_dis);
       
     
        $reming = round($CouponAmount - $total_final_amount);
        if($CouponAmount > $total_final_amount)
        {
            $status = "YES";
        }
        else
        {
            $status="NO";
        }
        
        ////////////////////////////
        $dailog_text = "Coupon " . $CouponCode . " applied successfully.  Shop more of  " . $sy . " " .$reming . "  to get instant " . $CouponDiscount . "% discount";
       
        if($slug == "")
        {
            $slug = DB::table('categorys')->where('category_id',1)->value('slug');    
        }
        
        return view('front.cart.shopping_cart',compact('page_title','gst','slug','dailog_text','check_code','sy','status'),$data);
        
    }
    
    // Remove Item in Cart
    
    public function product_remove_cart(Request $request, $id)
    {
         $deleted = $this->applyCurrentCartOwner(DB::table('carts')->where('cart_id',$id), $request)->delete();

         if (!$deleted) {
             return Response::json(['message' => 'Cart item was not found.'], 404);
         }

         return 'Your product is remove in your cart';
    }
    
    // Get Arrtributes List
    
    public function getAttributesData(Request $request,$id)
    {
        $prso_data = Db::table('product_attributes')->where('product_id', $id)->pluck('attribute_name');
        /*echo"<pre>";
        print_r($prso_data);
          echo"</pre>";*/
        return json_encode($prso_data);
        
    }
    
     public function updateCartSystemStore(Request $request)
    {
        
        
        $product_id = $request->product_id;
        $pro_qty = $request->pro_qty;
        $cart_id = $request->cart_id;
        /*$user_id = Auth::user()->id;*/
        
        $pro_qty_det = explode(',',$request->filter);
         $pro_det = json_encode($pro_qty_det);
         
         $input['product_qty_id'] = DB::table('product_quantity')->where('attributes_value',$pro_det)->where('product_id',$product_id)->value('product_quantity_id');
         
        $input['product_qty'] = $pro_qty;
     
        $updated = $this->applyCurrentCartOwner(DB::table('carts')->where('cart_id',$cart_id), $request)->update($input);

        if (!$updated) {
            return Response::json(['message' => 'Cart item was not found.'], 404);
        }
        
        return $request;
    }
    //clear Cart 
    public function shopping_cart_clear(Request $request)
    {
        $this->applyCurrentCartOwner(DB::table('carts'), $request)->delete();
        Session::put('item', 0);
        Session::save();
      return 'Your cart is empty';  
         
    }
    
    
    /* Buy Now Code Start */
    
        
    public function purchaseNow(Request $request)
    {
        $currencySession = Session::get('currency');
        $pro_qty = $request->pro_qty;
        $filter = $request->filter;
        $pro_id = $request->product_id;
        /*return $request;*/
        
        $page_title = "Buy Now - Zouple";
        
        $user_id = Auth::user()->id;

        $data['buynow_shipping_list'] = DB::table('user_information')
                ->join('users','users.id','user_information.user_id')
                ->where('user_information.user_id',Auth::user()->id)
                ->where('user_information.default_address',"YES")
                ->where('user_information.addresstype','Shipping')->get();

        $data['buynow_billing_list'] = DB::table('user_information')
                ->join('users','users.id','user_information.user_id')
                ->where('user_information.user_id',Auth::user()->id)
                ->where('user_information.default_address',"YES")
                ->where('user_information.addresstype','Billing')->get();
        
        
        $data['showBilling'] = DB::table('user_information')
                ->where('user_information.user_id',Auth::user()->id)
                ->where('user_information.addresstype','Billing')->get();  
        
        $data['showShipping'] = DB::table('user_information')
                ->where('user_information.user_id',Auth::user()->id)
                ->where('user_information.addresstype','Shipping')->get();
        
        $filter1 = explode(',' , $filter);
        
        $filter2 = json_encode($filter1);
        
        
         $shipping = DB::table('product_quantity')
             ->join('products', 'products.product_id',  'product_quantity.product_id')
             ->where('product_quantity.product_id',$pro_id)->where('product_quantity.attributes_value',$filter2)
             ->value('product_weight');
        
        $data['cart_data'] = DB::table('product_quantity')
             ->join('products', 'products.product_id',  'product_quantity.product_id')
             ->where('product_quantity.product_id',$pro_id)->where('product_quantity.attributes_value',$filter2)
             ->get();
        
        
        $minKg = MINIMUMKG;
        if($currencySession == "rupee_price")
         {
             $ship_ch = RUPEESHIPPINCHARGE;
             
         }
         elseif($currencySession == "dollar_price")
         {
             $ship_ch = DOLLARSHIPPINCHARGE;
         } 
         elseif($currencySession == "euro_price")
         {
             $ship_ch = EUROSHIPPINCHARGE;
         }
         else
         {
            $ship_ch = DOLLARSHIPPINCHARGE;
         }
        $shipping = $shipping;
        if($shipping < $minKg)
        {
            $shipping = 0;
        }
        else
        {
            $shipping = $shipping * $ship_ch * $pro_qty;
        }
        
       /* echo $shipping;*/
         $gst = DB::table('products')->where('product_id',$pro_id)->value('product_gst');
        $gst = $gst*$pro_qty;
        /*return  $request;*/
        return view('front.payment.buynow',compact('page_title','pro_qty', 'gst', 'shipping'),$data); 
    }
    
    /* Buy Now Code End */
    // New Buy now Coupon
    public function buyNowSingleCoupenApply(Request $request)
    {
        $product_qty_id = $request->product_qty_id;
        $product_id = $request->product_id;
        $pro_qty = $request->pro_qty;
        $coupon = $request->coupon;
        $user_id = Auth::user()->id;
        $discount_percent = 0;
        $coupon_type="NONE";
        
        $amount = round(DB::table('product_quantity')->where('product_quantity_id',$product_qty_id)->value('net_amount'));
        
        $net_with_gst = round(DB::table('product_quantity')->where('product_quantity_id',$product_qty_id)->value('net_with_gst'));
        $weight = DB::table('product_quantity')->where('product_quantity_id',$product_qty_id)->value('product_weight');
        $gst = DB::table('products')->where('product_id',$product_id)->value('product_gst');
         
        $minKg = MINIMUMKG;
        $ship_ch = SHIPPINCHARGE;
        
        if($weight < $minKg)
        {
            $shipping = 0;
        }
        else
        {
            $shipping = $weight * $ship_ch * $pro_qty;
        }
        
        ////////////Coupon check ////////////////
        
        $procouponcheck = DB::table('product_coupon')->where('coupon_code',$coupon)->get();
        $custcouponcheck = DB::table('customer_coupon')->where('coupon_code',$coupon)->get();
        if(!$procouponcheck->isEmpty())
        {

            foreach($procouponcheck as $data)
            {
                $coupen_vaild = $data->coupon_valid;
                $coupen_percent = $data->coupon_discount;
                $product_ids =  json_decode($data->product_id);
                $coupon_id = $data->product_coupon_id;
                foreach($product_ids as $pro_id)
                {
                    if($pro_id == $product_id)
                    {
                        $coupon_uses = DB::table('coupon_uses')->where('coupon_type','PRODUCT')->where('user_id',$user_id)->where('coupon_id',$data->product_coupon_id)->count('coupen_uses_id');
                       
                        if($coupen_vaild > $coupon_uses)
                        {
                            $discount_percent = $coupen_percent;
                            $coupenApply = "YES";
                            $text = "Hurray!! Coupon code has been successfully applied on your purchase.";
                            $coupon_type="PRODUCT";
                        }
                        else
                        {
                            $coupenApply = "NO";
                            $text = "Oops!! Coupon code has exceeded the number of times it can be used.";
                        }
                        
                       
                        break;
                    }
                    else
                    {
                        $coupenApply = "NO";
                        $text = "Oops!! You have selected an invalid product for this coupon code. Kindly selected a valid product for this coupon code to the benefits.";
                    }
    
                }
            }
        }
        elseif(!$custcouponcheck->isEmpty())
        {
            foreach($custcouponcheck as $data)
            {
                $coupen_vaild = $data->coupon_valid;
                $coupen_percent = $data->coupon_discount;
                $customer_ids =  json_decode($data->id);
                $coupon_id =  $data->customer_coupon_id;
                foreach($customer_ids as $pro_id)
                {
                    if($pro_id == $user_id)
                    {
                        $coupon_uses = DB::table('coupon_uses')->where('coupon_type','CUSTOMER')->where('user_id',$user_id)->where('coupon_id',$data->customer_coupon_id)->count('coupen_uses_id');
                        if($coupen_vaild > $coupon_uses)
                        {
                            $coupon_type = "CUSTOMER";
                            $discount_percent = $coupen_percent;
                            $coupenApply = "YES";
                            $text = "Hurray!! Coupon code has been successfully applied on your purchase.";
                        }
                        else
                        {
                            $coupenApply = "NO";
                            $text = "Oops!! Coupon code has exceeded the number of times it can be used.";
                        }
                        
                        break;
                    }
                    else
                    {
                        $coupenApply = "NO";
                        $text = "Oops!! You are not vaild customer for this coupon. Kindly enter a valid coupon code to the benefits.";
                    }
                }
            }
        }
        else
        {
                $coupenApply = "NO";
                $text = "Oops!! You have entered an invalid coupon code. Kindly enter a valid coupon code to avail the benefits.";
            
        }
        
        
        $total_net = $amount * $pro_qty;
               
        $discount = $total_net * $discount_percent/100;
       
        $net_amount = $total_net-$discount;
       
        $gst_amount = $net_amount * $gst/100;
       
        $total = $net_amount + $gst_amount + $shipping;
         
       
        
        $total_net_amount = $total_net;
        $total_discount = $pro_qty* $net_with_gst*$discount_percent/100;
        $total_shipping = $shipping;
        $total_pro_gst = $gst_amount;
        
        
        
        $total_final_amount = $net_with_gst-  $total_discount+ $total_shipping;
        $dataqs['total_discount'] = round($total_discount);
        $dataqs['total_gst'] = number_format($total_pro_gst, 2, '.', '');
        $dataqs['total_net_amount'] = $total_net_amount;
        $dataqs['total_shipping'] = number_format($total_shipping, 2, '.', '');
        $dataqs['total_final_amount'] = round($total_final_amount);
        $dataqs['coupenApply'] = $coupenApply;
        $dataqs['text'] = $text;
        $dataqs['coupon_type'] = $coupon_type;
        $dataqs['discount'] = $discount_percent;
        $dataqs['Coupon_code'] = $coupon;
        $dataqs['coupon_id'] = $coupon_id;
        
        
        return $dataqs;
       
        
        
        
    }
    
    /* Checkout Coupon System */ 
    
    public function checkoutSystemCoupenApply(Request $request)
    {
        $user_id = Auth::id();
        $coupon = $request->coupon;
        $total_amt = $request->total_amt;
        $discount_percent = 0;
        $total_net_amount = 0;
        $total_final_amount = 0;
        $total_discount=0;
        $total_shipping = 0;
        $total_pro_gst = 0;
        $minKg = MINIMUMKG;
        $coupon_id = 0;
        $coupon_type="NONE";
        $currencySession = Session::get('currency');  
        // For shipping Amount //
        if($currencySession == "rupee_price")
        {
        $rupeeShipCh = RUPEESHIPPINCHARGE;

        }
        elseif($currencySession == "dollar_price")
        {
        $rupeeShipCh = DOLLARSHIPPINCHARGE;
        } 
        elseif($currencySession == "euro_price")
        {
        $rupeeShipCh = EUROSHIPPINCHARGE;
        }
        else
        {
        $rupeeShipCh = DOLLARSHIPPINCHARGE;
        }
        $ship_ch = $rupeeShipCh;
        
        //////////////Cart Code ///////////////
        $datass = $this->applyCurrentCartOwner(DB::table('carts')
             ->join('products', 'products.product_id', '=', 'carts.product_id')
             ->join('product_quantity','product_quantity.product_quantity_id','carts.product_qty_id')
             , $request)
             ->get();
        ////////////Coupon check ////////////////
        $procouponcheck = DB::table('product_coupon')->where('coupon_code',$coupon)->get();
        $custcouponcheck = DB::table('customer_coupon')->where('coupon_code',$coupon)->get();
        $pricecouponcheck = DB::table('price_coupon')->where('coupen_code',$coupon)->get();
        //Product Type Coupen //
        if(!$procouponcheck->isEmpty())
        {
            $check_pro_id = 0;
            foreach($procouponcheck as $data)
            {
                $coupen_vaild = $data->coupon_valid;
                //Coupen Discount//
                if($currencySession == "rupee_price")
                {
                    $coupen_percent = $data->rupee_discount;
                }
                elseif($currencySession == "dollar_price")
                {
                    $coupen_percent = $data->doller_discount;
                } 
                elseif($currencySession == "euro_price")
                {
                    $coupen_percent = $data->euro_price;
                }
                else
                {
                    $coupen_percent = $data->doller_discount;
                }
                $coupon_id = $data->product_coupon_id;
                $product_ids =  json_decode($data->product_id);
                $coupon_uses = DB::table('coupon_uses')->where('coupon_type','PRODUCT')->where('user_id',$user_id)->where('coupon_id',$data->product_coupon_id)->count('coupen_uses_id');
                if($coupen_vaild>$coupon_uses)
                {
                    foreach($datass as $cartdata)
                    {
                        if($currencySession == "rupee_price")
                        { 
                            $net_gst =  $cartdata->rupee_net_with_gst;
                            $net_amount = $cartdata->rupee_net_amount; 
                        }
                        elseif($currencySession == "dollar_price")
                        {

                            $net_gst =  $cartdata->dollar_net_with_gst;
                            $net_amount = $cartdata->rupee_net_amount;
                        } 
                        elseif($currencySession == "euro_price")
                        {  
                             $net_gst =  $cartdata->euro_net_with_gst;
                             $net_amount = $cartdata->rupee_net_amount;
                        }
                        else
                        {
                             $net_gst =  $data->dollar_net_with_gst;
                             $net_amount = $cartdata->rupee_net_amount;
                        }
                        $net_amount = $net_amount * $cartdata->product_qty;   
                        $pro_amount = round($net_gst) * $cartdata->product_qty;  
                        $pro_id = $cartdata->product_id;
                        //discount //
                        if(in_array($pro_id, $product_ids))
                        {
                            $check_pro_id = $check_pro_id+1;
                            $discount_percent = $coupen_percent;
                            $discount = $pro_amount * $discount_percent/100;
                        }
                        else
                        {
                            $discount = 0;
                        }
                        //Weight//
                        $weight = $cartdata->product_weight;
                        if($minKg<$weight)
                        {
                            $shipping = $ship_ch * $weight * $cartdata->product_qty;
                        }
                        else
                        {
                            $shipping = 0;
                        }
                        if($check_pro_id > 0)
                        {
                            $coupenApply = "YES";
                            $text = "Hurray!! Coupon code has been successfully applied on your purchase.";
                            $coupon_type="PRODUCT";
                        }
                        else
                        {
                            $coupenApply = "NO";
                            $text = "Oops!! You have selected an invalid product for this coupon code. Kindly selected a valid product for this coupon code to the benefits.";
                        }
                        
                        $finl_amt = $net_amount - $discount;
                        $total_net_amount = $total_net_amount +  $pro_amount;
                        $total_discount = $total_discount + $discount;
                        $total_shipping = $total_shipping + $shipping;
                    }
                }
                else
                {
                    $discount_percent = 0;
                    $coupenApply = "NO";
                    $text = "Oops!! Coupon code has exceeded the number of times it can be used.";
                    foreach($datass as $cartdata)
                    {
                        if($currencySession == "rupee_price")
                        { 
                            $net_gst =  $cartdata->rupee_net_with_gst;
                            $net_amount = $cartdata->rupee_net_amount; 
                        }
                        elseif($currencySession == "dollar_price")
                        {

                            $net_gst =  $cartdata->dollar_net_with_gst;
                            $net_amount = $cartdata->rupee_net_amount;
                        } 
                        elseif($currencySession == "euro_price")
                        {  
                             $net_gst =  $cartdata->euro_net_with_gst;
                             $net_amount = $cartdata->rupee_net_amount;
                        }
                        else
                        {
                             $net_gst =  $data->dollar_net_with_gst;
                             $net_amount = $cartdata->rupee_net_amount;
                        }
                        $net_amount = $net_amount * $cartdata->product_qty;   
                        $pro_amount = round($net_gst) * $cartdata->product_qty;  
                        $pro_id = $cartdata->product_id;
                        $discount = 0;
                        $weight = $cartdata->product_weight;
                        if($minKg<$weight)
                        {
                            $shipping = $ship_ch * $weight * $cartdata->product_qty;
                        }
                        else
                        {
                            $shipping = 0;
                        }
                        $finl_amt = $net_amount - $discount;
                        $total_net_amount = $total_net_amount +  $pro_amount;
                        $total_discount = $total_discount + $discount;
                        $total_shipping = $total_shipping + $shipping;
                    }
                    
                        
                }
                
            }
        }
        elseif(!$custcouponcheck->isEmpty())
        {
            foreach($custcouponcheck as $data)
            {
                $coupen_vaild = $data->coupon_valid;
                $customer_ids =  json_decode($data->id);
                $coupon_id =  $data->customer_coupon_id;
                if($currencySession == "rupee_price")
                {
                    $coupen_percent = $data->rupee_discount;
                }
                elseif($currencySession == "dollar_price")
                {
                    $coupen_percent = $data->doller_discount;
                } 
                elseif($currencySession == "euro_price")
                {
                    $coupen_percent = $data->euro_price;
                }
                else
                {
                    $coupen_percent = $data->doller_discount;
                }
                
                if(in_array($user_id, $customer_ids))
                {
                    $coupon_uses = DB::table('coupon_uses')->where('coupon_type','CUSTOMER')->where('user_id',$user_id)->where('coupon_id',$data->customer_coupon_id)->count('coupen_uses_id');
                    if($coupen_vaild > $coupon_uses)
                    {
                        $discount_percent = $coupen_percent;
                        $coupenApply = "YES";
                        $text = "Hurray!! Coupon code has been successfully applied on your purchase.";
                        $coupon_type = "CUSTOMER";
                    }
                    else
                    {
                        $discount_percent = 0;
                        $coupenApply = "NO";
                        $text = "Oops!! Coupon code has exceeded the number of times it can be used.";
                    }
                    foreach($datass as $cartdata)
                    {
                        if($currencySession == "rupee_price")
                        { 
                            $net_gst =  $cartdata->rupee_net_with_gst;
                            $net_amount = $cartdata->rupee_net_amount; 
                        }
                        elseif($currencySession == "dollar_price")
                        {

                            $net_gst =  $cartdata->dollar_net_with_gst;
                            $net_amount = $cartdata->rupee_net_amount;
                        } 
                        elseif($currencySession == "euro_price")
                        {  
                             $net_gst =  $cartdata->euro_net_with_gst;
                             $net_amount = $cartdata->rupee_net_amount;
                        }
                        else
                        {
                             $net_gst =  $data->dollar_net_with_gst;
                             $net_amount = $cartdata->rupee_net_amount;
                        }



                        $net_amount = $net_amount * $cartdata->product_qty;
                        $pro_amount =round($net_gst) * $cartdata->product_qty;  
                        $discount = $pro_amount * $discount_percent/100;
                        $weight = $cartdata->product_weight;

                        if($minKg<$weight)
                        {
                            $shipping = $ship_ch * $weight * $cartdata->product_qty;
                        }
                        else
                        {
                            $shipping = 0;
                        }

                        $finl_amt = $pro_amount - $discount;
                        $total_net_amount = $total_net_amount +  $pro_amount;
                        $total_discount = $total_discount + $discount;
                        $total_shipping = $total_shipping + $shipping;
                    }
                }
                        
                else
                {
                    $coupenApply = "NO";
                    $text = "Oops!! You are not vaild customer for this coupon. Kindly enter a valid coupon code to the benefits.";
                    $coupon_type="NO COUPON";
                    $discount_percent = 0;
                    foreach($datass as $cartdata)
                    {
                        if($currencySession == "rupee_price")
                        { 
                            $net_gst =  $cartdata->rupee_net_with_gst;
                            $net_amount = $cartdata->rupee_net_amount; 
                        }
                        elseif($currencySession == "dollar_price")
                        {

                            $net_gst =  $cartdata->dollar_net_with_gst;
                            $net_amount = $cartdata->rupee_net_amount;
                        } 
                        elseif($currencySession == "euro_price")
                        {  
                             $net_gst =  $cartdata->euro_net_with_gst;
                             $net_amount = $cartdata->rupee_net_amount;
                        }
                        else
                        {
                             $net_gst =  $data->dollar_net_with_gst;
                             $net_amount = $cartdata->rupee_net_amount;
                        }
                        $net_amount = $net_amount * $cartdata->product_qty;   
                        $pro_amount = round($net_gst) * $cartdata->product_qty;  
                        $pro_id = $cartdata->product_id;
                        $discount = 0;
                        $weight = $cartdata->product_weight;
                        if($minKg<$weight)
                        {
                            $shipping = $ship_ch * $weight * $cartdata->product_qty;
                        }
                        else
                        {
                            $shipping = 0;
                        }
                        $finl_amt = $net_amount - $discount;
                        $total_net_amount = $total_net_amount +  $pro_amount;
                        $total_discount = $total_discount + $discount;
                        $total_shipping = $total_shipping + $shipping;
                    }
                }
            }
        }
        
        
        
        else
        {
            $coupenApply = "NO";
            $text = "Oops!! You have entered an invalid coupon code. Kindly enter a valid coupon code to avail the benefits.";
            $coupon_type="NO COUPON";
            $discount_percent = 0;
            foreach($datass as $cartdata)
            {
                if($currencySession == "rupee_price")
                { 
                    $net_gst =  $cartdata->rupee_net_with_gst;
                    $net_amount = $cartdata->rupee_net_amount; 
                }
                elseif($currencySession == "dollar_price")
                {

                    $net_gst =  $cartdata->dollar_net_with_gst;
                    $net_amount = $cartdata->rupee_net_amount;
                } 
                elseif($currencySession == "euro_price")
                {  
                     $net_gst =  $cartdata->euro_net_with_gst;
                     $net_amount = $cartdata->rupee_net_amount;
                }
                else
                {
                     $net_gst =  $data->dollar_net_with_gst;
                     $net_amount = $cartdata->rupee_net_amount;
                }
                $net_amount = $net_amount * $cartdata->product_qty;   
                $pro_amount = round($net_gst) * $cartdata->product_qty;  
                $pro_id = $cartdata->product_id;
                $discount = 0;
                $weight = $cartdata->product_weight;
                if($minKg<$weight)
                {
                    $shipping = $ship_ch * $weight * $cartdata->product_qty;
                }
                else
                {
                    $shipping = 0;
                }
                $finl_amt = $net_amount - $discount;
                $total_net_amount = $total_net_amount +  $pro_amount;
                $total_discount = $total_discount + $discount;
                $total_shipping = $total_shipping + $shipping;
            }
            
        }
        
        //- Final Submit --//
        $total_final_amount = round($total_net_amount) + round($total_shipping) - round($total_discount);
        
        $dataqs['total_discount'] = round($total_discount);
        
        $dataqs['total_net_amount'] = $total_net_amount;
        $dataqs['total_shipping'] = $total_shipping;
        $dataqs['total_final_amount'] =$total_final_amount;
        $dataqs['coupenApply'] = $coupenApply;
        $dataqs['text'] = $text;
        $dataqs['coupon_type'] = $coupon_type;
        $dataqs['discount'] = $discount_percent;
        $dataqs['Coupon_code'] = $coupon;
        $dataqs['coupon_id'] = $coupon_id;
        
        return $dataqs;
        
        
    }
    
    
    
    
    /* Flash Purduct Code Start */
    
    public function FlashProductPurchashNow(Request $request)
    {

        $currencySession = Session::get('currency');

        $pro_qty = $request->pro_qty;
        $filter = $request->filter;
        $pro_id = $request->product_id;
        /*return $request;*/
        
        $page_title = "Buy Now - Zouple";
        
        $user_id = Auth::user()->id;

        $data['buynow_shipping_list'] = DB::table('user_information')
                ->join('users','users.id','user_information.user_id')
                ->where('user_information.user_id',Auth::user()->id)
                ->where('user_information.default_address',"YES")
                ->where('user_information.addresstype','Shipping')->get();

        $data['buynow_billing_list'] = DB::table('user_information')
                ->join('users','users.id','user_information.user_id')
                ->where('user_information.user_id',Auth::user()->id)
                ->where('user_information.default_address',"YES")
                ->where('user_information.addresstype','Billing')->get();
        
        
        $data['showBilling'] = DB::table('user_information')
                ->where('user_information.user_id',Auth::user()->id)
                ->where('user_information.addresstype','Billing')->get();  
        
        $data['showShipping'] = DB::table('user_information')
                ->where('user_information.user_id',Auth::user()->id)
                ->where('user_information.addresstype','Shipping')->get();
        
        $filter1 = explode(',' , $filter);
        
        $filter2 = json_encode($filter1);
        
        
        $data['cart_data'] = DB::table('product_quantity')
             ->join('products', 'products.product_id',  'product_quantity.product_id')
             ->where('product_quantity.product_id',$pro_id)->where('product_quantity.attributes_value',$filter2)
             ->get();

        if($filter != "")
        {

            $pros = explode(',',$request->filter);
            $pro_details = json_encode($pros);

            $pro_qty = $request->pro_qty;

            $product_qut = DB::table('product_quantity')->where('attributes_value',$pro_details)->where('product_id',$pro_id)->value('product_quantity_id');
            
           
             

             if($currencySession == "rupee_price")
             {
                 $flash_sales = DB::table('flash_sale')->where('product_id', $pro_id)->value('product_prize');
                 
             }
             elseif($currencySession == "dollar_price")
             {
                 $flash_sales = DB::table('flash_sale')->where('product_id', $pro_id)->value('dollar_prize');
             } 
             elseif($currencySession == "euro_price")
             {
                 $flash_sales = DB::table('flash_sale')->where('product_id', $pro_id)->value('euro_prize');
             }
             else
             {
                $flash_sales = DB::table('flash_sale')->where('product_id', $pro_id)->value('dollar_prize');
             }

             $flas_dt = json_decode($flash_sales);

             foreach($flas_dt as $key => $dt)
            {
               
                if($key == $product_qut)
                {
                    $pros = explode(",",$dt);
                    /*$net_amount = $pros[1];*/
                }
                
            }
            
             $net_amount = $pros[1];
            
        }
        
        
        
         $shipping = DB::table('product_quantity')
             ->join('products', 'products.product_id','product_quantity.product_id')
             ->where('product_quantity.product_id',$pro_id)->where('product_quantity.attributes_value',$filter2)
             ->value('product_quantity.product_weight');
        
        $minKg = MINIMUMKG;


                            
         if($currencySession == "rupee_price")
         {
             $ship_ch = RUPEESHIPPINCHARGE;
             
         }
         elseif($currencySession == "dollar_price")
         {
             $ship_ch = DOLLARSHIPPINCHARGE;
         } 
         elseif($currencySession == "euro_price")
         {
             $ship_ch = EUROSHIPPINCHARGE;
         }
         else
         {
            $ship_ch = DOLLARSHIPPINCHARGE;
         }
        
        if($shipping < $minKg)
        {
            $shipping = 0;
        }
        else
        {
            $shipping = $shipping * $ship_ch;
        }
        
       /* echo $shipping;*/
         $gst = DB::table('products')->where('product_id',$pro_id)->value('product_gst');
        /*return  $request;*/
        return view('front.payment.flashbuynow',compact('page_title','pro_qty', 'gst', 'shipping', 'net_amount'),$data);
        
    }
   
    
    /* Flash Purduct Code End */
}
