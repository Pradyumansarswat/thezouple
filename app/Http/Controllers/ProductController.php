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
use App\Product;
use App\Accessories;
use App\Category;

class ProductController extends Controller
{
    
    /* Category Fileter Page Code Start */
   public function categories_list(Request $request,$slug)
    {
        $cate_id = Category::where('slug',$slug)->value('category_id');
        
        /*$List['products'] = Product::where('is_active','ACTIVE') 
                            ->where('category', 'LIKE', '%"'.$cate_id.'"%')
                            ->orderBy('product_id', 'desc')
                            ->paginate(9);*/
       
       $List['products'] = Product::leftJoin('product_quantity', function ($join) {
                $join->on('product_quantity.product_quantity_id', '=', DB::raw('(SELECT product_quantity_id FROM product_quantity WHERE product_quantity.product_id = products.product_id LIMIT 1)'));})
       ->where('products.is_active','ACTIVE') 
       ->where('products.category', 'LIKE', '%"'.$cate_id.'"%')
       ->orderBy('products.product_id', 'desc')
       ->paginate(16);
       
       
       $cate_name = Category::where('slug',$slug)->value('title');
        
       
        $page_head   = $cate_name;
    	$page_title  = $cate_name." - Zople";
       
       
        $attributes_val = Category::where('slug',$slug)->value('attributesvalue');
        
        $attributeName = DB::table('attribute')->get();
        foreach($attributeName as $data)
        {
            $attributeName[$data->attribute_id] = $data->name;
        }
        
        $pro_id = Product::where('is_active','ACTIVE') 
                            ->where('category', 'LIKE', '%"'.$cate_id.'"%')
                            ->pluck('product_id');
        
        $prso_data = Db::table('product_attributes')->whereIn('product_id', $pro_id)->get();
        
       if(!$prso_data->isEmpty())
       {   
           foreach($prso_data as $data)
            {
                $attr_name[] = $data->attribute_name;
            }
           $attr_names_na = array_unique($attr_name);
           foreach($attr_names_na as $dt)
           {
               $attr_namess[] = $dt;
           }
           
           /*echo "<pre>";
           print_r($attr_namess);
           echo "</pre>";*/
            $array_size = sizeof($attr_namess);
           /*echo $array_size;*/
            for($i=0;$i<$array_size;$i++)
            {
                     $pr_data = Db::table('product_attributes')->where('attribute_name', $attr_namess[$i])->get();
                    foreach($pr_data as $data)
                    {
                        $att_val =  json_decode($data->attribute_value);
                         foreach($att_val as $dt)
                         {
                             $att_vales[] = $dt;
                         }
                        
                    }
                    
                    $att_values[$attr_namess[$i]] = array_unique($att_vales);

                    unset($att_vales);
                
                    
               
            }
       }
       
       if(isset(Auth::user()->id))
       {
           $List['wishs_lists'] = DB::table('wishlists')->join('products', 'products.product_id', '=', 'wishlists.product_id')->join('product_quantity', 'product_quantity.product_quantity_id', '=', 'wishlists.product_qty_id')->orderBy('products.product_id', 'asc')->where('wishlists.user_id',Auth::user()->id)->limit(4)->get();
       }
           
       
       if(isset($att_values))
       {
          return view('front.product.category',compact('page_head','page_title','att_values'),$List); 
       }
       else
       {
           return view('front.product.category',compact('page_head','page_title'),$List);
       }
       
        /*echo json_encode($List);*/
    	
    }
    
