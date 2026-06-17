<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use Auth,Redirect,View,File,Config,Image;
use Validator;
use DB;
use Session;
use Mail;
use App\Helper\BasicHelper;

use App\AttributValue;
use App\ShirtCategory;
use App\ShirtAttribut;


class DesignShirtController extends Controller
{
    public function designShirtList(Request $request)
    {
    	$page_title = "Design Shirt - Zouple";
        
        if(Session::has('currency')) 
           {

           }
        else
          {
             Session::put('currency',"rupee_price");
          }

    	$data['febric_data'] = DB::table('febric')->orderby('febric_id', 'asc')->get();
        
        $data['element_show_data'] = DB::table('element')->orderby('element_id', 'asc')->get();

    	return view('front.design.febric',compact('page_title'), $data); 
    }
    
    public function elementsListShow(Request $request)
    {
    	$febric_id = $request->febric;
        $order_number = $request->order_no;
        $order_no =  $order_number+1;  
        $pre_element = $request->element;
        Session::put('febric', $febric_id);
        
        $element_id = DB::table('element')->where('order_no',$order_no)->value('element_id');
        if($element_id != "")
        {
            $element_name =  DB::table('element')->where('element_id', $element_id)->value('name');
            $page_title = $element_name." List - Zouple";
            $data['element_data'] = DB::table('element_value')
                                    ->where('element_id',$element_id)
                                    ->get();
            
            $data['element_show_data'] = DB::table('element')->orderby('element_id', 'asc')->get();
            $data['size_img'] = DB::table('shirt_size')->orderby('shirt_size_id', 'asc')->get();

            return view('front.design.elementvalues',compact('page_title', 'element_name', 'order_no','pre_element'), $data);  
        }
        else
        {
            $data['febric']  = Session::get('febric');
            $elementid = DB::table('element')->get(); 
            foreach($elementid as $element)
            {
                $data[$element->name]  = Session::get($element->name);
            }
            /*echo "<pre>";
            print_r($data);
            echo "</pre>";*/
            $page_title = "Thankyou for desgined Your Shirt";
            return view('front.design.your_design_shirt',compact('page_title','data'));  
        }
        
        
    }
    
    public function nextElementListShow(Request $request)
    {
        if(Session::has('currency')) 
       {
           
       }
        else
        {
            Session::put('currency',"rupee_price");
        }
        
        Session::put($request->element_name,$request->element);
        $order_number = $request->order_no;
        $order_no =  $order_number+1; 
        $pre_element = $request->element;
        $element_id = DB::table('element')->where('order_no',$order_no)->value('element_id');
        if($element_id != "")
        {
            $element_name =  DB::table('element')->where('element_id', $element_id)->value('name');
            $page_title = $element_name." List - Zouple";
            $data['element_data'] = DB::table('element_value')
                                    ->where('element_id',$element_id)
                                    ->get();
            
            $data['element_show_data'] = DB::table('element')->orderby('element_id', 'asc')->get();
            $data['size_img'] = DB::table('shirt_size')->orderby('shirt_size_id', 'asc')->get();

            return view('front.design.elementvalues',compact('page_title', 'element_name', 'order_no','pre_element'), $data);  
        }
        else
        {
            $data['febric']  = Session::get('febric');
            $elementid = DB::table('element')->get(); 
            foreach($elementid as $element)
            {
                $data[$element->name]  = Session::get($element->name);
            }
            /*echo "<pre>";
            print_r($data);
            echo "</pre>";*/
            $page_title = "Thankyou for desgined Your Shirt";
            return view('front.design.your_design_shirt',compact('page_title','data'));  
        }
    }
    
     public function selectedElementChangeShow(Request $request, $dt)
     {
         $page_title = " Your Designrd Shirt  - Zouple";
         $ele_val_id = $dt;
         $element_id =  DB::table('element_value')->where('element_value_id', $dt)->value('element_id');
         /*echo $element_id;*/
         
         $order_no = DB::table('element')->where('element_id', $element_id)->value('order_no');
         /*echo $order_no;*/
         
         $element_name =  DB::table('element')->where('element_id', $element_id)->value('name');
         $page_title = $element_name." List - Zouple";
         $data['element_data'] = DB::table('element_value')
                                    ->where('element_id',$element_id)
                                    ->get();
            
         $data['element_show_data'] = DB::table('element')->orderby('element_id', 'asc')->get();
         $data['size_img'] = DB::table('shirt_size')->orderby('shirt_size_id', 'asc')->get();
         
         return view('front.design.update_element_values',compact('page_title', 'element_name', 'order_no','ele_val_id'), $data);  
     }
    
