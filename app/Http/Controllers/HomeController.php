<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Slider;
use App\Category;
use App\Offerbanner;
use Auth,Redirect,View,File,Config,Image;
use Validator;
use DB;
use App\Product;
use App\Accessories;
use App\ProductQty;
use Session;
use Mail;
use App\Helper\BasicHelper;


class HomeController extends Controller
{
   
   public function __construct(Request $request)
    {
       /* Location */
         $ips = $request->ip();
        //$ips = "207.244.89.90";
       // $ips = "103.73.34.128";
       return $ips;
        $dataLocation = \Location::get($ips);
        foreach($dataLocation as $dt)
        {
            $arrLoc[] =  $dt;
        }
        $cun_code = $arrLoc[2];
        $amtType =  DB::table('currency')->where('currency_code',$cun_code)->value('currency');
      
        Session::put('currency',$amtType);
        Session::save();
    }
    public function index(Request $request)
    {
        try {
       
       $is_flash = "INACTIVE";
        $amt = 0;
        
        $count_down = date('M d, Y 00:00:00');
        
       $data['cate_data'] = Category::where('is_show',"SHOW")->get();
        
       $data['blog_data'] = DB::table('blog')->orderby('blog_id', 'desc')->limit(2)->get();
        
       $data['main_video'] = DB::table('video')->get();
        
       

       $data['slider_data'] = DB::table('sliders')->where('is_active', 'ACTIVE')->get();
        
       $data['banner_data'] = DB::table('offerbanners')->orderby('offerbanners_id', 'asc')->get();
        
      $data['featured_products'] = Product::leftJoin('product_quantity', function ($join) {
                $join->on('product_quantity.product_quantity_id', '=', DB::raw('(SELECT product_quantity_id FROM product_quantity WHERE product_quantity.product_id = products.product_id LIMIT 1)'));})
            ->where('products.is_active','ACTIVE')
            ->where('products.featured_product','YES')
            ->orderBy('products.product_id', 'ASC')->take(12)->get();
       
        
        $data['new_arrivals'] = Product::leftJoin('product_quantity', function ($join) {
                $join->on('product_quantity.product_quantity_id', '=', DB::raw('(SELECT product_quantity_id FROM product_quantity WHERE product_quantity.product_id = products.product_id LIMIT 1)'));})
            ->where('products.is_active','ACTIVE')
            ->where('products.new_arrivals','YES')
            ->orderBy('products.product_id', 'ASC')
            ->take(12)->get();
        
       /*return  $data['new_arrivals'] ;*/
        
        $data['view_flash_data'] = DB::table('flash_sale')
            ->join('products', 'products.product_id', '=', 'flash_sale.product_id')
            ->where('flash_sale.flash_active', 'ACTIVE')
            ->get();
        
        foreach($data['view_flash_data'] as $dt)
        {
            $start_date = $dt->start_date;
            $start_time = $dt->start_time;
            $end_time = $dt->end_time;
            $end_date = $dt->end_date;
            $is_flash = $dt->flash_active;
            $productId = $dt->product_id;
            
            $count_down = date('M d, Y 00:00:00');
            $last = $end_date." ".$end_time;
            $currdate = date('Y-m-d');
            $currTime = date('H:i:s');


            $data['flashSalesData'] = Product::leftJoin('product_quantity', function ($join) {
                $join->on('product_quantity.product_quantity_id', '=', DB::raw('(SELECT product_quantity_id FROM product_quantity WHERE product_quantity.product_id = products.product_id LIMIT 1)'));})
            ->where('products.is_active','ACTIVE')
            ->where('products.product_id',$productId)->get();
           
            if($currdate >= $end_date && $currTime > $end_time)
            {
               $is_flash = "INACTIVE"; 
            }
            $count_down = $last;

            $currencySession = Session::get('currency');
         /*   $ips = $request->ip();
             return $ips; */              
             if($currencySession == "rupee_price")
             {
                 $p_dt = json_decode($dt->product_prize);
                 
             }
             elseif($currencySession == "dollar_price")
             {
                 $p_dt = json_decode($dt->dollar_prize);
             } 
             elseif($currencySession == "euro_price")
             {
                 $p_dt = json_decode($dt->euro_prize);
             }
             else
             {
                $p_dt = json_decode($dt->dollar_prize);
             }
            foreach($p_dt as $dassa)
            {
                $pros = explode(',',$dassa);
                break;
            }
            $amt = $pros[1];
        }
        
        
        
        /*$price = DB::table('flash_sale')->orderby('flash_banner_id', 1)->value('start_date'); */
        
        
      
        $data['flash_sales_data'] = DB::table('flash_banner')->where('flash_banner_id', 1)->orderby('flash_banner_id', 'asc')->get();
        
        $data['customer_data'] = DB::table('customer_shirt')->where('customer_shirt_id', 1)->get();
        $data['testimonials'] = DB::table('testimonial')->get();
        
        
            return view('front.index',compact('is_flash','count_down','amt'), $data);
        } catch (\Exception $e) {
            // Database unavailable, return simple view with default values
            $data = [];
            $is_flash = "INACTIVE";
            $count_down = date('M d, Y 00:00:00');
            $amt = 0;
            return view('front.index', compact('is_flash','count_down','amt'), $data);
        }
    }
    
    
    public function newsub_store(Request $request)
    {
        $input = $request->all();
        $input['date'] = date("d/m/Y");
        $email =  $request->email;
        $check = DB::table('subscribe')->where('email',$email)->get();
        if(!$check->isEmpty())
        {
            $request->session()->flash('alert-danger','Sorry ! You are already subscribed.');
           
        }
        else
        {
            $check = DB::table('subscribe')->insert($input);
            $request->session()->flash('alert-success','Thank you for subscribed for latest News.');
            
        }
        return redirect()->back();
        
    }
    