    public function product_filter_list(Request $request)
    {
       

        $currencySession = Session::get('currency');
        
        $amount = $request->amount;
        $price = explode ("-", $amount); 
        $low=(Integer)$price[0]; $upper=(Integer)$price[1];
       /* return $low.$upper;*/
        
        $defaultpro_id = Product::where('is_active','ACTIVE')
                                ->orderBy('product_id', 'desc')
                                ->get('product_id');
        
        $pr_id[]="";
        $cate_list = $request->cate_list;
        $filter = $request->filter;
        if($cate_list != "")
        {
            $cat_list = explode(',',$cate_list);
            foreach($cat_list as $cat)
            {
                $pro_id = Product::where('is_active','ACTIVE') 
                                ->where('category', 'LIKE', '%"'.$cat.'"%')
                                ->orderBy('product_id', 'desc')
                                ->get('product_id');
   
                if(!$pro_id->isEmpty())
                {
                    foreach($pro_id as $data)
                    {
                        $catpr_id[] = $data->product_id;
                    } 
                }
                else
                {
                    unset($catpr_id);
                    $catpr_id = [0];
                }   
                    
            }
            
        }
        else
        {
            foreach($defaultpro_id as $data)
            {
                $catpr_id[] = $data->product_id;
            }
        }
        /*print_r($cats_list);*/
        
        if($filter != "")
        {
            $filters = explode(',',$filter);
            foreach($filters as $cat)
            {
                $pro_id = DB::table('product_quantity')
                                ->join('products','products.product_id','product_quantity.product_id')
                                ->where('products.is_active','ACTIVE')
                                ->whereIn('products.product_id',$catpr_id)
                                ->where('product_quantity.attributes_value','LIKE','%"'.$cat.'"%')
                                ->orderBy('products.product_id', 'desc')
                                ->get('product_quantity.product_id');
                if(!$pro_id->isEmpty())
                {
                    foreach($pro_id as $data)
                    {
                        $filter_id[] = $data->product_id;
                    } 
                }
                else
                {
                   $filter_id = $catpr_id;
                } 
            }
            
        }
        else
        {
            $filter_id = $catpr_id;
        }
        
        $pro_ids = array_unique($filter_id);
        
        $review = $request->review;
        if($review != "")
        {
            $rev = explode(",",$review);
            foreach($rev as $dr)
            {
                $poss = DB::table('review')->where('star',$dr)->whereIn('product_id',$pro_ids)->get();
                if(!$poss->isEmpty())
                {
                    $pros_id[] = $pros->product_id;
                }
                else
                {
                   $pros_id[] = 0; 
                }
            }
        }
        else
        {
            $pros_id = $pro_ids;
        }
        
        $pro_ids = array_unique($pros_id);
        
       /* echo "<pre>";
        print_r($pro_ids);
        echo "</pre>";
        return $request;*/

       
                            
         if($currencySession == "rupee_price")
         {
              $pr_qty_list = DB::table('product_quantity')->whereIn('product_id',$pro_ids)->whereBetween('rupee_net_with_gst', array($low, $upper))->groupBy('product_id')->get();
             
         }
         elseif($currencySession == "dollar_price")
         {
             $pr_qty_list = DB::table('product_quantity')->whereIn('product_id',$pro_ids)->whereBetween('dollar_net_with_gst', array($low, $upper))->groupBy('product_id')->get();
         } 
         elseif($currencySession == "euro_price")
         {
             $pr_qty_list = DB::table('product_quantity')->whereIn('product_id',$pro_ids)->whereBetween('euro_net_with_gst', array($low, $upper))->groupBy('product_id')->get();
         }
         else
         {
            $pr_qty_list = DB::table('product_quantity')->whereIn('product_id',$pro_ids)->whereBetween('dollar_net_with_gst', array($low, $upper))->groupBy('product_id')->get();
         }
        
        foreach($pr_qty_list as $data)
        {
            $prqty_ids[] = $data->product_quantity_id;
        } 
        
        /*echo "<pre>";
            print_r($pr_qty_list);
        echo "</pre>";
        return $request;*/
        
        $sorting = $request->sorting;
        if($sorting != "")
        {
            if($sorting == "popularity")
            {

                if($currencySession == "rupee_price")
                 {
                     $List['products'] = DB::table('product_quantity')
                    ->join('products','products.product_id','product_quantity.product_id')
                    ->whereIn('product_quantity.product_quantity_id',$prqty_ids)
                     ->orderBy('product_quantity.rupee_net_with_gst', 'desc')
                    ->paginate(200);
                     
                 }
                 elseif($currencySession == "dollar_price")
                 {
                     $List['products'] = DB::table('product_quantity')
                    ->join('products','products.product_id','product_quantity.product_id')
                    ->whereIn('product_quantity.product_quantity_id',$prqty_ids)
                     ->orderBy('product_quantity.doller_net_with_gst', 'desc')
                    ->paginate(200);
                 } 
                 elseif($currencySession == "euro_price")
                 {
                     $List['products'] = DB::table('product_quantity')
                    ->join('products','products.product_id','product_quantity.product_id')
                    ->whereIn('product_quantity.product_quantity_id',$prqty_ids)
                     ->orderBy('product_quantity.euro_net_with_gst', 'desc')
                    ->paginate(200);
                 }
                 else
                 {
                   $List['products'] = DB::table('product_quantity')
                    ->join('products','products.product_id','product_quantity.product_id')
                    ->whereIn('product_quantity.product_quantity_id',$prqty_ids)
                     ->orderBy('product_quantity.doller_net_with_gst', 'desc')
                    ->paginate(200);
                 }
                    
            }
            elseif($sorting == "lowtohigh")
            {

                if($currencySession == "rupee_price")
                 {
                     $List['products'] = DB::table('product_quantity')
                    ->join('products','products.product_id','product_quantity.product_id')
                    ->whereIn('product_quantity.product_quantity_id',$prqty_ids)
                     ->orderBy('product_quantity.rupee_net_with_gst', 'asc')
                    ->paginate(200);
                     
                 }
                 elseif($currencySession == "dollar_price")
                 {
                     $List['products'] = DB::table('product_quantity')
                    ->join('products','products.product_id','product_quantity.product_id')
                    ->whereIn('product_quantity.product_quantity_id',$prqty_ids)
                     ->orderBy('product_quantity.dollar_net_with_gst', 'asc')
                    ->paginate(200);
                 } 
                 elseif($currencySession == "euro_price")
                 {
                     $List['products'] = DB::table('product_quantity')
                    ->join('products','products.product_id','product_quantity.product_id')
                    ->whereIn('product_quantity.product_quantity_id',$prqty_ids)
                     ->orderBy('product_quantity.euro_net_with_gst', 'asc')
                    ->paginate(200);
                 }
                 else
                 {
                    $List['products'] = DB::table('product_quantity')
                    ->join('products','products.product_id','product_quantity.product_id')
                    ->whereIn('product_quantity.product_quantity_id',$prqty_ids)
                     ->orderBy('product_quantity.dollar_net_with_gst', 'asc')
                    ->paginate(200);
                 }


                
            }
            elseif($sorting == "hightolow")
            {

                if($currencySession == "rupee_price")
                 {
                     $List['products'] = DB::table('product_quantity')
                   ->join('products','products.product_id','product_quantity.product_id')
                    ->whereIn('product_quantity.product_quantity_id',$prqty_ids)
                     ->orderBy('product_quantity.rupee_net_with_gst', 'desc')
                    ->paginate(200);
                     
                 }
                 elseif($currencySession == "dollar_price")
                 {
                     $List['products'] = DB::table('product_quantity')
                   ->join('products','products.product_id','product_quantity.product_id')
                    ->whereIn('product_quantity.product_quantity_id',$prqty_ids)
                     ->orderBy('product_quantity.dollar_net_with_gst', 'desc')
                    ->paginate(200);
                 } 
                 elseif($currencySession == "euro_price")
                 {
                     $List['products'] = DB::table('product_quantity')
                   ->join('products','products.product_id','product_quantity.product_id')
                    ->whereIn('product_quantity.product_quantity_id',$prqty_ids)
                     ->orderBy('product_quantity.euro_net_with_gst', 'desc')
                    ->paginate(200);
                 }
                 else
                 {
                    $List['products'] = DB::table('product_quantity')
                   ->join('products','products.product_id','product_quantity.product_id')
                    ->whereIn('product_quantity.product_quantity_id',$prqty_ids)
                     ->orderBy('product_quantity.dollar_net_with_gst', 'desc')
                    ->paginate(200);
                 }
                
            }
            else
            {
                
            }
        }
        else
        {
            
            if(isset($prqty_ids))
            {
                $List['products'] = DB::table('product_quantity')
                    ->join('products','products.product_id','product_quantity.product_id')
                    ->whereIn('product_quantity.product_quantity_id',$prqty_ids)
                     ->orderBy('product_quantity.product_id', 'desc')
                    ->paginate(200);
            }
            else
            {
                $List['products'] = DB::table('product_quantity')
                    ->join('products','products.product_id','product_quantity.product_id')
                    ->where('product_quantity.product_quantity_id',0)
                     ->orderBy('product_quantity.product_id', 'desc')
                    ->paginate(200);
            }
            
           
            
        }
        
        if(!$List['products']->isEmpty())
        {
            $data = "Yes";
        }
        else
        {
            $data = "No";
        }
        
       /* echo "<pre>";
        print_r($List);
        echo "</pre>";*/
        
          $response	=	array(
                'success' => true,
                'product_filter' => view('front.product.filter_product',$List)->render(),
                'data' => $data,
            );
            return  Response::json($response); die;
        
 
    }
    
    
    /* Catergory Page Code End */
    
    
    public function productShowDetails(Request $request, $slug)
    {
        
        
        $proTitle = Product::where('slug',$slug)->value('product_title');
        $meta_description = Product::where('slug',$slug)->value('meta_description');
        $meta_keyword = Product::where('slug',$slug)->value('meta_keyword');
        $page_title = Product::where('slug',$slug)->value('meta_title');
        
        $data['products_show'] = Product::leftJoin('product_quantity', function ($join) {
                $join->on('product_quantity.product_quantity_id', '=', DB::raw('(SELECT product_quantity_id FROM product_quantity WHERE product_quantity.product_id = products.product_id LIMIT 1)'));})->where('products.slug',$slug)->get();
        
        $title = Product::where('slug',$slug)->value('product_title');
        
        $pro_id = Product::where('slug',$slug)->value('product_id');
        
        $vendor = Product::where('slug',$slug)->value('vendor_id');
        
        $ip_address = request()->ip();
        
        $data['prductss_datas'] =  DB::table('products')->where('slug','=',$slug)->get();
        
        $vendorname = DB::table('vendors')->where('vendor_id',$vendor)->value('vendor_name');
        
        $cats =  DB::table('products')->where('slug','=',$slug)->value('category');
        $product_id =  DB::table('products')->where('slug','=',$slug)->value('product_id');
       
          $cats_l = json_decode($cats);

          
              $data['rel_product'] = Product::leftJoin('product_quantity', function ($join) {
                $join->on('product_quantity.product_quantity_id', '=', DB::raw('(SELECT product_quantity_id FROM product_quantity WHERE product_quantity.product_id = products.product_id LIMIT 1)'));})
                ->where('products.is_active','ACTIVE') 
                ->where('products.category', 'LIKE', '%"'.$cats_l[1].'"%')
                ->orderBy('products.product_id', 'desc')
                ->paginate(9);
             
        
        $data['review_list'] = DB::table('review')
                ->join('products','products.product_id', '=', 'review.product_id')
                ->where('products.product_id',$product_id)
                ->where('review.is_active','ACTIVE')
                ->orderby('review.review_id', 'desc')->limit(2)
                ->get(); 
        
        $reviewCount =  DB::table('review')
                ->join('products','products.product_id','review.product_id')
                ->where('products.product_id',$product_id)
                ->where('review.is_active','ACTIVE')
                ->orderby('review.review_id', 'desc')->limit(2)
                ->count('review.product_id');  
        
        $data['att_val'] = DB::table('product_attributes')->where('product_id',$pro_id)->get();


        
        
        return view('front.product.productDetails',compact('page_title', 'title', 'vendorname','vendor', 'ip_address', 'reviewCount', 'meta_description', 'meta_keyword'), $data);
    }
    
    
    /* Flash Slaes Code Start */
    
    
    public function flashproductDetails(Request $request, $slug)
    {

        
        $proTitle = Product::where('slug',$slug)->value('product_title');
        
        $page_title = $proTitle." - Zouple";
        
        $data['products_show'] = Product::leftJoin('product_quantity', function ($join) {
                $join->on('product_quantity.product_quantity_id', '=', DB::raw('(SELECT product_quantity_id FROM product_quantity WHERE product_quantity.product_id = products.product_id LIMIT 1)'));})->where('products.slug',$slug)->get();
        
        $title = Product::where('slug',$slug)->value('product_title');
        
        $pro_id = Product::where('slug',$slug)->value('product_id');
        
        $vendor = Product::where('slug',$slug)->value('vendor_id');
        
        $ip_address = request()->ip();
        
        $data['prductss_datas'] =  DB::table('products')->where('slug','=',$slug)->get();
        
        $vendorname = DB::table('vendors')->where('vendor_id',$vendor)->value('vendor_name');
        
        $cats =  DB::table('products')->where('slug','=',$slug)->value('category');
        $product_id =  DB::table('products')->where('slug','=',$slug)->value('product_id');
       
          $cats_l = json_decode($cats);

          foreach($cats_l as $dt)
          {
              $data['rel_product'] = Product::leftJoin('product_quantity', function ($join) {
                $join->on('product_quantity.product_quantity_id', '=', DB::raw('(SELECT product_quantity_id FROM product_quantity WHERE product_quantity.product_id = products.product_id LIMIT 1)'));})
                ->where('products.is_active','ACTIVE') 
                ->where('products.category', 'LIKE', '%"'.$dt.'"%')
                ->orderBy('products.product_id', 'desc')
                ->paginate(9);
              break;
          }
        
        $data['review_list'] = DB::table('review')
                ->join('users','users.id','review.user_id')
                ->join('products','products.product_id','review.product_id')
                ->where('products.product_id',$product_id)
                ->where('review.is_active','ACTIVE')
                ->orderby('review.review_id', 'desc')->limit(2)
                ->get(); 
        
        $reviewCount =  DB::table('review')
                ->join('users','users.id','review.user_id')
                ->join('products','products.product_id','review.product_id')
                ->where('products.product_id',$product_id)
                ->where('review.is_active','ACTIVE')
                ->orderby('review.review_id', 'desc')->limit(2)
                ->count('review.product_id');  
        
        $data['att_val'] = DB::table('product_attributes')->where('product_id',$pro_id)->get();
        
        
        
        $data['view_flash_data'] = DB::table('flash_sale')
            ->join('products', 'products.product_id', '=', 'flash_sale.product_id')
            ->where('flash_sale.flash_active', 'ACTIVE')
            ->get();
        
        foreach($data['view_flash_data'] as $dt)
        {
            
            $end_time = $dt->end_time;
            $end_date = $dt->end_date;
            $last = $end_date." ".$end_time;
            $count_down = $last;
            $productId = $dt->product_id;
            
            $data['flashSalesData'] = Product::leftJoin('product_quantity', function ($join) {
                $join->on('product_quantity.product_quantity_id', '=', DB::raw('(SELECT product_quantity_id FROM product_quantity WHERE product_quantity.product_id = products.product_id LIMIT 1)'));})
            ->where('products.is_active','ACTIVE')
            ->where('products.product_id',$productId)->get();

            $currencySession = Session::get('currency');
                            
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
            
        }
        $amt = $pros[1];
        return view('front.product.flashsale',compact('page_title', 'title', 'vendorname','vendor', 'ip_address', 'reviewCount', 'amt'), $data);
    }
    
