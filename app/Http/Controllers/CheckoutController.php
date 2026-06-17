<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth,Redirect,View,File,Config,Image;
use Validator;
use DB;
use Response;
use Session;
use App\Helper\CurrencyHelper;
use App\Product;
use App\User;
use App\Order;
use App\Category;
use Mail;


use Illuminate\Support\Facades\Input;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;

/** All Paypal Details class **/
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

use URL;




class CheckoutController extends Controller
{
    private $_api_context;
    public function __construct()
    {
        /** PayPal api context **/
        $paypal_conf = \Config::get('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential(
            $paypal_conf['client_id'],
            $paypal_conf['secret'])
        );
        $this->_api_context->setConfig($paypal_conf['settings']);
        
        
        
        
    }
    public function checkout_details(Request $request)
    {  
        
         /*return $request;*/
         $user_id = Auth::user()->id;
         $ip = $request->ip();
         /*$totalFinalAmount = 0;
         $total_discount = 0;
         $discountper = 0;
        
         $totalFinalAmount = $request->total_final_amount;
         $total_discount = $request->discountAmount;
         $discountper = $request->discountCouponFetchData;*/
         $page_title = "Cart Checkout - Zouple";

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
        
         $data['cart_data'] = DB::table('carts')
             ->join('product_quantity','product_quantity.product_quantity_id','carts.product_qty_id')
             ->join('products', 'products.product_id', '=', 'carts.product_id')
             ->where('carts.ip_address',$ip)
             ->orWhere ('carts.user_id', Auth::user()->id)
             ->get();
        
         $shipping = DB::table('carts')
                     ->join('products', 'products.product_id', '=', 'carts.product_id')
                     ->where('carts.ip_address',$ip)
                    ->max('products.product_shipping');


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

             $sy='Rupees';
             
         }
         elseif(CurrencyHelper::isDollarCurrency($currencySession))
         {
             $rupeeShipCh = DOLLARSHIPPINCHARGE;
             $rs_dis = "doller_discount";
             $amt= "doller_min";
             $sy='Doller';
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
             $sy='Rupees';
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
            $pros = DB::table('products')->whereIn('product_id', $proIds)->get();
            foreach($pros as $datas)
            {
                $pro_id = $datas->product_id;
                $data['proAttributes'][$datas->product_id] = DB::table('product_attributes')->where('product_id',$pro_id)->get();
            }
        }

        $total_final_amount = $total_net_amount + $total_pro_gst;
        
         
        $total_final_amount = $total_net_amount + $total_pro_gst;

        if($currencySession == "rupee_price")
        {
            $priceCouponId = DB::table('price_coupon')->where('is_active', 'ACTIVE')->where('rupee_min', '<', $total_final_amount)->where('rupee_max', '>', $total_final_amount)->value('price_coupon_id');

            $priceCouponIds =  DB::table('price_coupon')->where('is_active', 'ACTIVE')->where('rupee_min', '>', $total_final_amount)->orderBy('rupee_max', 'ASC')->limit(1)->value('price_coupon_id');
            
            $data['discountCouponFetchData'] = DB::table('price_coupon')->where('is_active', 'ACTIVE')->where('rupee_min', '<', $total_final_amount)->where('rupee_max', '>', $total_final_amount)->limit(1)->value('rupee_discount');

            $data['discountCouponFetchDatass'] = DB::table('price_coupon')->where('is_active', 'ACTIVE')->where('price_coupon_id',  $priceCouponId)->value('rupee_discount');
            
            
        }
             elseif(CurrencyHelper::isDollarCurrency($currencySession))
        {

            $priceCouponId = DB::table('price_coupon')->where('is_active', 'ACTIVE')->where('doller_min', '<', $total_final_amount)->where('doller_max', '>', $total_final_amount)->value('price_coupon_id');

            $priceCouponIds =  DB::table('price_coupon')->where('is_active', 'ACTIVE')->where('doller_min', '>', $total_final_amount)->orderBy('doller_max', 'ASC')->limit(1)->value('price_coupon_id');
            
            $data['discountCouponFetchData'] = DB::table('price_coupon')->where('is_active', 'ACTIVE')->where('doller_min', '<', $total_final_amount)->where('doller_max', '>', $total_final_amount)->limit(1)->value('doller_discount');

            $data['discountCouponFetchDatass'] = DB::table('price_coupon')->where('is_active', 'ACTIVE')->where('price_coupon_id',  $priceCouponId)->value('doller_discount');
        } 
        elseif($currencySession == "euro_price")
        {

            $priceCouponId = DB::table('price_coupon')->where('is_active', 'ACTIVE')->where('euro_min', '<', $total_final_amount)->where('euro_max', '>', $total_final_amount)->value('price_coupon_id');

            $priceCouponIds =  DB::table('price_coupon')->where('is_active', 'ACTIVE')->where('euro_min', '>', $total_final_amount)->orderBy('euro_max', 'ASC')->limit(1)->value('price_coupon_id');
            
            $data['discountCouponFetchData'] = DB::table('price_coupon')->where('is_active', 'ACTIVE')->where('euro_min', '<', $total_final_amount)->where('euro_max', '>', $total_final_amount)->limit(1)->value('euro_discount');

            $data['discountCouponFetchDatass'] = DB::table('price_coupon')->where('is_active', 'ACTIVE')->where('price_coupon_id',  $priceCouponId)->value('euro_discount');
        }
        else
        {
            $priceCouponId = DB::table('price_coupon')->where('is_active', 'ACTIVE')->where('doller_min', '<', $total_final_amount)->where('doller_max', '>', $total_final_amount)->value('price_coupon_id');

            $priceCouponIds =  DB::table('price_coupon')->where('is_active', 'ACTIVE')->where('doller_min', '>', $total_final_amount)->orderBy('doller_max', 'ASC')->limit(1)->value('price_coupon_id');
            
            $data['discountCouponFetchData'] = DB::table('price_coupon')->where('is_active', 'ACTIVE')->where('doller_min', '<', $total_final_amount)->where('doller_max', '>', $total_final_amount)->limit(1)->value('doller_discount');

            $data['discountCouponFetchDatass'] = DB::table('price_coupon')->where('is_active', 'ACTIVE')->where('price_coupon_id',  $priceCouponId)->value('doller_discount');
        }

        // Make Payment for Coupon


        /*$check_code = 0;

        $check_code = $priceCouponIds;

        $CouponAmount = DB::table('price_coupon')->where('price_coupon_id',$check_code)->value('rupee_discount');
        
        if($CouponAmount  == $total_final_amount)
        {
            $status = "SHOW";
        }
        else
        {
            echo $status="HIDE";
        }
        echo $total_final_amount;*/
        
        ////////////////////////////
        



        if($slug == "")
        {
            $slug = DB::table('categorys')->where('category_id',1)->value('slug');    
        }
        
        
        
        
       return view('front.payment.checkout',compact('page_title','shipping'), $data);
       
    }
    
    
    public function shirtCheckoutStore(Request $request)
    {
        
        /* Currency Type */
        $currency_type = Session::get('currency');
        if($currency_type == "rupee_price")
        {
            $currency = "INR";
        }
        elseif($currency_type == "dollar_price")
        {
            $currency = "USD";
        }
        elseif($currency_type == "euro_price")
        {
            $currency = "EUR";
        }
        else
        {
            $currency = "USD";
        }
      
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $order = mt_rand(10, 99)
        . mt_rand(10, 99)
        . $characters[rand(0, strlen($characters) - 1)]
        . $characters[rand(3, strlen($characters) - 1)];

        $mm = date('m');
        $yy = date('y');
        $order_number = str_shuffle($order).$mm.$yy;

        $inser_data['user_id'] = Auth::user()->id;
        $inser_data['order_number'] = $order_number;
        
         
        if(isset($request->checksame))
        {
            if($request->checksame = "on")
            {
                $bills_num = DB::table('user_information')->where('user_information_id',$request->shippingAddress)->get();
                foreach($bills_num as $object)
                {
                    $arrays[] =  (array) $object;
                }
                DB::table('order_address')->insert($arrays);
                
                $inser_data['shipping_address_id'] = DB::getPdo()->lastInsertId();
                $inser_data['billing_address_id'] = DB::getPdo()->lastInsertId();
            }
            else
            {
                
                 $bills_num = DB::table('user_information')->where('user_information_id',$request->billingAddress)->get();
                 $ships_num = DB::table('user_information')->where('user_information_id',$request->shippingAddress)->get();
                foreach($bills_num as $object)
                {
                    $billsarrays[] =  (array) $object;
                }
                DB::table('order_address')->insert($billsarrays);
                $inser_data['billing_address_id'] = DB::getPdo()->lastInsertId();
                
                foreach($ships_num as $object)
                {
                    $shipsarrays[] =  (array) $object;
                }
                DB::table('order_address')->insert($shipsarrays);
                $inser_data['shipping_address_id'] = DB::getPdo()->lastInsertId();
                
            }
        }
        else
        {
            $bills_num = DB::table('user_information')->where('user_information_id',$request->billingAddress)->get();
             $ships_num = DB::table('user_information')->where('user_information_id',$request->shippingAddress)->get();
            foreach($bills_num as $object)
            {
                $billsarrays[] =  (array) $object;
            }
            DB::table('order_address')->insert($billsarrays);
            $inser_data['billing_address_id'] = DB::getPdo()->lastInsertId();
            
            foreach($ships_num as $object)
            {
                $shipsarrays[] =  (array) $object;
            }
            DB::table('order_address')->insert($shipsarrays);
            $inser_data['shipping_address_id'] = DB::getPdo()->lastInsertId();
        }
       
        $inser_data['total_amount'] = $request->price;
        $amt = $request->price;
        $inser_data['_token'] = $request->_token;
      
        
        //Address Default Update
            $billing = $request->billingAddress;
            $shipping = $request->shippingAddress;
            $inpu['default_address'] = "YES";
            DB::table('user_information')->where('user_information_id',$billing)->update($inpu);
            DB::table('user_information')->where('user_information_id',$shipping)->update($inpu);
        //
       
        $inser_data['order_date'] = date('Y-m-d H:i:s');
        $inser_data['order_status'] = "Pending";
        $inser_data['query_Text'] = $request->query_Text;
        $inser_data['amount_type'] = $currency_type;
        
       
        //shirt Details
       
        $products['Febric']  = Session::get('febric');
        $elementid = DB::table('element')->get(); 
        foreach($elementid as $element)
        {
            $products[$element->name]  = Session::get($element->name);
        }
        
        $inser_data['product_details'] = json_encode($products);
        $inser_data['order_type'] = "DESIGN-SHIRT";
        
        
          
        DB::table('order_system')->insert($inser_data);
    
        $price = $request->price;
        $order_number = $order_number;
        $user_id = Auth::user()->id;

        if($currency_type == "rupee_price")
        {
            $amount = (int)number_format($price);
            $amount = number_format($amount,2);
            $amount = (float) str_replace(',', '', $amount);
            $data_for_request = $this->handlePaytmRequest($order_number,$price,$user_id);
            $paytm_txn_url = 'https://securegw.paytm.in/order/process';         
            $paramList = $data_for_request['paramList'];
            $checkSum = $data_for_request['checkSum'];
            
            return view('front.payment.paytm-merchant-form', compact('paytm_txn_url', 'paramList', 'checkSum' ));
        }
        
        else
        {
            
             $amount = number_format($price);
            $amount_1 = (float) str_replace(',', '', $amount);
            
            $payer = new Payer();
            $payer->setPaymentMethod('paypal');
            
            $item_1 = new Item();
            $item_1->setName($order_number) /** item name **/
                ->setCurrency($currency)
                ->setQuantity(1)
                ->setPrice($amount_1); /** unit price **/
           
            $item_list = new ItemList();
            $item_list->setItems(array($item_1));
            
            $amount = new Amount();
            $amount->setCurrency($currency)
                ->setTotal($amount_1);
            
            $transaction = new Transaction();
            $transaction->setAmount($amount)
                ->setItemList($item_list)
                ->setDescription('Your transaction description');
            
            $redirect_urls = new RedirectUrls();
            $redirect_urls->setReturnUrl(URL::to('paypalStatus')) /** Specify return URL **/
                ->setCancelUrl(URL::to('paypalStatus'));

            $payment = new Payment();
            $payment->setIntent('Sale')
                ->setPayer($payer)
                ->setRedirectUrls($redirect_urls)
                ->setTransactions(array($transaction));
            try {
                $payment->create($this->_api_context);
            } catch (\PayPal\Exception\PPConnectionException $ex) {
                die($ex);
                if (\Config::get('app.debug')) {
                    $request->session()->flash('alert-danger','Sorry, your order is not place because there have connection time out issue.');
                    return Redirect::to('/');
                } else {
                      $request->session()->flash('alert-danger','Sorry, your order is not place because there have some technical issue.');
                    return Redirect::to('/');
                }
            }
            foreach ($payment->getLinks() as $link) {
                if ($link->getRel() == 'approval_url') {
                    $redirect_url = $link->getHref();
                    break;
                }
            }
            /** add payment ID to session **/
            Session::put('paypal_payment_id', $payment->getId());
            $trans_update['transaction_id'] = $payment->getId();
            $trans_update['payment_getway'] = "Paypal";
            DB::table('order_system')->where('order_number',$order_number)->update($trans_update);
            if (isset($redirect_url)) {
                return Redirect::away($redirect_url);
            }
            $request->session()->flash('alert-danger','Sorry, your order is not place because there have some technical issue.');
            return Redirect::to('/');            
        }

        
       
        
        
    }
    

    public function goToPaymentSystem(Request $request)
    {
        
        /* Currency Type */
        $currency_type = Session::get('currency');
        if($currency_type == "rupee_price")
        {
            $currency = "INR";
            $net_with_gst = "rupee_net_with_gst";
        }
        elseif($currency_type == "dollar_price")
        {
            $currency = "USD";
            $net_with_gst = "dollar_net_with_gst";
        }
        elseif($currency_type == "euro_price")
        {
            $currency = "EUR";
            $net_with_gst = "euro_net_with_gst";
        }
        else
        {
            $currency = "USD";
            $net_with_gst = "dollar_net_with_gst";
        }
       /*return $request; */
        $user_id = Auth::user()->id;
         
         //Address Default Update
            $billing = $request->billingAddress;
            $shipping = $request->shippingAddress;
            $inpu['default_address'] = "YES";
            DB::table('user_information')->where('user_information_id',$billing)->update($inpu);
            DB::table('user_information')->where('user_information_id',$shipping)->update($inpu);
        //
        if(isset($request->checksame))
        {
            if($request->checksame = "on")
            {
                $bills_num = DB::table('user_information')->where('user_information_id',$request->shippingAddress)->get();
                foreach($bills_num as $object)
                {
                    $arrays[] =  (array) $object;
                }
                DB::table('order_address')->insert($arrays);
                
                $input['shipping_address_id'] = DB::getPdo()->lastInsertId();
                $input['billing_address_id'] = DB::getPdo()->lastInsertId();
            }
            else
            {
                
                 $bills_num = DB::table('user_information')->where('user_information_id',$request->billingAddress)->get();
                 $ships_num = DB::table('user_information')->where('user_information_id',$request->shippingAddress)->get();
                foreach($bills_num as $object)
                {
                    $billsarrays[] =  (array) $object;
                }
                DB::table('order_address')->insert($billsarrays);
                $input['billing_address_id'] = DB::getPdo()->lastInsertId();
                
                foreach($ships_num as $object)
                {
                    $shipsarrays[] =  (array) $object;
                }
                DB::table('order_address')->insert($shipsarrays);
                $input['shipping_address_id'] = DB::getPdo()->lastInsertId();
                
            }
        }
        else
        {
            $bills_num = DB::table('user_information')->where('user_information_id',$request->billingAddress)->get();
             $ships_num = DB::table('user_information')->where('user_information_id',$request->shippingAddress)->get();
            foreach($bills_num as $object)
            {
                $billsarrays[] =  (array) $object;
            }
            DB::table('order_address')->insert($billsarrays);
            $input['billing_address_id'] = DB::getPdo()->lastInsertId();
            
            foreach($ships_num as $object)
            {
                $shipsarrays[] =  (array) $object;
            }
            DB::table('order_address')->insert($shipsarrays);
            $input['shipping_address_id'] = DB::getPdo()->lastInsertId();
        }
         $ip = $request->ip();
        $cart_data = DB::table('carts')
             ->join('product_quantity','product_quantity.product_quantity_id','carts.product_qty_id')
             ->join('products', 'products.product_id', '=', 'carts.product_id')
             ->where('carts.ip_address',$ip)
             ->orWhere ('carts.user_id', Auth::user()->id)
             ->get();
        
        foreach($cart_data as $data)
        {
            $product_id = $data->product_id;
            $attrvalues = json_decode($data->attributes_value);
            $attributes_value = implode(',',$attrvalues);
            $product_qty =  $data->product_qty;
            $product_amt =  round($data->$net_with_gst);
            
            /*$pro_val = round(DB::table('product_quantity')->where('product_quantity_id',$request->product_qty_id)->value($currency_type));*/
            
            $pro_details[$product_id] = $attributes_value."-".$product_qty."-".$product_amt;
            
        }
        $product_details = json_encode($pro_details);
        $input['product_details'] = $product_details;
        $input['total_amount'] = $request->total_amount;
        $input['net_amount'] = $request->net_amount;
        $input['product_gst'] = $request->product_gst;
        $input['discount'] = $request->total_discount;
        $input['coupon_discount'] = $request->discount;
        $input['coupon_type'] = $request->coupon_type;
        $input['coupon_code'] = $request->coupon_code;
        $input['coupenApply'] = $request->coupenApply;
        $input['shipping'] = $request->shiping_charge;
        $input['user_id'] = $user_id;
        $input['_token'] = $request->_token;
        $input['order_status'] = "Pending";
        $input['order_date'] = date('Y-M-d:H:M:S');
        $input['order_type'] = "CHECKOUT";
        $input['amount_type'] = $currency_type;
        
        
        $cou_use['user_id'] = $user_id;
        $cou_use['coupon_id'] = $request->coupon_id;
        $cou_use['coupon_type'] = $request->coupon_type;
         /*return $input;*/
        DB::table('coupon_uses') ->insert($cou_use);
         
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $order = mt_rand(10, 99)
        . mt_rand(10, 99)
        . $characters[rand(0, strlen($characters) - 1)]
        . $characters[rand(2, strlen($characters) - 1)];

        $mm = date('m');
        $yy = date('y');
        $order_number = str_shuffle($order).$mm.$yy;
        $input['order_date'] = date('Y-m-d H:i:s');
        $input['order_number'] = $order_number;
        
        DB::table('order_system')->insert($input);
        $user_id = Auth::user()->id;
        $amount = round($request->total_amount);
       
        if($currency_type == "rupee_price")
        {
            
            
            $amount = number_format($amount,2);
            $amount = (float) str_replace(',', '', $amount);
            
            $data_for_request = $this->handlePaytmRequest($order_number,$amount,$user_id);
            $paytm_txn_url = 'https://securegw.paytm.in/order/process';
            $paramList = $data_for_request['paramList'];
            $checkSum = $data_for_request['checkSum'];

            return view('front.payment.paytm-merchant-form', compact('paytm_txn_url', 'paramList', 'checkSum' ));
        }
        
        else
        {
            
            $amount = number_format($amount);
            $amount_1 = (float) str_replace(',', '', $amount);
            
            $payer = new Payer();
            $payer->setPaymentMethod('paypal');
            
            $item_1 = new Item();
            $item_1->setName($order_number) /** item name **/
                ->setCurrency($currency)
                ->setQuantity(1)
                ->setPrice($amount_1); /** unit price **/
           
            $item_list = new ItemList();
            $item_list->setItems(array($item_1));
            
            $amount = new Amount();
            $amount->setCurrency($currency)
                ->setTotal($amount_1);
            
            $transaction = new Transaction();
            $transaction->setAmount($amount)
                ->setItemList($item_list)
                ->setDescription('Your transaction description');
            
            $redirect_urls = new RedirectUrls();
            $redirect_urls->setReturnUrl(URL::to('paypalStatus')) /** Specify return URL **/
                ->setCancelUrl(URL::to('paypalStatus'));

            $payment = new Payment();
            $payment->setIntent('Sale')
                ->setPayer($payer)
                ->setRedirectUrls($redirect_urls)
                ->setTransactions(array($transaction));
            try {
                $payment->create($this->_api_context);
            } catch (\PayPal\Exception\PPConnectionException $ex) {
                die($ex);
                if (\Config::get('app.debug')) {
                    $request->session()->flash('alert-danger','Sorry, your order is not place because there have connection time out issue.');
                    return Redirect::to('/');
                } else {
                      $request->session()->flash('alert-danger','Sorry, your order is not place because there have some technical issue.');
                    return Redirect::to('/');
                }
            }
            foreach ($payment->getLinks() as $link) {
                if ($link->getRel() == 'approval_url') {
                    $redirect_url = $link->getHref();
                    break;
                }
            }
            /** add payment ID to session **/
            Session::put('paypal_payment_id', $payment->getId());
            $trans_update['transaction_id'] = $payment->getId();
            $trans_update['payment_getway'] = "Paypal";
            DB::table('order_system')->where('order_number',$order_number)->update($trans_update);
            if (isset($redirect_url)) {
                return Redirect::away($redirect_url);
            }
            $request->session()->flash('alert-danger','Sorry, your order is not place because there have some technical issue.');
            return Redirect::to('/');            
        }  
    }
    
    
    
    
    public function buyNowSystem(Request $request)
    {
        /* Currency Type */
        $currency_type = Session::get('currency');
        if($currency_type == "rupee_price")
        {
            $currency = "INR";
        }
        elseif($currency_type == "dollar_price")
        {
            $currency = "USD";
        }
        elseif($currency_type == "euro_price")
        {
            $currency = "EUR";
        }
        else
        {
            $currency = "USD";
        }
        $user_id = Auth::user()->id;
        //Address Default Update
            $billing = $request->billingAddress;
            $shipping = $request->shippingAddress;
            $inpu['default_address'] = "YES";
            DB::table('user_information')->where('user_information_id',$billing)->update($inpu);
            DB::table('user_information')->where('user_information_id',$shipping)->update($inpu);
        //
        $pro_dst = json_decode(DB::table('product_quantity')->where('product_quantity_id',$request->product_qty_id)->value('attributes_value'));
        $pro_det = implode(',',$pro_dst);
        $pro_val = round(DB::table('product_quantity')->where('product_quantity_id',$request->product_qty_id)->value($currency_type));
        
        $product_details[$request->product_id] = $pro_det."-".$request->pro_qty."-".$pro_val;
        if(isset($request->checksame))
        {
            if($request->checksame = "on")
            {
                $bills_num = DB::table('user_information')->where('user_information_id',$request->shippingAddress)->get();
                foreach($bills_num as $object)
                {
                    $arrays[] =  (array) $object;
                }
                DB::table('order_address')->insert($arrays);
                
                $input['shipping_address_id'] = DB::getPdo()->lastInsertId();
                $input['billing_address_id'] = DB::getPdo()->lastInsertId();
            }
            else
            {
                
                 $bills_num = DB::table('user_information')->where('user_information_id',$request->billingAddress)->get();
                 $ships_num = DB::table('user_information')->where('user_information_id',$request->shippingAddress)->get();
                foreach($bills_num as $object)
                {
                    $billsarrays[] =  (array) $object;
                }
                DB::table('order_address')->insert($billsarrays);
                $input['billing_address_id'] = DB::getPdo()->lastInsertId();
                
                foreach($ships_num as $object)
                {
                    $shipsarrays[] =  (array) $object;
                }
                DB::table('order_address')->insert($shipsarrays);
                $input['shipping_address_id'] = DB::getPdo()->lastInsertId();
                
            }
        }
        else
        {
            $bills_num = DB::table('user_information')->where('user_information_id',$request->billingAddress)->get();
             $ships_num = DB::table('user_information')->where('user_information_id',$request->shippingAddress)->get();
            foreach($bills_num as $object)
            {
                $billsarrays[] =  (array) $object;
            }
            DB::table('order_address')->insert($billsarrays);
            $input['billing_address_id'] = DB::getPdo()->lastInsertId();
            
            foreach($ships_num as $object)
            {
                $shipsarrays[] =  (array) $object;
            }
            DB::table('order_address')->insert($shipsarrays);
            $input['shipping_address_id'] = DB::getPdo()->lastInsertId();
        }
        $input['product_details'] = json_encode($product_details);
        $input['total_amount'] = $request->total_amount;
        $input['net_amount'] = $request->net_amount;
        $input['product_gst'] = $request->product_gst;
        $input['discount'] = $request->total_discount;
        $input['coupon_discount'] = $request->discount;
        $input['coupon_type'] = $request->coupon_type;
        $input['coupon_code'] = $request->coupon_code;
        $input['coupenApply'] = $request->coupenApply;
        $input['shipping'] = $request->shiping_charge;
        $input['user_id'] = $user_id;
        $input['_token'] = $request->_token;
        $input['order_status'] = "Pending";
        $input['order_date'] = date('Y-M-d:H:M:S');
        $input['order_type'] = "BUYNOW";
        $input['amount_type'] = $currency_type;

        $cou_use['user_id'] = $user_id;
        $cou_use['coupon_id'] = $request->coupon_id;
        $cou_use['coupon_type'] = $request->coupon_type;
         
        DB::table('coupon_uses') ->insert($cou_use);
         
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $order = mt_rand(10, 99)
        . mt_rand(10, 99)
        . $characters[rand(0, strlen($characters) - 1)]
        . $characters[rand(2, strlen($characters) - 1)];

        $mm = date('m');
        $yy = date('y');
        $order_number = str_shuffle($order).$mm.$yy;
        
        $input['order_number'] = $order_number;
        
        DB::table('order_system')->insert($input);
        $user_id = Auth::user()->id;
        $amount = round($request->total_amount);
    
        
        if($currency_type == "rupee_price")
        {  
          $amount = number_format($amount);
            $amount = (float) str_replace(',', '', $amount);
            $data_for_request = $this->handlePaytmRequest($order_number,$amount,$user_id);
            $paytm_txn_url = 'https://securegw.paytm.in/order/process';
            $paramList = $data_for_request['paramList'];
            $checkSum = $data_for_request['checkSum'];

            return view('front.payment.paytm-merchant-form', compact('paytm_txn_url', 'paramList', 'checkSum' ));
        }
        else
        {
            
            $amount = number_format($amount);
            $amount_1 = (float) str_replace(',', '', $amount);
            
            $payer = new Payer();
            $payer->setPaymentMethod('paypal');
            
            $item_1 = new Item();
            $item_1->setName($order_number) /** item name **/
                ->setCurrency($currency)
                ->setQuantity(1)
                ->setPrice($amount_1); /** unit price **/
           
            $item_list = new ItemList();
            $item_list->setItems(array($item_1));
            
            $amount = new Amount();
            $amount->setCurrency($currency)
                ->setTotal($amount_1);
            
            $transaction = new Transaction();
            $transaction->setAmount($amount)
                ->setItemList($item_list)
                ->setDescription('Your transaction description');
            
            $redirect_urls = new RedirectUrls();
            $redirect_urls->setReturnUrl(URL::to('paypalStatus')) /** Specify return URL **/
                ->setCancelUrl(URL::to('paypalStatus'));

            $payment = new Payment();
            $payment->setIntent('Sale')
                ->setPayer($payer)
                ->setRedirectUrls($redirect_urls)
                ->setTransactions(array($transaction));
            try {
                $payment->create($this->_api_context);
            } catch (\PayPal\Exception\PPConnectionException $ex) {
                die($ex);
                if (\Config::get('app.debug')) {
                    $request->session()->flash('alert-danger','Sorry, your order is not place because there have connection time out issue.');
                    return Redirect::to('/');
                } else {
                      $request->session()->flash('alert-danger','Sorry, your order is not place because there have some technical issue.');
                    return Redirect::to('/');
                }
            }
            foreach ($payment->getLinks() as $link) {
                if ($link->getRel() == 'approval_url') {
                    $redirect_url = $link->getHref();
                    break;
                }
            }
            /** add payment ID to session **/
            Session::put('paypal_payment_id', $payment->getId());
            $trans_update['transaction_id'] = $payment->getId();
            $trans_update['payment_getway'] = "Paypal";
            DB::table('order_system')->where('order_number',$order_number)->update($trans_update);
            if (isset($redirect_url)) {
                return Redirect::away($redirect_url);
            }
            $request->session()->flash('alert-danger','Sorry, your order is not place because there have some technical issue.');
            return Redirect::to('/');            
        }           
    }
    
    
    public function flashBuyNowSystem(Request $request)
    {
        
        /* Currency Type */
        $currency_type = Session::get('currency');
        if($currency_type == "rupee_price")
        {
            $currency = "INR";
        }
        elseif($currency_type == "dollar_price")
        {
            $currency = "USD";
        }
        elseif($currency_type == "euro_price")
        {
            $currency = "EUR";
        }
        else
        {
            $currency = "USD";
        }
        
        
        $user_id = Auth::user()->id;
        //Address Default Update
            $billing = $request->billingAddress;
            $shipping = $request->shippingAddress;
            $inpu['default_address'] = "YES";
            DB::table('user_information')->where('user_information_id',$billing)->update($inpu);
            DB::table('user_information')->where('user_information_id',$shipping)->update($inpu);
        //
        $pro_dst = json_decode(DB::table('product_quantity')->where('product_quantity_id',$request->product_qty_id)->value('attributes_value'));
        $pro_det = implode(',',$pro_dst);
        
        $pro_val = round(DB::table('product_quantity')->where('product_quantity_id',$request->product_qty_id)->value($currency_type));
        
        
        $product_details[$request->product_id] = $pro_det."-".$request->pro_qty."-".$pro_val;
        
        if(isset($request->checksame))
        {
            if($request->checksame = "on")
            {
                $bills_num = DB::table('user_information')->where('user_information_id',$request->shippingAddress)->get();
                foreach($bills_num as $object)
                {
                    $arrays[] =  (array) $object;
                }
                DB::table('order_address')->insert($arrays);
                
                $input['shipping_address_id'] = DB::getPdo()->lastInsertId();
                $input['billing_address_id'] = DB::getPdo()->lastInsertId();
            }
            else
            {
                
                 $bills_num = DB::table('user_information')->where('user_information_id',$request->billingAddress)->get();
                 $ships_num = DB::table('user_information')->where('user_information_id',$request->shippingAddress)->get();
                foreach($bills_num as $object)
                {
                    $billsarrays[] =  (array) $object;
                }
                DB::table('order_address')->insert($billsarrays);
                $input['billing_address_id'] = DB::getPdo()->lastInsertId();
                
                foreach($ships_num as $object)
                {
                    $shipsarrays[] =  (array) $object;
                }
                DB::table('order_address')->insert($shipsarrays);
                $input['shipping_address_id'] = DB::getPdo()->lastInsertId();
                
            }
        }
        else
        {
            $bills_num = DB::table('user_information')->where('user_information_id',$request->billingAddress)->get();
             $ships_num = DB::table('user_information')->where('user_information_id',$request->shippingAddress)->get();
            foreach($bills_num as $object)
            {
                $billsarrays[] =  (array) $object;
            }
            DB::table('order_address')->insert($billsarrays);
            $input['billing_address_id'] = DB::getPdo()->lastInsertId();
            
            foreach($ships_num as $object)
            {
                $shipsarrays[] =  (array) $object;
            }
            DB::table('order_address')->insert($shipsarrays);
            $input['shipping_address_id'] = DB::getPdo()->lastInsertId();
        }
        $input['product_details'] = json_encode($product_details);
        $input['total_amount'] = $request->total_amount;
        $input['net_amount'] = $request->net_amount;
        $input['product_gst'] = $request->product_gst;
        $input['shipping'] = $request->shiping_charge;
        $input['user_id'] = $user_id;
        $input['_token'] = $request->_token;
        $input['order_status'] = "Pending";
        $input['order_date'] = date('Y-M-d:H:M:S');
        $input['order_type'] = "FLASH-NOW";
        $input['amount_type'] = $currency_type;

        $cou_use['user_id'] = $user_id;
        $cou_use['coupon_id'] = $request->coupon_id;
        $cou_use['coupon_type'] = $request->coupon_type;
         
        DB::table('coupon_uses') ->insert($cou_use);
         
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $order = mt_rand(10, 99)
        . mt_rand(10, 99)
        . $characters[rand(0, strlen($characters) - 1)]
        . $characters[rand(2, strlen($characters) - 1)];

        $mm = date('m');
        $yy = date('y');
        $order_number = str_shuffle($order).$mm.$yy;
        
        $input['order_number'] = $order_number;
        
        DB::table('order_system')->insert($input);
        $user_id = Auth::user()->id;
        $amount = round($request->total_amount);
        
        
        if($currency_type == "rupee_price")
        {
            $amount = (int)number_format($amount);
            $amount = number_format($amount,2);
            $amount = (float) str_replace(',', '', $amount);
            $data_for_request = $this->handlePaytmRequest($order_number,$amount,$user_id);
            $paytm_txn_url = 'https://securegw.paytm.in/order/process';
            $paramList = $data_for_request['paramList'];
            $checkSum = $data_for_request['checkSum'];
            return view('front.payment.paytm-merchant-form', compact('paytm_txn_url', 'paramList', 'checkSum' ));
        }
        
        else
        {
            
             $amount = number_format($amount);
            $amount_1 = (float) str_replace(',', '', $amount);
            
            $payer = new Payer();
            $payer->setPaymentMethod('paypal');
            
            $item_1 = new Item();
            $item_1->setName($order_number) /** item name **/
                ->setCurrency($currency)
                ->setQuantity(1)
                ->setPrice($amount_1); /** unit price **/
           
            $item_list = new ItemList();
            $item_list->setItems(array($item_1));
            
            $amount = new Amount();
            $amount->setCurrency($currency)
                ->setTotal($amount_1);
            
            $transaction = new Transaction();
            $transaction->setAmount($amount)
                ->setItemList($item_list)
                ->setDescription('Your transaction description');
            
            $redirect_urls = new RedirectUrls();
            $redirect_urls->setReturnUrl(URL::to('paypalStatus')) /** Specify return URL **/
                ->setCancelUrl(URL::to('paypalStatus'));

            $payment = new Payment();
            $payment->setIntent('Sale')
                ->setPayer($payer)
                ->setRedirectUrls($redirect_urls)
                ->setTransactions(array($transaction));
            try {
                $payment->create($this->_api_context);
            } catch (\PayPal\Exception\PPConnectionException $ex) {
                die($ex);
                if (\Config::get('app.debug')) {
                    $request->session()->flash('alert-danger','Sorry, your order is not place because there have connection time out issue.');
                    return Redirect::to('/');
                } else {
                      $request->session()->flash('alert-danger','Sorry, your order is not place because there have some technical issue.');
                    return Redirect::to('/');
                }
            }
            foreach ($payment->getLinks() as $link) {
                if ($link->getRel() == 'approval_url') {
                    $redirect_url = $link->getHref();
                    break;
                }
            }
            /** add payment ID to session **/
            Session::put('paypal_payment_id', $payment->getId());
            $trans_update['transaction_id'] = $payment->getId();
            $trans_update['payment_getway'] = "Paypal";
            DB::table('order_system')->where('order_number',$order_number)->update($trans_update);
            if (isset($redirect_url)) {
                return Redirect::away($redirect_url);
            }
            $request->session()->flash('alert-danger','Sorry, your order is not place because there have some technical issue.');
            return Redirect::to('/');            
        }  
        
        
    }

    
   
    
    

    public function handlePaytmRequest($order_id,$amount,$user_id)
    {
        $this->getAllEncdecFunc();
        $this->getConfigPaytmSetting();
        $checkSum="";
        $paramList = array();
        
        // $param List
        
        // Create an array having all required parameters for creating checksum.
		$paramList["MID"] = 'aRUKQB44056800848352';
		$paramList["ORDER_ID"] = $order_id;
		$paramList["CUST_ID"] = $user_id;
		$paramList["INDUSTRY_TYPE_ID"] = 'Retail';
		$paramList["CHANNEL_ID"] = 'WEB';
		$paramList["TXN_AMOUNT"] = $amount;
		$paramList["WEBSITE"] = 'DEFAULT';
		$paramList["CALLBACK_URL"] = url('/paytm-callback');
		$paytm_merchant_key = 'yAVZIO8X8a#h&Cfc';
        
        //Here checksum string will return by getChecksumFromArray() function.
		$checkSum = getChecksumFromArray($paramList, $paytm_merchant_key);

		return array(
			'checkSum' => $checkSum,
			'paramList' => $paramList
		);
    }
    
    
    
    public function getAllEncdecFunc()
    { 
        function encrypt_e($input, $ky) {
            $key   = html_entity_decode($ky);
            $iv = "@@@@&&&&####$$$$";
            $data = openssl_encrypt ( $input , "AES-128-CBC" , $key, 0, $iv );
            return $data;
        }

        function decrypt_e($crypt, $ky) {
            $key   = html_entity_decode($ky);
            $iv = "@@@@&&&&####$$$$";
            $data = openssl_decrypt ( $crypt , "AES-128-CBC" , $key, 0, $iv );
            return $data;
        }

        function generateSalt_e($length) {
            $random = "";
            srand((double) microtime() * 1000000);

            $data = "AbcDE123IJKLMN67QRSTUVWXYZ";
            $data .= "aBCdefghijklmn123opq45rs67tuv89wxyz";
            $data .= "0FGH45OP89";

            for ($i = 0; $i < $length; $i++) {
                $random .= substr($data, (rand() % (strlen($data))), 1);
            }

            return $random;
        }

        function checkString_e($value) {
            if ($value == 'null')
                $value = '';
            return $value;
        }

        function getChecksumFromArray($arrayList, $key, $sort=1) {
            if ($sort != 0) {
                ksort($arrayList);
            }
            $str = getArray2Str($arrayList);
            $salt = generateSalt_e(4);
            $finalString = $str . "|" . $salt;
            $hash = hash("sha256", $finalString);
            $hashString = $hash . $salt;
            $checksum = encrypt_e($hashString, $key);
            return $checksum;
        }
        function getChecksumFromString($str, $key) {

            $salt = generateSalt_e(4);
            $finalString = $str . "|" . $salt;
            $hash = hash("sha256", $finalString);
            $hashString = $hash . $salt;
            $checksum = encrypt_e($hashString, $key);
            return $checksum;
        }

        function verifychecksum_e($arrayList, $key, $checksumvalue) {
            $arrayList = removeCheckSumParam($arrayList);
            ksort($arrayList);
            $str = getArray2StrForVerify($arrayList);
            $paytm_hash = decrypt_e($checksumvalue, $key);
            $salt = substr($paytm_hash, -4);

            $finalString = $str . "|" . $salt;

            $website_hash = hash("sha256", $finalString);
            $website_hash .= $salt;

            $validFlag = "FALSE";
            if ($website_hash == $paytm_hash) {
                $validFlag = "TRUE";
            } else {
                $validFlag = "FALSE";
            }
            return $validFlag;
        }

        function verifychecksum_eFromStr($str, $key, $checksumvalue) {
            $paytm_hash = decrypt_e($checksumvalue, $key);
            $salt = substr($paytm_hash, -4);

            $finalString = $str . "|" . $salt;

            $website_hash = hash("sha256", $finalString);
            $website_hash .= $salt;

            $validFlag = "FALSE";
            if ($website_hash == $paytm_hash) {
                $validFlag = "TRUE";
            } else {
                $validFlag = "FALSE";
            }
            return $validFlag;
        }

        function getArray2Str($arrayList) {
            $findme   = 'REFUND';
            $findmepipe = '|';
            $paramStr = "";
            $flag = 1;	
            foreach ($arrayList as $key => $value) {
                $pos = strpos($value, $findme);
                $pospipe = strpos($value, $findmepipe);
                if ($pos !== false || $pospipe !== false) 
                {
                    continue;
                }

                if ($flag) {
                    $paramStr .= checkString_e($value);
                    $flag = 0;
                } else {
                    $paramStr .= "|" . checkString_e($value);
                }
            }
            return $paramStr;
        }

        function getArray2StrForVerify($arrayList) {
            $paramStr = "";
            $flag = 1;
            foreach ($arrayList as $key => $value) {
                if ($flag) {
                    $paramStr .= checkString_e($value);
                    $flag = 0;
                } else {
                    $paramStr .= "|" . checkString_e($value);
                }
            }
            return $paramStr;
        }

        function redirect2PG($paramList, $key) {
            $hashString = getchecksumFromArray($paramList);
            $checksum = encrypt_e($hashString, $key);
        }

        function removeCheckSumParam($arrayList) {
            if (isset($arrayList["CHECKSUMHASH"])) {
                unset($arrayList["CHECKSUMHASH"]);
            }
            return $arrayList;
        }

        function getTxnStatus($requestParamList) {
            return callAPI(PAYTM_STATUS_QUERY_URL, $requestParamList);
        }

        function getTxnStatusNew($requestParamList) {
            return callNewAPI(PAYTM_STATUS_QUERY_NEW_URL, $requestParamList);
        }

        function initiateTxnRefund($requestParamList) {
            $CHECKSUM = getRefundChecksumFromArray($requestParamList,PAYTM_MERCHANT_KEY,0);
            $requestParamList["CHECKSUM"] = $CHECKSUM;
            return callAPI(PAYTM_REFUND_URL, $requestParamList);
        }

        function callAPI($apiURL, $requestParamList) {
            $jsonResponse = "";
            $responseParamList = array();
            $JsonData =json_encode($requestParamList);
            $postData = 'JsonData='.urlencode($JsonData);
            $ch = curl_init($apiURL);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);                                                                  
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
            curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                         
            'Content-Type: application/json', 
            'Content-Length: ' . strlen($postData))                                                                       
            );  
            $jsonResponse = curl_exec($ch);   
            $responseParamList = json_decode($jsonResponse,true);
            return $responseParamList;
        }

        function callNewAPI($apiURL, $requestParamList) {
            $jsonResponse = "";
            $responseParamList = array();
            $JsonData =json_encode($requestParamList);
            $postData = 'JsonData='.urlencode($JsonData);
            $ch = curl_init($apiURL);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);                                                                  
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
            curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                         
            'Content-Type: application/json', 
            'Content-Length: ' . strlen($postData))                                                                       
            );  
            $jsonResponse = curl_exec($ch);   
            $responseParamList = json_decode($jsonResponse,true);
            return $responseParamList;
        }
        function getRefundChecksumFromArray($arrayList, $key, $sort=1) {
            if ($sort != 0) {
                ksort($arrayList);
            }
            $str = getRefundArray2Str($arrayList);
            $salt = generateSalt_e(4);
            $finalString = $str . "|" . $salt;
            $hash = hash("sha256", $finalString);
            $hashString = $hash . $salt;
            $checksum = encrypt_e($hashString, $key);
            return $checksum;
        }
        function getRefundArray2Str($arrayList) {	
            $findmepipe = '|';
            $paramStr = "";
            $flag = 1;	
            foreach ($arrayList as $key => $value) {		
                $pospipe = strpos($value, $findmepipe);
                if ($pospipe !== false) 
                {
                    continue;
                }

                if ($flag) {
                    $paramStr .= checkString_e($value);
                    $flag = 0;
                } else {
                    $paramStr .= "|" . checkString_e($value);
                }
            }
            return $paramStr;
        }
        function callRefundAPI($refundApiURL, $requestParamList) {
            $jsonResponse = "";
            $responseParamList = array();
            $JsonData =json_encode($requestParamList);
            $postData = 'JsonData='.urlencode($JsonData);
            $ch = curl_init($apiURL);	
            curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_URL, $refundApiURL);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);  
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
            $headers = array();
            $headers[] = 'Content-Type: application/json';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);  
            $jsonResponse = curl_exec($ch);   
            $responseParamList = json_decode($jsonResponse,true);
            return $responseParamList;
        }

    }
    
    public function getConfigPaytmSetting()
    {
        define('PAYTM_ENVIRONMENT', 'PROD'); // PROD
        define('PAYTM_MERCHANT_KEY', 'yAVZIO8X8a#h&Cfc'); //Change this constant's value with Merchant key received from Paytm.
        define('PAYTM_MERCHANT_MID', 'aRUKQB44056800848352'); //Change this constant's value with MID (Merchant ID) received from Paytm.
        define('PAYTM_MERCHANT_WEBSITE', 'DEFAULT'); //Change this constant's value with Website name received from Paytm.

        /*$PAYTM_STATUS_QUERY_NEW_URL='https://securegw-stage.paytm.in/merchant-status/getTxnStatus';
        $PAYTM_TXN_URL='https://securegw-stage.paytm.in/theia/processTransaction';*/
        if (PAYTM_ENVIRONMENT == 'PROD') {
            $PAYTM_STATUS_QUERY_NEW_URL='https://securegw.paytm.in/merchant-status/getTxnStatus';
            $PAYTM_TXN_URL='https://securegw.paytm.in/order/process';
        }

        define('PAYTM_REFUND_URL', '');
        define('PAYTM_STATUS_QUERY_URL', $PAYTM_STATUS_QUERY_NEW_URL);
        define('PAYTM_STATUS_QUERY_NEW_URL', $PAYTM_STATUS_QUERY_NEW_URL);
        define('PAYTM_TXN_URL', $PAYTM_TXN_URL);
    
   
    }

    
    public function paytmcallback(Request $request)
    {
        /*return $request;*/
        $order_number = $request->ORDERID;
        if($request->STATUS == "TXN_SUCCESS")
        {
            $orderDetails = DB::table('order_system')->where('order_number',$order_number)->get();
            foreach($orderDetails as $data)
            {
                $order_type = $data->order_type;
                // For update prodcut Quantity
                if($order_type != "DESIGN-SHIRT")
                {
                    $pros = json_decode($data->product_details);
                    foreach($pros as $key => $prosin)
                    {
                        $pros_details = explode('-',$prosin);
                        $pro_array = explode(',',$pros_details[0]);
                        $pro_items = json_encode($pro_array);
                        $old_qty = DB::table('product_quantity')->where('product_id',$key)->where('attributes_value',$pro_items)->value('product_quantity');
                        $old_qty_id = DB::table('product_quantity')->where('product_id',$key)->where('attributes_value',$pro_items)->value('product_quantity_id');
                        $new_qty['product_quantity'] = $old_qty - $pros_details[1];
                        DB::table('product_quantity')->where('product_quantity_id',$old_qty_id)->update($new_qty);

                    }

                }
                if($data->order_type == "CHECKOUT")
                {
                    $ip = $request->ip();
                    DB::table('carts')->where('user_id',Auth::user()->id)->orwhere('ip_address',$ip)->delete();
                }
            }
        }
        
      
        $up_pro['transaction_id'] = $request->TXNID;
        //$up_pro['order_date'] = $request->TXNDATE;
        $order_number = $request->ORDERID;
        $up_pro['payment_status'] = $request->STATUS;
        
        
        if($request->STATUS == "TXN_SUCCESS")
        {
            $up_pro['order_status'] = "Confirmed";
            Order::where('order_number',$order_number)->update($up_pro);
            $mailsend = $this->sendOrderMail($order_number);
            $request->session()->flash('alert-success','Thank you for your purchase. Your order has been successfully placed. Please your spam, incase mail not recived in inbox');
        }
        else
        {
            $up_pro['order_status'] = "Pending";
            Order::where('order_number',$order_number)->update($up_pro);
            $request->session()->flash('alert-danger','Sorry!! You have cancel your order.');
        }
     
       /* echo $mailsend;*/
      return redirect('yourOrder');
    }
    
    
   
    public function sendOrderMail($order_number)
    {        
        $user_name = Auth::user()->name;
        $logo_url = "https://thezouple.com/public/front/images/logo.png";
        $url = "https://thezouple.com/printInvoicesss/".$order_number;
        $order_date = DB::table('order_system')->where('order_number',$order_number)->value('order_date');
        $ord_date = date('d/M/Y', strtotime($order_date));
        $datas = DB::table('order_system')->where('order_number',$order_number)->value('product_details');
        $check_order = DB::table('order_system')->where('order_number',$order_number)->value('order_type');
        
        $messageBody1="<!DOCTYPE html><html lang='en'>

            <head>
                <title>The Zouple</title>
                <meta charset='utf-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1'>

            </head>

            <body>

               <section style='width: 80%; min-height: 300px;padding: 15px;margin: 25px auto; background: rgba(255,255,255,.6);  display: block;border-radius: 2px;border:2px solid black'>
                    <div style='text-align:center;background-color:black;padding:8px;'>
                        <img src='{$logo_url}' width='100px'></a>
                     </div>
                    <div style='text-align:left;'>
                        <h1 style='text-align: left; font-size:30px; color:#969696;'>ORDER PLACED SUCCESSFULLY</h1>
                       
                        <p style='border-bottom: 3px solid #000000;'> </p>
                         <h3 style='text-align: left;margin:0px!important; color:#969696;'>Hi {$user_name},</h3>
                        <br>
                          <div style='margin-bottom:15px!important;'>
                            <div style='float:left;'>
                                 <h3 style='text-align: left;margin:0px!important; color:#969696;'>Order Number : #{$order_number}</h3>
                            </div>
                            <div style='float:right;'>
                                 <h3 style='text-align: left;margin:0px!important; color:#969696;'>Order Date :{$ord_date}</h3>
                            </div>
                       </div>";
                       if($check_order != "DESIGN-SHIRT")
                       {
                           
                       $messageBody1=$messageBody1."
                        <table width='100%' border='2px black solid' style='margin-top:15px!important; margin-bottom:15px!important;clear:both;'>
                        <tr>
                            <th style='padding:10px;'>
                            Product Name
                            </th>
                            <th style='padding:10px;'>Quantity
                            </th>
                        </tr>";
                        $pro_det = json_decode($datas);
                        foreach($pro_det as $key => $dt)
                        {
                            $pro_name = DB::table('products')->where('product_id',$key)->value('product_title');
                            $prs = explode('-',$dt);
                        
                        $messageBody1=$messageBody1."
                        <tr>
                            <td style='padding:10px;'>
                                {$pro_name}
                            </td>
                            <td style='padding:10px;'>{$prs[1]}
                            </td>
                        </tr>";
                        }
                        $messageBody1=$messageBody1."</table>
                        <div style='font-weight: normal;clear:both; margin-bottom:5px; text-align: left; color:#969696; font-size: 16px;'>Thanks for shopping with Zouple.</div><br>
                        <div style='font-weight: normal;clear:both; margin-bottom:5px; text-align: left; color:#969696; font-size: 16px;'>We are happy to serve you.</div><br>
                        <div style='font-weight: normal;clear:both; margin-bottom:5px; text-align: left; color:#969696; font-size: 16px;'>This mail is sent to you for placing your order successfully.</div><br>
                        <div style='font-weight: normal;clear:both; margin-bottom:5px; text-align: left; color:#969696; font-size: 16px;'>As soon as the order will be confirmed you will get a <b>Confirmation mail</b>. Usually it take 1-2 day or less. After confirmation mail , your order will be shipped,which will take 7-10 in complete process.</div><br>
                        
                         
                         ";
                       }
                       else
                       {
                           $messageBody1=$messageBody1."
                           <div style='clear:both;'></div>
                           <h3 style='text-align: left;margin-top:10px!important; color:#969696;clear:both;'>Customize Shirt</h3>
                            <table width='100%' border='2px black solid' style='margin-top:15px!important; margin-bottom:15px!important;clear:both;'>
                            <tr>
                                <th style='padding:10px;'>
                                Customize Shirt Febric
                                </th>
                                <th style='padding:10px;'>";
                                   $pro_det = json_decode($datas);
                                   foreach($pro_det as $key => $dt)
                                   {
                                       $feb = DB::table('febric')->where('febric_id',$dt)->value('name');
                                       break;
                                   }
                                    
                                 $messageBody1=$messageBody1."  
                                 {$feb}
                                </th>
                            </tr>
                            </table>
                            <div style='font-weight: normal;clear:both; margin-bottom:5px; text-align: left; color:#969696; font-size: 16px;'>Thankyou for shopping with Zouple.</div><br>
                            <div style='font-weight: normal;clear:both; margin-bottom:5px; text-align: left; color:#969696; font-size: 16px;'>We are happy to service you.</div><br>
                            <div style='font-weight: normal;clear:both; margin-bottom:5px; text-align: left; color:#969696; font-size: 16px;'>This mail is sent ti you for placing your order successfully.</div><br>
                            <div style='font-weight: normal;clear:both; margin-bottom:5px; text-align: left; color:#969696; font-size: 16px;'>As soon as the order will be confirmed you will get a <b>Confirmation mail</b>. Usually it take 1-2 day or less. After confirmation mail , your order will be prepared and shipped,which will take 7-10 in complete process.</div><br>
                            ";
                            
                       }
                       $messageBody1=$messageBody1."<div style='font-weight: normal; margin-bottom:5px; text-align: left; color:#969696; font-size: 16px;'>Due to some unavaible conditions , if it delays or cancel you will be confirmed by another mail. </div><br>
                         
                          <div style='font-weight: normal; margin-bottom:5px; text-align: left; color:#969696; font-size: 16px;'>Before confirmation mail you can cancel your order from your account any time. </div><br>
                          
                          <div style='font-weight: normal; margin-bottom:5px; text-align: left; color:#969696; font-size: 16px;'>After shipping of product is done, you can Return/Exchange item within 7 days after receiving. For more info please go through our Shipping Policy. </div><br>
                         
                         <div style='font-weight: normal; margin-bottom:5px; text-align: left; color:#969696; font-size: 16px;'>Please click on below link to download your invoice.</div><br>
                         
                         <a class='link-btn' href='{$url}' style='padding: 10px 20px;font-size: 18px;line-height: 24px;background: #000000;margin: 30px auto;display: block; width: 150px;text-align: center;color: #fff;text-transform: uppercase;text-decoration: none;'>DOWNLOAD</a>
                         <br>
                          <div style='font-weight: normal; margin-bottom:5px; text-align: left; color:#969696; font-size: 16px;'>You can also login to your The Zouple - My account section to download your invoice.
                            </div>
                            <p style='border-bottom: 3px solid #000000;'> </p>
                        
                          
                         <div style='font-size: 16px;margin-top:30px; margin-bottom: 5px; color:#969696; text-align: left;' In case of any questions please write to us at contact@thezouple.com and we will revert to you as soon as we receive your email. </div>
                                             <div style='font-size: 16px; margin-top:40px; color:#969696; text-align: left; margin-left:15px;'>
                                                    Thank You,
                                                 </div>
                                                  <div style='font-size: 16px; color:#969696; text-align: left; margin-left:15px;'>
                                                   The Zouple
                                                 </div>
                    </div>
                    
                    
                </section>
            
            ";
        
        
        
                        
        
        $mail = DB::table('mail_settings')->where('slug','order')->first();
        if ($mail) //checking if table is not empty
        {
            $config = array(
                'driver'     => $mail->driver,
                'host'       => $mail->host,
                'port'       => $mail->port,
                'from'       => array('address' => $mail->from_address, 'name' => $mail->from_name),
                'encryption' => $mail->encryption,
                'username'   => $mail->username,
                'password'   => $mail->password,
                'sendmail'   => '/usr/sbin/sendmail -bs',
                'pretend'    => false,
            );
            Config::set('mail', $config);
        }
        
        
        $email=Auth::user()->email;
        $subject = "Order Placed Successfully ";
        $data['msg']=$messageBody1;
        $data['subject']=$subject;
        $data['email']=$email;
        Mail::send([],[],  function ($message)  use($data) 
        {
            $message->to($data['email'])->subject($data['subject'])
                ->setBody($data['msg'], 'text/html'); 
        });
        
        return $messageBody1;
  /*   echo $messageBody1;    */
        
        
    }

    
    /* Paypal Return Status */
    public function getPaypalPaymentStatus(Request $request)
    {
      
       /* return $request;*/
        /** Get the payment ID before session clear **/
        $payment_id = Session::get('paypal_payment_id');
        /** clear the session payment ID **/
        Session::forget('paypal_payment_id');
        if (empty(Input::get('PayerID')) || empty(Input::get('token'))) {
            $request->session()->flash('alert-dabger','Sorry, your payment is not done so your order has not placed.');
            return Redirect::to('yourOrder');
        }
        $payment = Payment::get($payment_id, $this->_api_context);
        $execution = new PaymentExecution();
        $execution->setPayerId(Input::get('PayerID'));
        /**Execute the payment **/
        $result = $payment->execute($execution, $this->_api_context);
        if ($result->getState() == 'approved') {
           $update['transaction_id']   =   $request->token;  
           $update['order_status'] = "Confirmed";
           $update['order_date'] = date('Y-m-d H:i:s');
           $update['payment_status'] = "TXN_SUCCESS";
           $order_number= DB::table('order_system')->where('transaction_id',$payment_id)->value('order_number');
           $mailsend = $this->sendOrderMail($order_number);        
           DB::table('order_system')->where('transaction_id',$payment_id)->update($update);   
           $request->session()->flash('alert-success','Thank you for your purchase. Your order has been successfully placed.');
           return Redirect::to('yourOrder');
        }
        
        $request->session()->flash('alert-danger','Sorry, your payment is not done so your order has not placed.');
        return Redirect::to('yourOrder');
    }
}