    public function seeYourShirtShow(Request $request)
    {
           Session::put($request->element_name,$request->element);
       
          
            $data['febric']  = Session::get('febric');
            $elementid = DB::table('element')->get(); 
            foreach($elementid as $element)
            {
                $data[$element->name]  = Session::get($element->name);
            }
            /*echo "<pre>";
            print_r($data);
            echo "</pre>";*/
            $page_title = "Thankyou for desgined Your Shirt";
            return view('front.design.your_design_shirt',compact('page_title','data'));  
        
    }
    
    public function selectedFebricChangeShow(Request $request, $dt)
     {
         $page_title = "Design Shirt - Zouple";

    	$data['febric_data'] = DB::table('febric')->orderby('febric_id', 'asc')->paginate(5);
        
        $data['element_show_data'] = DB::table('element')->orderby('element_id', 'asc')->get();
        
        $feb_id = $dt;
        $data['size_img'] = DB::table('shirt_size')->orderby('shirt_size_id', 'asc')->get();
    	

         return view('front.design.update_febric_values',compact('page_title', 'feb_id'), $data);  
     }
    
    public function seeYourFebricShow(Request $request)
    {
        
        
        
        $febric_id = $request->febric;
        $order_number = $request->order_no;
        $order_no =  $order_number+1;  
        
        Session::put('febric', $febric_id);
        
        $data['febric']  = Session::get('febric');
        $elementid = DB::table('element')->get(); 
        foreach($elementid as $element)
        {
            $data[$element->name]  = Session::get($element->name);
        }
        $page_title = "Thankyou for desgined Your Shirt";
        return view('front.design.your_design_shirt',compact('page_title','data'));  
        
    }
    
    
    
    
    public function preElementListShow(Request $request)
    {
        $order_number = $request->order_no;
        $order_no =  $order_number-1; 
        $element_id = DB::table('element')->where('order_no',$order_no)->value('element_id');
        
        if($order_no == 0)
        {
            $febricss = Session::get('febric');
            
            $page_title = "Design Shirt - Zouple";

            $data['febric_data'] = DB::table('febric')->orderby('febric_id', 'asc')->paginate(5);
        
            $data['element_show_data'] = DB::table('element')->orderby('element_id', 'asc')->get();

            $data['size_img'] = DB::table('shirt_size')->orderby('shirt_size_id', 'asc')->get();

            return view('front.design.febric',compact('page_title', 'febricss'), $data);
        }
        else
        {
            if($element_id != "")
                {
                    $element_name =  DB::table('element')->where('element_id', $element_id)->value('name');
                    $pre_element = Session::get($element_name);
                    $page_title = $element_name." List - Zouple";
                    $data['element_data'] = DB::table('element_value')
                                            ->where('element_id',$element_id)
                                            ->get();

                    $data['element_show_data'] = DB::table('element')->orderby('element_id', 'asc')->get();
                 $data['size_img'] = DB::table('shirt_size')->orderby('shirt_size_id', 'asc')->get();

                    return view('front.design.elementvalues',compact('page_title', 'element_name', 'order_no','pre_element'), $data);  
                }
                else
                {


                $page_title = "Design Shirt - Zouple";

                $data['febric_data'] = DB::table('febric')->orderby('febric_id', 'asc')->paginate(5);

                $data['element_show_data'] = DB::table('element')->orderby('element_id', 'asc')->get();

                return view('front.design.febric',compact('page_title'), $data);
                }
        }
        
    }
    
    
    
    public function goDesignCheckoutList(Request $request)
    {  
         $user_id = Auth::user()->id;
         $page_title = "Design Checkout - Zouple";

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
        
        
        
            $datafeb['febric']  = Session::get('febric');
            $elementid = DB::table('element')->get(); 
            foreach($elementid as $element)
            {
                $datafeb[$element->name]  = Session::get($element->name);
            }
            
        $queryText=Session::get('queryText');
        /*echo $queryText;*/
        
     return view('front.design.design_checkout',compact('page_title', 'datafeb','queryText'), $data);     
       
    }
    
    public function shirtCheckoutStore(Request $request)
    {  
         echo $user_id = Auth::user()->id;
         echo $amount = $request->price;
        
         $datafeb['febric']  = Session::get('febric');
            $elementid = DB::table('element')->get(); 
            foreach($elementid as $element)
            {
                $datafeb[$element->name]  = Session::get($element->name);
            }
        
        return $datafeb;
        
    }
    
    
    public function clearDesignFormat(Request $request)
    {  
         
         $request->session()->forget('febric');
            $elementid = DB::table('element')->get(); 
            foreach($elementid as $element)
            {
                $request->session()->forget($element->name);
            }
        
        return redirect('designShirt');
        
        
    }
    
    public function querySetSessionStore(Request $request)
    {
       
         Session::put("queryText",$request->queryText);
         //echo Session::get('queryText');
        return $request;
    }
    
    
    
    
    
    
    
    
    
    
    
    
}