    /* Flash Sales Code End */
    
    
    
    
    public function addJSCartloginProductStore(Request $request)
    {
        
        $cart_status = "No";
        
        $vendor_id = $request->vendor_id;
        $product_id = $request->product_id;
        $user_id = "";
        $pro_qty =$request->pro_qty;
        $ip =$request->ip();
        $input['ip_address'] = $request->ip_address;
        if(isset(Auth::user()->id))
        {
            $user_id = Auth::user()->id;
        }
        //for pro attributes //
        $check_filter = $request->filter;
        
        if($check_filter != "Self")
        {
            $pros = explode(',',$request->filter);
            $pro_details = json_encode($pros);
            /*echo $pro_details;*/
            // check cart this product //

            $cart_qty_id = DB::table('product_quantity')->where('attributes_value',$pro_details)->where('product_id',$product_id)->value('product_quantity_id');


            $check_cart = DB::table('carts')
                ->where('product_qty_id',$cart_qty_id)
                ->where('product_id',$product_id)
                ->where('ip_address',$ip)->value('cart_id');


            if($check_cart == "")
            {
                $cart_status = "No";

            }
            else
            {
                $cart_status = "Yes";
            }
           /* echo $pro_details;*/

           
            

            if($cart_status == "No")
            {
                $pro_qty = $request->pro_qty;
                $input['user_id'] = $user_id;
                $input['product_id'] = $product_id;
                $input['product_qty_id'] = $cart_qty_id;
                $input['vendor_id'] = $vendor_id;
                $input['product_qty'] = $request->pro_qty;
                $input['ip_address'] = $request->ip();


                Db::table('carts')->insert($input);

            }

            $data['cart_status'] = $cart_status;
            return $data;
        }
        elseif($check_filter == "Self")
        {
            
            $cart_qty_id = DB::table('product_quantity')->where('product_id',$product_id)->value('product_quantity_id');
            $check_cart = DB::table('carts')
                ->where('product_qty_id',$cart_qty_id)
                ->where('product_id',$product_id)
                ->where('ip_address',$ip)->value('cart_id');


            if($check_cart == "")
            {
                $cart_status = "No";

            }
            else
            {
                $cart_status = "Yes";
            }
           /* echo $pro_details;*/

            $proStatus = "0";
            $checkCart = DB::table('carts')->where('ip_address',$ip)->get();
            if(!$checkCart->isEmpty())
            {
                foreach($checkCart as $dat)
                {
                    $cartVendor = $dat->vendor_id;
                    if($cartVendor == $vendor_id)
                    {
                        $vendorStatus = "Yes";
                    }
                    else
                    {
                        $vendorStatus = "No";
                    }
                    break;
                }
               /* echo $vendorStatus;*/
            }
            else
            {
                $vendorStatus = "Yes";
            }


            if($vendorStatus == "Yes" && $cart_status == "No")
            {
                $pro_qty = $request->pro_qty;
                $input['user_id'] = $user_id;
                $input['product_id'] = $product_id;
                $input['product_qty_id'] = $cart_qty_id;
                $input['vendor_id'] = $vendor_id;
                $input['product_qty'] = $request->pro_qty;
                $input['ip_address'] = $request->ip();


                Db::table('carts')->insert($input);

            }


           /* return $request;*/
          
            $data['cart_status'] = $cart_status;
            return $data;
        }
    }
    
    
    
