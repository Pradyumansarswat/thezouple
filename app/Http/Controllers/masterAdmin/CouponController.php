<?php

namespace App\Http\Controllers\masterAdmin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Email_template;
   
use Auth,Redirect,View,File,Config,Image;
use Validator;
use DB;
use Input;
use Mail;
use App\Helper\BasicHelper;
use App\Coupon;

class CouponController extends Controller
{
    public function productCouponList(REQUEST $request)
    {
        $data['product_coupon_data'] = DB::table('product_coupon')->orderby('product_coupon_id', 'asc')->get();
        $pros = DB::table('products')->get();
        foreach($pros as $datas)
        {
            $data['proTitle'][$datas->product_id] = $datas->product_title;
        }
        $page_title = "Product Coupon - Zouple";
        return view('masters.coupon.product_coupon',compact('page_title'), $data);
    }
    
    public function productCouponStatusAllUpdateStore(Request $request)
    {
        
        $input = $request->all();
        $is_active = $request->is_active; 
        DB::table('product_coupon')->update($input);
        $request->session()->flash('alert-success','Product Coupon Status has been sucessfully updated.');
        return Redirect::route('productCoupon');
    }
    
    public function addProductCouponpage(REQUEST $request)
    {
         $data['product_data'] = DB::table('products')->orderby('product_id', 'asc')->get();
         $page_title = "Add Product Coupon - Zouple";
         return view('masters.coupon.add_product_coupon',compact('page_title'), $data);
    }
    
    public function productCouponStore(REQUEST $request)
    {
         $input = $request->all();
         $input['product_id'] = json_encode($request->product_id);
         unset($input['example_length']);
         DB::table('product_coupon')->insert($input);
         $request->session()->flash('alert-success','Product Coupon has been sucessfully added.');
         return Redirect::route('addProductCoupon');
    }
    
    public function productCouponUpdatepage(REQUEST $request, $product_coupon_id)
    {
         $data['product_data'] = DB::table('products')->orderby('product_id', 'asc')->get();
        
         $data['product_coupon_datas'] = DB::table('product_coupon')->where('product_coupon_id', $product_coupon_id)->get();
        
         $page_title = "Edit Product Coupon - Zouple";
         return view('masters.coupon.edit_product_coupon',compact('page_title'), $data);
    }
    
    public function productCouponEditStore(REQUEST $request)
    {
         $input = $request->all();
         $product_coupon_id = $request->product_coupon_id; 
         $input['product_id'] = json_encode($request->product_id);
         unset($input['example_length']);
         DB::table('product_coupon')->where('product_coupon_id', $product_coupon_id)->update($input);
         $request->session()->flash('alert-success','Product Coupon has been sucessfully updated.');
         return Redirect::route('productCoupon');
    }
    
    public function productCouponDeleteFormat(Request $request,$product_coupon_id)
  {
      
      $m = DB::table('product_coupon')->where('product_coupon_id', $product_coupon_id)->delete();
      $request->session()->flash('alert-success','Product Coupon has been sucessfully deleted.');
      return Redirect::route('productCoupon');
  }
    
    public function productCouponStatusUpdateSave(Request $request)
    {
        $input = $request->all();
        $product_coupon_id = $request->product_coupon_id; 
        DB::table('product_coupon')->where('product_coupon_id', $product_coupon_id)->update($input);
        $request->session()->flash('alert-success','Product Coupon Status has been sucessfully updated.');
        return Redirect::route('productCoupon');
    }
    
    
    /* Customer Coupon Code Start */
    