    public function ipAddressFounder(Request $request)
    {
        echo "Mahi";
        $m = $this->getIp();
        echo $clientIP = \Request::getClientIp(true);;
    }
    
     public function cmspages(Request $request, $slug)
    {
       
        $data['cms_data'] = DB::table('cms')->where('slug',$slug)->get();

            $meta_description = DB::table('cms')->where('slug',$slug)->value('meta_description');
            $meta_keyword = DB::table('cms')->where('slug',$slug)->value('meta_keywords');
            $page_title = DB::table('cms')->where('slug',$slug)->value('meta_title');
       
       
        return view('front.cms.cmspage',compact('page_title', 'meta_description', 'meta_keyword'),$data);
    }
    
    public function cmspagesss(Request $request, $slug)
    {
       
        $data['cms_data'] = DB::table('cms')->where('slug',$slug)->get();
        $meta_description = DB::table('cms')->where('slug',$slug)->value('meta_description');
        $meta_keyword = DB::table('cms')->where('slug',$slug)->value('meta_keywords');
        $page_title = DB::table('cms')->where('slug',$slug)->value('meta_title');
        
        return view('front.cms.cmspagess',compact('page_title', 'meta_description', 'meta_keyword'),$data);
    }
    
    
     public function getIp(){
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key){
            if (array_key_exists($key, $_SERVER) === true){
                foreach (explode(',', $_SERVER[$key]) as $ip){
                    $ip = trim($ip); // just to be safe
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false){
                        return $ip;
                    }
                }
            }
        }
    }
    
    
    public function show_search_data(Request $request)
    {
        
       
        $search_string = $request->searchData;
       
        
        $slug = Product::where('product_title','LIKE','%'.$search_string.'%')->where('is_active','ACTIVE')->get();
        
        
        foreach($slug as $dt)
        {
            $pro_id[] = $dt->product_id;
        }
        
        /*echo "<pre>";
        print_r($pro_id);
        echo "</pre>";
        
        
        return $request;*/
 
        
        if(isset($pro_id))
        {
         
            $data['products'] = Product::leftJoin('product_quantity', function ($join) {
                $join->on('product_quantity.product_quantity_id', '=', DB::raw('(SELECT product_quantity_id FROM product_quantity WHERE product_quantity.product_id = products.product_id LIMIT 1)'));})
                ->whereIn('products.product_id',$pro_id)
                ->get();
            
            
            $page_title = "Searched Product List";
            $page_head = "Searched Product List";

          return view('front.product.productSearch',compact('page_title', 'page_head'), $data);
        }
        else
        {
            $page_title = "Not Record Found";
            $page_head = "Not Record Found";
            return view('front.product.productSearch',compact('page_title','page_head'));
        }
        
    }
    
    
    
    /* Change Currency Code Start */
    
    public function changeCurrencyPage(Request $request, $currency)
    {
        Session::put('currency', \App\Helper\CurrencyHelper::normalizeSessionCurrency($currency));
        $data['val'] = "true";
        return $data;
    }

    public function messageSendStore(Request $request)
    {
        $input = $request->all();
        
        Session::put('showAlert', "No");
        Session::put('showAlertContent', "No");
        $input['name']= Auth::user()->name;
        $input['email']= Auth::user()->email;
        $input['contact']= Auth::user()->contact;
        $input['date'] = date('d/m/Y');
        DB::table('send_message')->insert($input);
        $request->session()->flash('alert-success','Thank you for answerning this Poll. Your feedback is highly apprectiated!');
        return redirect()->back();
        
    }
}