    public function addJSCartProductStore(Request $request)
    {
        /*return $request;*/
        
        $cart_status = "No";
        
        
        
        $vendor_id = $request->vendor_id;
        $product_id = $request->product_id;
        $ip_address = $request->ip_address;
        $pro_qty =$request->pro_qty;
        
        //for pro attributes //
        $check_filter = $request->filter;
        
        if($check_filter != "Self")
        {
            $pros = explode(',',$request->filter);
            $pro_details = json_encode($pros);
            /*echo $pro_details;*/
            // check cart this product //

            $cart_qty_id = DB::table('product_quantity')->where('attributes_value',$pro_details)->where('product_id',$product_id)->value('product_quantity_id');


            $check_cart = DB::table('carts')
                ->where('product_qty_id',$cart_qty_id)
                ->where('product_id',$product_id)
                ->where('ip_address',$ip_address)->value('cart_id');


            if($check_cart == "")
            {
                $cart_status = "No";

            }
            else
            {
                $cart_status = "Yes";
            }
           /* echo $pro_details;*/

           
            

            if($cart_status == "No")
            {
                $pro_qty = $request->pro_qty;
                $input['product_id'] = $product_id;
                $input['product_qty_id'] = $cart_qty_id;
                $input['vendor_id'] = $vendor_id;
                $input['product_qty'] = $request->pro_qty;
                $input['ip_address'] = $request->ip_address;
                Db::table('carts')->insert($input);

            }

            $data['cart_status'] = $cart_status;
            return $data;
        }
        elseif($check_filter == "Self")
        {
            
            $cart_qty_id = DB::table('product_quantity')->where('product_id',$product_id)->value('product_quantity_id');
            $check_cart = DB::table('carts')
                ->where('product_qty_id',$cart_qty_id)
                ->where('product_id',$product_id)
                ->where('ip_address',$ip_address)->value('cart_id');


            if($check_cart == "")
            {
                $cart_status = "No";

            }
            else
            {
                $cart_status = "Yes";
            }
           /* echo $pro_details;*/

            $proStatus = "0";
            $checkCart = DB::table('carts')->where('ip_address',$ip_address)->get();
            if(!$checkCart->isEmpty())
            {
                foreach($checkCart as $dat)
                {
                    $cartVendor = $dat->vendor_id;
                    if($cartVendor == $vendor_id)
                    {
                        $vendorStatus = "Yes";
                    }
                    else
                    {
                        $vendorStatus = "No";
                    }
                    break;
                }
               /* echo $vendorStatus;*/
            }
            else
            {
                $vendorStatus = "Yes";
            }


            if($vendorStatus == "Yes" && $cart_status == "No")
            {
                $pro_qty = $request->pro_qty;
                $input['product_id'] = $product_id;
                $input['product_qty_id'] = $cart_qty_id;
                $input['vendor_id'] = $vendor_id;
                $input['product_qty'] = $request->pro_qty;
                $input['ip_address'] = $request->ip_address;


                Db::table('carts')->insert($input);

            }


           /* return $request;*/
          
            $data['cart_status'] = $cart_status;
            return $data;
        }
    }
    
    
    
    
    
    
    public function enquiryProductStock(Request $request)
    {
        /*return $request;*/
        $product_id = $request->product_id;
        /*$user_id = Auth::user()->id;*/
        
        //for pro attributes //
        $check_filter = $request->filter;
        if($check_filter != "" && $check_filter != "Self")
        {
            $pros = explode(',',$request->filter);
            $pro_details = json_encode($pros);
            $pro_qty = $request->pro_qty;
            $check_qty = DB::table('product_quantity')->where('attributes_value',$pro_details)->where('product_id',$product_id)->value('product_quantity');

            if($check_qty >= $pro_qty)
            {
                $data['stock_status'] = "INSTOCK";
            }
            else
            {
                $data['stock_status'] = "OUTSTOCK";
                $data['filter_check'] = "YES";
            }

            $price = DB::table('product_quantity')->where('attributes_value',$pro_details)->where('product_id',$product_id)->value('net_amount');
            $discount = DB::table('product_quantity')->where('attributes_value',$pro_details)->where('product_id',$product_id)->value('product_discount');

            if($price != "")
            {
                $data['price'] = $price;
            }
            else
            {
                $data['price'] = 0;
            }
            
            $data['discount'] = $discount;

            return $data;
        }
        elseif($check_filter != "" && $check_filter == "Self")
        {
            $pro_qty = $request->pro_qty;
            $check_qty = DB::table('product_quantity')->where('product_id',$product_id)->value('product_quantity');

            if($check_qty >= $pro_qty)
            {
                $data['stock_status'] = "INSTOCK";
            }
            else
            {
                $data['stock_status'] = "OUTSTOCK";
                $data['filter_check'] = "YES";
            }

            $price = DB::table('product_quantity')->where('product_id',$product_id)->value('net_amount');
            $discount = DB::table('product_quantity')->where('product_id',$product_id)->value('product_discount');

            if($price != "")
            {
                $data['price'] = $price;
            }
            else
            {
                $data['price'] = 0;
            }
            
            $data['discount'] = $discount;

            return $data;
        }
        
    }
    