    public function customerCouponList(REQUEST $request)
    {
        $data['customer_coupon_data'] = DB::table('customer_coupon')->orderby('customer_coupon_id', 'asc')->get();
        $page_title = "Customer Coupon - Zouple";
        return view('masters.coupon.customer_coupon',compact('page_title'), $data);
    }
    
    
    public function customerCouponStatusAllUpdateStore(Request $request)
    {
        
        $input = $request->all();
        $is_active = $request->is_active; 
        DB::table('customer_coupon')->update($input);
        $request->session()->flash('alert-success','Customer Coupon Status has been sucessfully updated.');
        return Redirect::route('customerCoupon');
    }
    
    
    public function addCustomerCouponPage(REQUEST $request)
    {
         $page_title = "Add Customer Coupon - Zouple";
         $data['user_data'] = DB::table('users')->where('user_role', 'FRONT')->orderby('id', 'DESC')->get();
         foreach($data['user_data'] as $dt)
         {
             $cust_id = $dt->id;
             $cust_ids[$cust_id] = $dt->id;
             $cust_name[$cust_id] = $dt->name;
             $amount[$cust_id] = round(DB::table('order_system')->where('user_id',$cust_id)->where('payment_status','TXN_SUCCESS')->sum('total_amount'));
             
             $last_dt  = DB::table('order_system')->where('user_id',$cust_id)->where('payment_status','TXN_SUCCESS')->orderby('order_id','desc')->value('order_date');
             $dates=date_create($last_dt);
             $last_date[$cust_id] = date_format($dates,"d-M-Y");
             /*$last_date[$cust_id] = date('d-M-Y', strtotime($last_dt));*/
         }
        if(!$data['user_data']->isEmpty())
        {
            return view('masters.coupon.add_customer_coupon',compact('page_title','cust_ids','cust_name','amount','last_date'), $data);
        }
        else
        {
            return view('masters.coupon.add_customer_coupon',compact('page_title'), $data);
        }
        
        
        
         
    }
    
    public function customerCouponStore(REQUEST $request)
    {
         $input = $request->all();
         $coupone = $request->coupon_code;
         $coupon_valid_days = $request->coupon_valid_days;
         $input['id']  = json_encode($request->id);
         foreach($request->id as $user)
         {
             $email = DB::table('users')->where('id', $user)->value('email');
             $name = DB::table('users')->where('id', $user)->value('name');
             
             $messageBody = "Hi " . $name . " , <br> You are eligible for following coupon code : <br> <br> <b> Coupon Code - </b>" . $coupone . "<br> <b> Validity - </b>" . $coupon_valid_days . "<br> <b> Eligibility </b> - All Purchase <br><br> Redeem the coupon at the earliest to avoid any expiry of validity . <br><br> Regards <br> The Zouple ";                 
             $subject = "Congratulation ! Your Coupon Inside";
             $data['msg']=$messageBody;
             $data['subject']=$subject;
             $data['email']=$email;
             Mail::send([],[],  function ($message)  use($data) 
             {
                $message->to($data['email'])->subject($data['subject'])
                    ->setBody($data['msg'], 'text/html'); 
             });
         }
         DB::table('customer_coupon')->insert($input);
         $request->session()->flash('alert-success','Cusotmer Coupon has been sucessfully added.');
         return Redirect::route('addCustomerCoupon');
    }
    
        public function customerCouponUpdatePage(REQUEST $request, $customer_coupon_id)
    {
        $page_title = "Edit Customer Coupon - Zouple";
        
         $data['customer_coupon_datas'] = DB::table('customer_coupon')->where('customer_coupon_id', $customer_coupon_id)->get();
            
            $data['user_data'] = DB::table('users')->where('user_role', 'FRONT')->orderby('id', 'asc')->get();
         foreach($data['user_data'] as $dt)
         {
             $cust_id = $dt->id;
             $cust_ids[$cust_id] = $dt->id;
             $cust_name[$cust_id] = $dt->name;
             $amount[$cust_id] = round(DB::table('order_system')->where('user_id',$cust_id)->where('payment_status','TXN_SUCCESS')->sum('total_amount'));
             
             $last_dt  = DB::table('order_system')->where('user_id',$cust_id)->where('payment_status','TXN_SUCCESS')->orderby('order_id','desc')->value('order_date');
             $last_date[$cust_id] = date('d-M-Y', strtotime($last_dt));
         }
        if(!$data['user_data']->isEmpty())
        {
            return view('masters.coupon.edit_customer_coupon',compact('page_title','cust_ids','cust_name','amount','last_date'), $data);
        }
        else
        {
            return view('masters.coupon.edit_customer_coupon',compact('page_title'), $data);
        }
        
         
         
    }
    