    public function productFilterPrice(Request $request)
    {
        
        $product_id = $request->product_id;
        //for pro attributes //
        $check_filter = $request->filter;
        if($check_filter != "")
        {
            
            $pros = explode(',',$request->filter);
            $pro_details = json_encode($pros);
            
            $pro_qty = $request->pro_qty;
            $check_qty = DB::table('product_quantity')->where('attributes_value',$pro_details)->where('product_id',$product_id)->value('product_quantity');

            if($check_qty >= $pro_qty)
            {
                $data['stock_status'] = "INSTOCK";
                
            }
            else
            {
                $data['stock_status'] = "OUTSTOCK";
                $data['filter_check'] = "YES";
            }
            $data['qty'] = $check_qty;
            

            $currencySession = Session::get('currency');
                            
             if($currencySession == "rupee_price")
             {
                 $amt = DB::table('product_quantity')->where('attributes_value',$pro_details)->where('product_id',$product_id)->value('rupee_net_amount');
                 
             }
             elseif($currencySession == "dollar_price")
             {
                 $amt = DB::table('product_quantity')->where('attributes_value',$pro_details)->where('product_id',$product_id)->value('dollar_net_amount');
             } 
             elseif($currencySession == "euro_price")
             {
                 $amt = DB::table('product_quantity')->where('attributes_value',$pro_details)->where('product_id',$product_id)->value('euro_net_amount');
             }
             else
             {
                $amt = DB::table('product_quantity')->where('attributes_value',$pro_details)->where('product_id',$product_id)->value('dollar_net_amount');
             }


            $gst = DB::table('products')->where('product_id',$product_id)->min('product_gst');
            

            if($currencySession == "rupee_price")
             {
                 $netAmount = round((DB::table('product_quantity')->where('attributes_value',$pro_details)->where('product_id',$product_id)->value('rupee_net_amount') * $gst/100) + DB::table('product_quantity')->where('attributes_value',$pro_details)->where('product_id',$product_id)->value('rupee_net_amount'));
                 
             }
             elseif($currencySession == "dollar_price")
             {
                 $netAmount = round((DB::table('product_quantity')->where('attributes_value',$pro_details)->where('product_id',$product_id)->value('dollar_net_amount') * $gst/100) + DB::table('product_quantity')->where('attributes_value',$pro_details)->where('product_id',$product_id)->value('dollar_net_amount'));
             } 
             elseif($currencySession == "euro_price")
             {
                 $netAmount = round((DB::table('product_quantity')->where('attributes_value',$pro_details)->where('product_id',$product_id)->value('euro_net_amount') * $gst/100) + DB::table('product_quantity')->where('attributes_value',$pro_details)->where('product_id',$product_id)->value('euro_net_amount'));
             }
             else
             {
                $netAmount = round((DB::table('product_quantity')->where('attributes_value',$pro_details)->where('product_id',$product_id)->value('dollar_net_amount') * $gst/100) + DB::table('product_quantity')->where('attributes_value',$pro_details)->where('product_id',$product_id)->value('dollar_net_amount'));
             }

             if($currencySession == "rupee_price")
             {
                 $proPrice = round((DB::table('product_quantity')->where('attributes_value',$pro_details)->where('product_id',$product_id)->value('rupee_price') * $gst/100 ) + DB::table('product_quantity')->where('attributes_value',$pro_details)->where('product_id',$product_id)->value('rupee_price'));
                 
             }
             elseif($currencySession == "dollar_price")
             {
                 $proPrice = round((DB::table('product_quantity')->where('attributes_value',$pro_details)->where('product_id',$product_id)->value('dollar_price') * $gst/100 ) + DB::table('product_quantity')->where('attributes_value',$pro_details)->where('product_id',$product_id)->value('dollar_price'));
             } 
             elseif($currencySession == "euro_price")
             {
                $proPrice = round((DB::table('product_quantity')->where('attributes_value',$pro_details)->where('product_id',$product_id)->value('euro_price') * $gst/100 ) + DB::table('product_quantity')->where('attributes_value',$pro_details)->where('product_id',$product_id)->value('euro_price'));
             }
             else
             {
                $proPrice = round((DB::table('product_quantity')->where('attributes_value',$pro_details)->where('product_id',$product_id)->value('dollar_price') * $gst/100 ) + DB::table('product_quantity')->where('attributes_value',$pro_details)->where('product_id',$product_id)->value('dollar_price'));
             }

             if($currencySession == "rupee_price")
             {
                 $price = DB::table('product_quantity')->where('attributes_value',$pro_details)->where('product_id',$product_id)->value('rupee_net_amount');
                 
             }
             elseif($currencySession == "dollar_price")
             {
                 $price = DB::table('product_quantity')->where('attributes_value',$pro_details)->where('product_id',$product_id)->value('dollar_net_amount');
             } 
             elseif($currencySession == "euro_price")
             {
                 $price = DB::table('product_quantity')->where('attributes_value',$pro_details)->where('product_id',$product_id)->value('euro_net_amount');
             }
             else
             {
                $price = DB::table('product_quantity')->where('attributes_value',$pro_details)->where('product_id',$product_id)->value('dollar_net_amount');
             }
            
            
             
             if($price != "")
            {
                $data['net_amt'] = $netAmount;
                $data['pro_price'] = $proPrice;
            }
            else
            {
                $data['net_amt'] = 0;
                $data['pro_price'] = 0;
            }
            return $data;
        }
    }
    
    
    /* Flash Sales Code Start */
    
    public function checkflashsalesFilterPrice(Request $request)
    {

        

        /*return $request;*/
        $product_id = $request->product_id;
        //for pro attributes //
        $check_filter = $request->filter;
        if($check_filter != "")
        {
            
            $pros = explode(',',$request->filter);
            $pro_details = json_encode($pros);
            
            $pro_qty = $request->pro_qty;

            $product_qut = DB::table('product_quantity')->where('attributes_value',$pro_details)->where('product_id',$product_id)->value('product_quantity_id');
            
           
             $currencySession = Session::get('currency');
                            
             if($currencySession == "rupee_price")
             {
                 $flash_sales = DB::table('flash_sale')->where('product_id', $product_id)->value('product_prize');
                 
             }
             elseif($currencySession == "dollar_price")
             {
                 $flash_sales = DB::table('flash_sale')->where('product_id', $product_id)->value('dollar_prize');
             } 
             elseif($currencySession == "euro_price")
             {
                 $flash_sales = DB::table('flash_sale')->where('product_id', $product_id)->value('euro_prize');
             }
             else
             {
                $flash_sales = DB::table('flash_sale')->where('product_id', $product_id)->value('dollar_price');
             }

             
            
             $flas_dt = json_decode($flash_sales);
            foreach($flas_dt as $key => $dt)
            {
               
                if($key == $product_qut)
                {
                    $pros = explode(",",$dt);
                    $data['price'] = $pros[1];
                }
                
            }
            
            return $data;
        }
    }
    
    
    /* Flash Sales Code End */

    
    
    public function product_add_Wishlist(Request $request)
    {
        $pr_id = $request->product_id;
        
         $user_id = Auth::user()->id;
        
         $check=DB::table('wishlists')
             ->where('user_id',$user_id)
             ->where('product_id',$pr_id)->get();
        if(!$check->isEmpty())
        {
           return 'Your Item is allready added';   
        }
        else
        {
             $input['product_id'] = $pr_id;
            $input['user_id'] = $user_id;
            DB::table('wishlists')->insert($input);
            return 'Your Item is added';
        }
       
       
        
    }