    public function customerCouponEditStore(REQUEST $request)
    {
         $input = $request->all();
         $coupone = $request->coupon_code;
         $customer_coupon_id = $request->customer_coupon_id; 
         $coupon_valid_days = $request->coupon_valid_days;
         $input['id'] = json_encode($request->id);
         foreach($request->id as $user)
         {
             $email = DB::table('users')->where('id', $user)->value('email');
             $name = DB::table('users')->where('id', $user)->value('name');
             
             $messageBody = "Hi " . $name . " , <br> You are eligible for following coupon code : <br> <br> <b> Coupon Code - </b>" . $coupone . "<br> <b> Validity - </b>" . $coupon_valid_days . "<br> <b> Eligibility </b> - All Purchase <br><br> Redeem the coupon at the earliest to avoid any expiry of validity . <br><br> Regards <br> The Zouple ";                 
             $subject = "Congratulation ! Your Coupon Inside";
             $data['msg']=$messageBody;
             $data['subject']=$subject;
             $data['email']=$email;
             Mail::send([],[],  function ($message)  use($data) 
             {
                $message->to($data['email'])->subject($data['subject'])
                    ->setBody($data['msg'], 'text/html'); 
             });
         }
         DB::table('customer_coupon')->where('customer_coupon_id', $customer_coupon_id)->update($input);
         $request->session()->flash('alert-success','Customer Coupon has been sucessfully updated.');
         return Redirect::route('customerCoupon');
    }
    
    public function customerCouponDeleteFormat(Request $request,$customer_coupon_id)
  {
      
      $m = DB::table('customer_coupon')->where('customer_coupon_id', $customer_coupon_id)->delete();
      $request->session()->flash('alert-success','Customer Coupon has been sucessfully deleted.');
      return Redirect::route('customerCoupon');
  }
    
    public function customerCouponStatusUpdateSave(Request $request)
    {
        $input = $request->all();
        $customer_coupon_id = $request->customer_coupon_id; 
        DB::table('customer_coupon')->where('customer_coupon_id', $customer_coupon_id)->update($input);
        $request->session()->flash('alert-success','Customer Coupon Status has been sucessfully updated.');
        return Redirect::route('customerCoupon');
    }
    
    /* Customer Coupon Code End */
    
    
    /* Price Coupon Code Start */
    
    public function pricesCouponList(REQUEST $request)
    {
        $data['price_coupon_data'] = DB::table('price_coupon')->orderby('price_coupon_id', 'asc')->get();
        $page_title = "Price Coupon - Zouple";
        return view('masters.coupon.price_percent_list',compact('page_title'), $data);
    }
    
    public function priceCouponStatusAllUpdateStore(Request $request)
    {
        
        $input = $request->all();
        $is_active = $request->is_active; 
        DB::table('price_coupon')->update($input);
        $request->session()->flash('alert-success','Price Coupon Status has been sucessfully updated.');
        return Redirect::route('pricesCoupon');
    }
    
    public function addpricesCouponPage(REQUEST $request)
    {
         $page_title = "Add Price Coupon - Zouple";
         return view('masters.coupon.add_price_percent_list',compact('page_title'));
    }
    
    public function pricesCouponStore(REQUEST $request)
    {
         $input = $request->all();
         DB::table('price_coupon')->insert($input);
         $request->session()->flash('alert-success','Product Coupon has been sucessfully added.');
         return Redirect::route('addpricesCoupon');
    }
    
    public function pricesCouponUpdatePage(REQUEST $request, $price_coupon_id)
    {
         $data['product_coupon_datas'] = DB::table('price_coupon')->where('price_coupon_id', $price_coupon_id)->get();
         $page_title = "Edit Price Coupon - Zouple";
         return view('masters.coupon.edit_price_percent_list',compact('page_title'), $data);
    }
    
    public function pricesCouponEditStore(REQUEST $request)
    {
         $input = $request->all();
       
         $price_coupon_id = $request->price_coupon_id; 
         DB::table('price_coupon')->where('price_coupon_id', $price_coupon_id)->update($input);
         $request->session()->flash('alert-success','Price Coupon has been sucessfully updated.');
         return Redirect::route('pricesCoupon');
    }
    
    public function pricesCouponDeleteFormat(Request $request,$price_coupon_id)
  {
      
      $m = DB::table('price_coupon')->where('price_coupon_id', $price_coupon_id)->delete();
      $request->session()->flash('alert-success','Price Coupon has been sucessfully deleted.');
      return Redirect::route('pricesCoupon');
  }
    
    public function pricesCouponStatusUpdateSave(Request $request)
    {
        $input = $request->all();
        $price_coupon_id = $request->price_coupon_id; 
        DB::table('price_coupon')->where('price_coupon_id', $price_coupon_id)->update($input);
        $request->session()->flash('alert-success','Price Coupon Status has been sucessfully updated.');
        return Redirect::route('pricesCoupon');
    }
    
    
    /* Price Coupon Code End */
}