    public function product_remove_Wishlist(Request $request)
    {
        $pr_id = $request->product_id;
         $user_id = Auth::user()->id;
        
         $check=DB::table('wishlists')
             ->where('user_id',$user_id)
             ->where('product_id',$pr_id)->delete();
        
            return 'Your Item is Remove'; 
        
    }
    

   
    
    public function pincodeStatusCheck(Request $request)
    {
        $pincode = $request->pincode;
        $check = DB::table('pincodes')
            ->where('pincode',$pincode)
            ->value('pincode_id');
        if($check>0)
        {
            return 1;
        }
        else
        {
            return 0;
        }
       
    }
    
    /* Review Code Start */
    
    public function productReviewStore(Request $request)
    {
        $input = $request->all();
        $user_id = Auth::user()->id;
        $name =DB::table('users')->where('id', $user_id)->value('name');
        $input['user_id'] = $user_id;
        $input['date'] = date("Y/m/d");
        $input['name'] =$name; 
        $input['product_id'] =$request->product_id;
            
        if($request->file('user_profile')!='')
            {
                $file=$request->file('user_profile');
                $filename=$file->getClientOriginalName();
                $imgname = $filename;
                $input['user_profile']= $imgname;       
                $destinationPath=public_path('upload/review/');       
                $request->file('user_profile')->move($destinationPath, $imgname);
            } 
        if($request->file('review_product_image')!='')
            {
                foreach($request->file('review_product_image') as $image)
                {
                    $name= $image->getClientOriginalName();
                    $destinationPath=public_path('upload/review/');       
                    $image->move($destinationPath,$name);  
                    $product_image[] = $name;
                }
                 $input['review_product_image'] = json_encode($product_image);
            }
        
       
        
        DB::table('review')->insert($input);
        $request->session()->flash('alert-success','Thank you for sharing your review and rating the product!');
        return Redirect::back();
        
    }
    
    
    public function reviewDetailShow (Request $request, $product_id)
    {
      $page_head   = DB::table('products')->where('product_id','=',$product_id)->value('product_title');
      /*$title   = DB::table('products')->where('product_id','=',$product_id)->value('meta_titlebar');*/
      $page_title  =  "Customer Review Product Details Show";

      $data['reviews_list'] = DB::table('review')
                ->join('products','products.product_id','review.product_id')
                ->where('products.product_id',$product_id)
                ->where('review.is_active','ACTIVE')->limit(4)
                ->get(); 
        
        
       

      return view('front.review.reviewDetail',compact('page_title', 'page_head'), $data);

      
    }
    
    /* Review Code End */
    
    
    
    /* ------------------------------ Product Filter Page Code Start ----------------------*/
    
                                /* Feature Product Code */
    
    public function featureProductCategoriesList (Request $request)
    {
        /*$cate_id = Category::where('slug',$slug)->value('category_id');*/
        
        $List['products'] = Product::where('is_active','ACTIVE') 
                            ->where('featured_product', 'YES')
                            ->orderBy('product_id', 'desc')
                            ->paginate(16);
       
       /*$cate_name = Category::where('slug',$slug)->value('title');*/
        
       
        $page_head = "Feature Product"; 
    	$page_title  = "Feature Product - Zouple";
       
       
        /*$attributes_val = Category::where('slug',$slug)->value('attributesvalue');*/
        
        $attributeName = DB::table('attribute')->get();
        foreach($attributeName as $data)
        {
            $attributeName[$data->attribute_id] = $data->name;
        }
        
        $pro_id = Product::where('is_active','ACTIVE') 
                            ->where('featured_product', 'YES')
                            ->pluck('product_id');
        
        $prso_data = Db::table('product_attributes')->whereIn('product_id', $pro_id)->get();
        
       if(!$prso_data->isEmpty())
       {   
           foreach($prso_data as $data)
            {
                $attr_name[] = $data->attribute_name;
            }
           $attr_names_na = array_unique($attr_name);
           foreach($attr_names_na as $dt)
           {
               $attr_namess[] = $dt;
           }
           
           /*echo "<pre>";
           print_r($attr_namess);
           echo "</pre>";*/
            $array_size = sizeof($attr_namess);
           /*echo $array_size;*/
            for($i=0;$i<$array_size;$i++)
            {
                     $pr_data = Db::table('product_attributes')->where('attribute_name', $attr_namess[$i])->get();
                    foreach($pr_data as $data)
                    {
                        $att_val =  json_decode($data->attribute_value);
                         foreach($att_val as $dt)
                         {
                             $att_vales[] = $dt;
                         }
                        
                    }
                    
                    $att_values[$attr_namess[$i]] = array_unique($att_vales);

                    unset($att_vales);
                
                    
               
            }
       }
           
       
       if(isset(Auth::user()->id))
       {
           $List['wishs_lists'] = DB::table('wishlists')->join('products', 'products.product_id', '=', 'wishlists.product_id')->orderBy('products.product_id', 'asc')->where('wishlists.user_id',Auth::user()->id)->limit(4)->get();
       }
       
        /*echo json_encode($List);*/
    	return view('front.product.productfiltercategory',compact('page_title','att_values', 'page_head'),$List);
    }
    
    
                                /* Feature Product End */
    
    
                                /* New Arriveal Product Code */
    
    public function newArrivalsProductCategoriesList (Request $request)
    {
        
         /*$cate_id = Category::where('slug',$slug)->value('category_id');*/
        
        /*$List['products'] = Product::where('is_active','ACTIVE') 
                            ->where('new_arrivals', 'YES')
                            ->orderBy('product_id', 'desc')
                            ->paginate(9);*/
        
        $List['products'] = Product::leftJoin('product_quantity', function ($join) {
                $join->on('product_quantity.product_quantity_id', '=', DB::raw('(SELECT product_quantity_id FROM product_quantity WHERE product_quantity.product_id = products.product_id LIMIT 1)'));})
            ->where('products.is_active','ACTIVE')
            ->where('products.new_arrivals','YES')
            ->orderBy('products.product_id', 'desc')
            ->paginate(16);
       
       
       /*$cate_name = Category::where('slug',$slug)->value('title');*/
        
       
        $page_head = "New Arrivals";
    	$page_title  = "New Arrivals Product - Zouple";
       
       
        /*$attributes_val = Category::where('slug',$slug)->value('attributesvalue');*/
        
        $attributeName = DB::table('attribute')->get();
        foreach($attributeName as $data)
        {
            $attributeName[$data->attribute_id] = $data->name;
        }
        
        $pro_id = Product::where('is_active','ACTIVE') 
                            ->where('featured_product', 'YES')
                            ->pluck('product_id');
        
        $prso_data = Db::table('product_attributes')->whereIn('product_id', $pro_id)->get();
        
       if(!$prso_data->isEmpty())
       {   
           foreach($prso_data as $data)
            {
                $attr_name[] = $data->attribute_name;
            }
           $attr_names_na = array_unique($attr_name);
           foreach($attr_names_na as $dt)
           {
               $attr_namess[] = $dt;
           }
           
           /*echo "<pre>";
           print_r($attr_namess);
           echo "</pre>";*/
            $array_size = sizeof($attr_namess);
           /*echo $array_size;*/
            for($i=0;$i<$array_size;$i++)
            {
                     $pr_data = Db::table('product_attributes')->where('attribute_name', $attr_namess[$i])->get();
                    foreach($pr_data as $data)
                    {
                        $att_val =  json_decode($data->attribute_value);
                         foreach($att_val as $dt)
                         {
                             $att_vales[] = $dt;
                         }
                        
                    }
                    
                    $att_values[$attr_namess[$i]] = array_unique($att_vales);

                    unset($att_vales);
                
                    
               
            }
       }
       
       
       
       if(isset(Auth::user()->id))
       {
           $List['wishs_lists'] = DB::table('wishlists')->join('products', 'products.product_id', '=', 'wishlists.product_id')->orderBy('products.product_id', 'asc')->where('wishlists.user_id',Auth::user()->id)->limit(4)->get();
       }
           
       
       
       
        /*echo json_encode($List);*/
    	return view('front.product.productfiltercategory',compact('page_title','att_values', 'page_head'),$List);
        
    }
    
    
                                /* New Arriveal Product End */
    
    /* ------------------------------ Product Filter Page Code End ----------------------*/
    
    
    
    
    
    
    /* Add To Wish List Code Start */
    
    
    public function addJSWishListloginProductStore(Request $request)
    {
      /* return $request;*/
       
       $cart_status = "No";
        
        $vendor_id = $request->vendor_id;
        $product_id = $request->product_id;
        $user_id = Auth::user()->id;
        $pro_qty =$request->pro_qty;
       /* $ip =$request->ip();
        $input['ip_address'] = $request->ip_address;*/
        
        //for pro attributes //
        $check_filter = $request->filter;
        
        
        
        if($check_filter != "Self")
        {
            $pros = explode(',',$request->filter);
            $pro_details = json_encode($pros);
            /*echo $pro_details;*/
            // check cart this product //

             $cart_qty_id = DB::table('product_quantity')->where('attributes_value',$pro_details)->where('product_id',$product_id)->value('product_quantity_id');


             $check_cart = DB::table('wishlists')
                ->where('product_qty_id',$cart_qty_id)
                ->where('product_id',$product_id)
                ->where('user_id',$user_id)->value('wishlist_id');


            if($check_cart == "")
            {
                $cart_status = "No";

            }
            else
            {
                $cart_status = "Yes";
            }
           /* echo $pro_details;*/

           
            

            if($cart_status == "No")
            {
                $pro_qty = $request->pro_qty;
                $input['user_id'] = $user_id;
                $input['product_id'] = $product_id;
                $input['product_qty_id'] = $cart_qty_id;
                /*$input['vendor_id'] = $vendor_id;*/
                $input['product_qty'] = $request->pro_qty;
                /*$input['ip_address'] = $request->ip();*/


                Db::table('wishlists')->insert($input);

            }

            $data['cart_status'] = $cart_status;
            return $data;
        }
        
        
        
        
        elseif($check_filter == "Self")
        {
            
            $cart_qty_id = DB::table('product_quantity')->where('product_id',$product_id)->value('product_quantity_id');
            $check_cart = DB::table('wishlist')
                ->where('product_qty_id',$cart_qty_id)
                ->where('product_id',$product_id)
                ->where('user_id',$user_id)->value('wishlist_id');


            if($check_cart == "")
            {
                $cart_status = "No";

            }
            else
            {
                $cart_status = "Yes";
            }
           /* echo $pro_details;*/

            $proStatus = "0";
            $checkCart = DB::table('wishlists')->where('user_id',$user_id)->get();
            if(!$checkCart->isEmpty())
            {
                foreach($checkCart as $dat)
                {
                    $cartVendor = $dat->vendor_id;
                    if($cartVendor == $vendor_id)
                    {
                        $vendorStatus = "Yes";
                    }
                    else
                    {
                        $vendorStatus = "No";
                    }
                    break;
                }
               /* echo $vendorStatus;*/
            }
            else
            {
                $vendorStatus = "Yes";
            }


            if($cart_status == "No")
            {
                $pro_qty = $request->pro_qty;
                $input['user_id'] = $user_id;
                $input['product_id'] = $product_id;
                $input['product_qty_id'] = $cart_qty_id;
                /*$input['vendor_id'] = $vendor_id;*/
                $input['product_qty'] = $request->pro_qty;
                /*$input['ip_address'] = $request->ip();*/


                Db::table('wishlists')->insert($input);

            }


           /* return $request;*/
          
            $data['cart_status'] = $cart_status;
            return $data;
        }
       
    }
    
    /* Add To Wish List Code End */
    
    
}
