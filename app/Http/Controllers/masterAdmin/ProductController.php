<?php

namespace App\Http\Controllers\masterAdmin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Accessories;
use Auth,Redirect,View,File,Config,Image;
use Validator;
use DB;
use Input;
use App\Helper\BasicHelper;
use App\Helper\CurrencyHelper;
use App\Product;
use App\Category;
use App\Imports\productImport;
use Maatwebsite\Excel\Facades\Excel;

use App\Attribute;


use App\Exports\UsersExport;
use App\Imports\UsersImport;

class ProductController extends Controller
{
   /* Prodcut */
    
    public function product_create(Request $request)
    {
        $data['cate_data'] = DB::table('categorys')->where('parent_id',0)->where('is_active','ACTIVE')->get();
       
        $categories = Category::where('parent_id', '=', 0)->where('is_active','ACTIVE')->get();
        $data['attribute_data'] = DB::table('attribute')->orderby('attribute_id', 'asc')->get();

        $data['vendors_data'] = DB::table('vendors')->orderby('vendor_id', 'asc')->get();

       
       $page_title = "Add Product - Zouple";
       return view('masters.product.add_product',compact('page_title','categories'),$data);
    }
        
    
    
    public function product_store(Request $request)
    {
        $input = $request->all();
        /*$this->validate($request , array
        (    
            'product_title' => 'required|max:255|unique:products',
            'product_sku' => 'required|max:255|unique:products',
        )); */
      if($request->file('product_header_image')!='')
        {
            $file=$request->file('product_header_image');
            $filename=$file->getClientOriginalName();
            $imgname = $filename;
            $input['product_header_image']= $imgname;       
            $destinationPath=public_path('upload/product/');       
            $request->file('product_header_image')->move($destinationPath, $imgname);
        } 
        if($request->file('product_images')!='')
        {
            foreach($request->file('product_images') as $image)
            {
                $name= $image->getClientOriginalName();
                $destinationPath=public_path('upload/product/');       
                $image->move($destinationPath,$name);  
                $product_image[] = $name;
            }
        }
        
        $cmsObj = new Product();
        $input['slug'] = BasicHelper::getproductSlug($cmsObj, $request->product_title);
        $input['product_images'] = json_encode($product_image);
        
        $input['category'] = json_encode($request->category);
      
        
        unset($input['attribute_name']);
        unset($input['attribute_value']);
        
        Product::insert($input);
        
        $pro_id = DB::getPdo()->lastInsertId();
        
        $meta_tag['meta_title'] = $request->meta_title;
        $meta_tag['meta_keyword'] = $request->meta_keyword;
        $meta_tag['meta_description'] = $request->meta_description;
        $meta_tag['meta_page'] = $request->product_title;
        $meta_tag['meta_slug'] = $input['slug'];
        $meta_tag['_token'] =  $request->_token;
        
        DB::table('meta_tags')->insert($meta_tag);
        
        $product_que['product_id'] = $pro_id;
        
        $attribute_name = $request->attribute_name ?? [];
        $att_val1 = $request->attribute_val1 ?? [];
        $att_val2 = $request->attribute_val2 ?? [];
        $att_val3 = $request->attribute_val3 ?? [];
        $att_val4 = $request->attribute_val4 ?? [];
        $att_val5 = $request->attribute_val5 ?? [];

        $count = count($attribute_name);

        for($i=0;$i<$count;$i++)
        {
            $att_values = array_filter([
                $att_val1[$i] ?? '',
                $att_val2[$i] ?? '',
                $att_val3[$i] ?? '',
                $att_val4[$i] ?? '',
                $att_val5[$i] ?? '',
            ]);
            $att_values = array_values($att_values);
            if(empty($att_values)) continue;
            $product_que['product_id'] = $pro_id;
            $product_que['attribute_value'] = json_encode($att_values);
            $product_que['attribute_name'] = $attribute_name[$i];
            $product_que['_token'] = $request->_token;
            DB::table('product_attributes')->insert($product_que);
        }
        $pro_ats =  DB::table('product_attributes')->where('product_id',$pro_id)->get();
        $atts;
        foreach($pro_ats as $dt)
        {
            $at_name = $dt->attribute_name;
            $at_values = $dt->attribute_value;
            $a_vals = json_decode($at_values);
            foreach($a_vals as $dt)
            {
                $at_s[] = $at_name.":".$dt;
            }
            $atts[] = $at_s;
            unset($at_s);
        }
        
        $arrSize = $this->get_combinations($atts);
        
        foreach($arrSize as $dat)
        {
            $inputs['product_id'] = $pro_id;
            $inputs['attributes_value'] = json_encode($dat);
            $inputs['product_quantity'] = 0;
            $inputs['rupee_price'] = 0;
            $inputs['dollar_price'] = 0;
            $inputs['euro_price'] = 0;
            $inputs['product_discount'] = 0;
            $inputs['rupee_net_amount'] = 0;
            $inputs['product_weight'] = 0;
            
            DB::table('product_quantity')->insert($inputs);
                
        }
        
        
  /*      
        echo "<pre>";
        print_r($arrSize);
        echo "</pre>";*/
        
        // Product Quantity Table // 
        
        
       
        
        $request->session()->flash('alert-success','Product has been sucessfully added.');
        return Redirect::route('product_list');
         
    }
    
    // For Product Arrtibutes Combination Function  // 
    public function get_combinations($arrays) 
    {
        $result = array();
        $arrays = array_values($arrays);
        $sizeIn = sizeof($arrays);
        $size = $sizeIn > 0 ? 1 : 0;
        foreach ($arrays as $array)
            $size = $size * sizeof($array);
        for ($i = 0; $i < $size; $i ++)
        {
            $result[$i] = array();
            for ($j = 0; $j < $sizeIn; $j ++)
                array_push($result[$i], current($arrays[$j]));
            for ($j = ($sizeIn -1); $j >= 0; $j --)
            {
                if (next($arrays[$j]))
                    break;
                elseif (isset ($arrays[$j]))
                    reset($arrays[$j]);
            }
        }
        return $result;
    }
    
    
    public function product_listing(Request $request)
    {
       /* $data['product_data'] = DB::table('products')
             ->orderBy('products.product_id', 'DESC')
             ->get();
        */
        
        $data['product_data'] = Product::leftJoin('product_quantity', function ($join) {
                $join->on('product_quantity.product_quantity_id', '=', DB::raw('(SELECT product_quantity_id FROM product_quantity WHERE product_quantity.product_id = products.product_id LIMIT 1)'));})
       
       ->orderBy('products.product_id', 'desc')
       ->get();
        
        
        
         $page_title = "Product List - Zouple";
         
        /* return $data;*/
         return view('masters.product.product_list',compact('page_title'),$data);
    }

    public function productshowdetail(Request $request, $product_id)
    {
        $data['product_show_data'] = DB::table('products')
            ->where('product_id',$product_id)
            ->get();
        
        $vender_id = DB::table('products')
            ->where('product_id',$product_id)
            ->value('vendor_id');
        
        $vendorname = DB::table('vendors')
            ->where('vendor_id',$vender_id)
            ->value('vendor_name');
        
        
        
        


        /*$data['product_show_data'] = DB::table('products')
            ->join('vendors', 'vendors.vendor_id', '=', 'products.vendor_id')
            ->select('products.*','vendors.vendor_name')
            ->orderBy('products.product_id', 'DESC')
            ->get();*/


        $page_title = "Product Show - Zouple";
        return view('masters.product.product_show',compact('page_title' , 'vendorname'),$data);    
    }

    public function productUpdate(Request $request, $product_id)
    {
        $data['product_update_data'] = DB::table('products')
             ->where('products.product_id',$product_id)
            ->orderBy('products.product_id', 'DESC')
            ->get();



        $data['product_attributes_data'] = DB::table('product_attributes')
             ->where('product_id',$product_id)
            ->orderBy('product_id', 'DESC')
            ->get();


        $data['attribute_data'] = DB::table('attribute')->orderby('attribute_id', 'asc')->get();

        $data['vendors_data'] = DB::table('vendors')->orderby('vendor_id', 'asc')->get();
        
        $data['cate_data'] = DB::table('categorys')->where('parent_id',0)->where('is_active','ACTIVE')->get();
       
        
        $categories = Category::where('parent_id', '=', 0)->where('is_active','ACTIVE')->get();

        $data['attribute_data'] = DB::table('attribute')->orderby('attribute_id', 'asc')->get();
        
        /*$data['free_ass'] = Accessories::all();*/


         $page_title = "Product Update - Zouple";
         return view('masters.product.product_edit',compact('page_title','categories'),$data);
    }

    public function product_update_save(Request $request)
    {
        $input = $request->all();
        $product_id = $request->product_id;
        if($request->file('product_header_image')!='')
        {
            $product_header_image = $request->product_header_image_old;
            $fullpath=public_path('upload/product/').$product_header_image;
            File::delete($fullpath);
            $file=$request->file('product_header_image');
            $filename=$file->getClientOriginalName();
            $imgname = $filename;
            $input['product_header_image']= $imgname;       
            $destinationPath=public_path('upload/product/');       
            $request->file('product_header_image')->move($destinationPath, $imgname);
        } 
        
        if($request->file('product_images')!='')
        { 
          $product_images = $request->product_images_old;
          $backimages=json_decode($product_images);
          foreach($backimages as $image)
          {
            $fullpath=public_path('upload/product/').$image;
            File::delete($fullpath);
          }
            foreach($request->file('product_images') as $image)
            {
                $name= $image->getClientOriginalName();
                $destinationPath=public_path('upload/product/');       
                $image->move($destinationPath,$name);  
                $product_image[] = $name;
            }
            $input['product_images'] = json_encode($product_image);
        }
        
        
        $cmsObj = new Product();
        $input['slug'] = BasicHelper::getproductSlug($cmsObj, $request->product_title);

        $input['category'] = json_encode($request->category);
        
        
        /*DB::table('product_quantity')->where('product_id','=',$product_id)->delete();*/
        
        DB::table('product_attributes')->where('product_id',$product_id)->delete();
        
        unset($input['product_images_old']);
        unset($input['product_header_image_old']);
        
        unset($input['attribute_name']);
        unset($input['attribute_value']);
        unset($input['attribute_val1']);
        unset($input['attribute_val2']);
        unset($input['attribute_val3']);
        unset($input['attribute_val4']);
        unset($input['attribute_val5']);

        
        Product::where('product_id',$product_id)->update($input);
        
        
        $product_que['product_id'] = $product_id;
        
        $attribute_name = $request->attribute_name ?? [];
        $att_val1 = $request->attribute_val1 ?? [];
        $att_val2 = $request->attribute_val2 ?? [];
        $att_val3 = $request->attribute_val3 ?? [];
        $att_val4 = $request->attribute_val4 ?? [];
        $att_val5 = $request->attribute_val5 ?? [];

        $count = count($attribute_name);

        for($i=0;$i<$count;$i++)
        {
            $att_values = array_filter([
                $att_val1[$i] ?? '',
                $att_val2[$i] ?? '',
                $att_val3[$i] ?? '',
                $att_val4[$i] ?? '',
                $att_val5[$i] ?? '',
            ]);
            $att_values = array_values($att_values);
            if(empty($att_values)) continue;
            $product_que['product_id'] = $product_id;
            $product_que['attribute_value'] = json_encode($att_values);
            $product_que['attribute_name'] = $attribute_name[$i];
            $product_que['_token'] = $request->_token;
            DB::table('product_attributes')->insert($product_que);
        }
        
        

        $pro_ats =  DB::table('product_attributes')->where('product_id',$product_id)->get();
        $atts;
        foreach($pro_ats as $dt)
        {
            $at_name = $dt->attribute_name;
            $at_values = $dt->attribute_value;
            $a_vals = json_decode($at_values);
            foreach($a_vals as $dt)
            {
                $at_s[] = $at_name.":".$dt;
            }
            $atts[] = $at_s;
            unset($at_s);
        }
        
        $arrSize = $this->get_combinations($atts);
        
        foreach($arrSize as $dat)
        {
            $inputs['product_id'] = $product_id;
            $inputs['attributes_value'] = json_encode($dat);
            $inputs['product_quantity'] = 0;
            $inputs['rupee_price'] = 0;
            $inputs['dollar_price'] = 0;
            $inputs['euro_price'] = 0;
            $inputs['product_discount'] = 0;
            $inputs['rupee_net_amount'] = 0;
            $inputs['product_weight'] = 0;
            
            $check_data = DB::table('product_quantity')->where('product_id',$product_id)->where('attributes_value',json_encode($dat))->value('product_quantity_id');
            
            $newList[] = json_encode($dat);
            
            
            if($check_data > 0)
            {
                /*echo "Allready";*/
            }
            else
            {
                DB::table('product_quantity')->insert($inputs);
            }   
            
            //DB::table('product_quantity')->insert($inputs);
             
            //DB::table('product_attributes')->where('product_id',$product_id)->delete();  
        }

        $check_new_data = DB::table('product_quantity')->where('product_id',$product_id)->get();
        foreach($check_new_data as $nData)
        {
            $pro_q_id = $nData->product_quantity_id;
            $pro_att = $nData->attributes_value;
            if(in_array($pro_att, $newList)) 
            {
                
            }
            else
            {
                DB::table('product_quantity')->where('product_quantity_id',$pro_q_id)->delete();  
            }
        }
        
        $request->session()->flash('alert-success','Product has been successfully Updated !!');
        return Redirect::route('product_list');
         
    }

    public function productDelete(Request $request , $product_id)
    {
      $data=DB::table('products')->where('product_id','=',$product_id)->value('product_images');
      $backimagess=json_decode($data);
      foreach($backimagess as $proudctimage)
      {
        $fullpath=public_path('upload/product/').$proudctimage;
        File::delete($fullpath);
      }
      $datas=DB::table('products')->where('product_id','=',$product_id)->value('product_header_image');
        $fullpath=public_path('upload/product/').$datas;
        File::delete($fullpath);
     
      DB::table('products')->where('product_id','=',$product_id)->delete();
       DB::table('product_attributes')->where('product_id','=',$product_id)->delete();
      $request->session()->flash('alert-success','Product has been deleted Successfully!!');
      return Redirect::route('product_list');   
    }
    
    public function productQuantityUpdate(Request $request,$product_id)
    {   
         $page_title = "Product Quantity Update - Zouple";
         $data['products'] = DB::table('products')->where('product_id',$product_id)->get();
         $data['pro_attributs'] = DB::table('product_quantity')->where('product_id',$product_id)->get();
         return view('masters.product.product_quantity_update',compact('page_title'),$data);
    }
    
    public function updateProductGSTStore(Request $request)
    {   
        
         $input=$request->all(); 
        $product_id = $request->product_id;    
        DB::table('products')->where('product_id',$product_id)->update($input); 
        $request->session()->flash('alert-success','Proudct GST Data Updated Successfully!!');     
        return redirect()->back(); 
    }
    
    
    
    public function flaceSalesPage(Request $request,$product_id)
    {   
         $page_title = "Product Flace Sale  - Zouple";
         $data['productss'] = DB::table('products')->where('product_id',$product_id)->get();
         $data['flash_data'] = DB::table('flash_sale')->where('flash_sale_id',1)->get();
         $data['pro_attributss'] = DB::table('product_quantity')->where('product_id',$product_id)->get();
         
         return view('masters.product.product_flash_sale',compact('page_title'),$data);
    }
    
    public function flashProductStore(Request $request)
    {
        /*$input = $request->all();*/
        $product_id = $request->product_id;
        $product_quantity_id = $request->product_quantity_id;
        $price = $request->price;
        $dollar = $request->dollar;
        $euro = $request->euro;
        $attributes_value = $request->attributes_value;
        $product_quantity_id = $request->product_quantity_id;
        $count = sizeof($attributes_value);
        for($i=0;$i<$count;$i++)
        {
             $pro_details[$product_quantity_id[$i]] = $attributes_value[$i].",".$price[$i];
             $doll_details[$product_quantity_id[$i]] = $attributes_value[$i].",".$dollar[$i];
             $eu_details[$product_quantity_id[$i]] = $attributes_value[$i].",".$euro[$i];
        }
       $product_details[$i] = json_encode($pro_details);
       $dollar_details[$i] = json_encode($doll_details);
       $euro_details[$i] = json_encode($eu_details);
        
        
        $input['product_prize'] = $product_details[$i];
        $input['dollar_prize'] = $dollar_details[$i];
        $input['euro_prize'] = $euro_details[$i];
        $input['product_id'] = $product_id;
        $input['start_date'] = $request->start_date;
        $input['start_time'] = $request->start_time;
        $input['end_time'] = $request->end_time;
        $input['end_date'] = $request->end_date;
        $input['flash_active'] = $request->flash_active;
        $input['_token'] = $request->_token;
         DB::table('flash_sale')->where('flash_sale_id', 1)->update($input);
        $request->session()->flash('alert-success','Flash Product has been Updated Successfully!!');
        
        
        return Redirect::route('product_list');  
    }
    
    
    public function viewFlashPage(Request $request)
    {   
        $data['view_flash_data'] = DB::table('flash_sale')
            ->join('products', 'products.product_id', '=', 'flash_sale.product_id')
            ->where('flash_sale.flash_active', 'ACTIVE')
            
            ->get();
        $page_title = "Flash product List - Zouple";
        return view('masters.product.view_flash',compact('page_title'),$data);
    }
    
    
    
    
    public function updateProductQuantityStore(Request $request)
    {
        $qty = $request->qty;
        $attributes_value = $request->attributes_value;
        $product_quantity_id = $request->product_quantity_id;
        $discount = $request->discount;
        $weight = $request->weight;
        $price = $request->price;
        $doller = $request->doller;
        $euro = $request->euro;
        $pro_id = DB::table('product_quantity')->where('product_quantity_id',$product_quantity_id)->value('product_id');
        $gst = DB::table('products')->where('product_id',$pro_id)->value('product_gst');
        
        $count = sizeof($qty);
        for($i=0; $i<$count; $i++)
        {
            $update = CurrencyHelper::pricesFromRupee($price[$i], $discount[$i], $gst);
            $update['product_quantity'] = $qty[$i];
            $update['product_discount'] = $discount[$i];
            $update['product_weight'] = $weight[$i];

            DB::table('product_quantity')->where('product_quantity_id',$product_quantity_id[$i])->update($update);
        }
        $request->session()->flash('alert-success','Product Qunatity and Prices has been sucessfully updated.');
        return Redirect::route('product_list');
        
    }
    
    
    
    /////////////////////earthly
    
    public function product_quantity_update_save(Request $request)
    {
        $input = $request->all();
        
        DB::table('products')->where('product_id',$input['product_id'])->update($input);
        $request->session()->flash('alert-success','Product Quantity has been sucessfully updated.
');
        return Redirect::route('product_list');
    }
    
    public function product_inactive_update(Request $request)
    {
        $input = $request->all();
        
        $check = DB::table('products')->where('product_id',$input['product_id'])->update($input);
        
        /*echo $check;*/
        $request->session()->flash('alert-success','Product Status has been sucessfully updated.
');
        return Redirect::route('product_list');
    }
    
    public function product_price_update_save(Request $request)
    {
        $input = $request->all();
        $price = $request->product_price;
        $product_discount = $request->product_discount;
        $net_amount = $price - ($price*$product_discount/100);
        $input['net_amount'] = $net_amount;
        DB::table('products')->where('product_id',$input['product_id'])->update($input);
        $request->session()->flash('alert-success','Product has been sucessfully updated.
');
        return Redirect::route('product_list');
    }


    
    
    /* ------------------------------------ Product Quentity Code End ------------------------------------ */
    
    
    /* Review Code Start */
    
    public function review_information_list(Request $request)
    {
        $data['review_data'] = DB::table('review')
            ->join('products', 'products.product_id', '=', 'review.product_id')
            ->select('review.*', 'products.product_title')
            ->orderBy('review.review_id', 'DESC')
            ->get(); 
         $page_title = "Review List - Zouple";
         return view('masters.product.review_list',compact('page_title'),$data);
    }
    
    public function addReviewInformationPage (Request $request)
    {
        $data['productsData'] = Product::all();
         $page_title = "Add Review Page - Zouple";
         return view('masters.product.addReview',compact('page_title'),$data);
    }
    
    public function reviewInformationStore(Request $request)
    {
        $input = $request->all();
       
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
            }
        
        $input['review_product_image'] = json_encode($product_image);

        DB::table('review')->insert($input);
        $request->session()->flash('alert-success','Review has been sucessfully added.');
        return Redirect::route('addReviewInformation');   
    }
    
    
    public function reviewInformationUpdatePage(Request $request, $review_id)
    {
        $data['reviewDatass'] = DB::table('review')->where('review_id', $review_id)->get(); 
         $page_title = "Review List - Zouple";
        $data['productsData'] = Product::all();
         return view('masters.product.editReviewList',compact('page_title'),$data);
    }
    
    public function reviewInformationEditStore(Request $request)
    {
        $input = $request->all();
        $review_id = $request->review_id;
        if($request->file('user_profile')!='')
        {
            $user_profile = DB::table('review')->where('review_id','=',$review_id)->value('user_profile');
            $fullpath=public_path('upload/review/').$user_profile;
            File::delete($fullpath);
            $file=$request->file('user_profile');
            $filename=$file->getClientOriginalName();
            $imgname = $filename;
            $input['user_profile']= $imgname;       
            $destinationPath=public_path('upload/review/');       
            $request->file('user_profile')->move($destinationPath, $imgname);
        } 
        
        if($request->file('review_product_image')!='')
        { 
          $product_images = DB::table('review')->where('review_id','=',$review_id)->value('review_product_image');
          if($product_images != "")
          {
            $backimages=json_decode($product_images);
              foreach($backimages as $image)
              {
                $fullpath1=public_path('upload/review/').$image;
                File::delete($fullpath1);
              }
          }
          
            foreach($request->file('review_product_image') as $image)
            {
                $name= $image->getClientOriginalName();
                $destinationPath=public_path('upload/review/');       
                $image->move($destinationPath,$name);  
                $product_image[] = $name;
            }
            $input['review_product_image'] = json_encode($product_image);
        }
        
        
        DB::table('review')->where('review_id','=',$review_id)->update($input);
        
        $request->session()->flash('alert-success','Review has been successfully Updated !!');
        return Redirect::route('review_information');
         
    }
    

    public function review_status_update_save(Request $request)
    {
        $input = $request->all();
        DB::table('review')->where('review_id',$input['review_id'])->update($input);
        $request->session()->flash('alert-success','Review Updated !!');
        return Redirect::route('review_information');
    }

    public function reviewDelete(Request $request , $review_id)
    {
      $input=$request->all();

       $user_profile = DB::table('review')->where('review_id','=',$review_id)->value('user_profile');
       $fullpath=public_path('upload/review/').$user_profile;
       File::delete($fullpath);

       $product_images = DB::table('review')->where('review_id','=',$review_id)->value('review_product_image');
       if($product_images != "")
          {
            $backimages=json_decode($product_images);
              foreach($backimages as $image)
              {
                $fullpath1=public_path('upload/review/').$image;
                File::delete($fullpath1);
              }
          }

      DB::table('review')->where('review_id',$review_id)->delete();
      $request->session()->flash('alert-success','Review has been deleted Successfully!!');
      return Redirect::route('review_information');   
    }
    
    
    /* Review Code End */





  /* Export Code */

    public function export() 
    {
        return Excel::download(new UsersExport, 'Products.xlsx');
    }

    public function filterProductPage(Request $request)
    {
        $data['cate_data'] = DB::table('categorys')->where('parent_id',0)->where('is_active','ACTIVE')->get();
       
        $categories = Category::where('parent_id', '=', 0)->where('is_active','ACTIVE')->get();
        $data['attribute_data'] = DB::table('attribute')->orderby('attribute_id', 'asc')->get();

        $data['vendors_data'] = DB::table('vendors')->orderby('vendor_id', 'asc')->get();

       
       $page_title = "Filter Product - Zouple";
       return view('masters.product.filter_product',compact('page_title','categories'),$data);
    }

    public function filterProductStore(Request $request)
    {
        $page_title = "Filter Product - Zouple";
       $category = $request->category;

       foreach($category as $cat)
        {
             $pro_id = Product::where('category', 'LIKE', '%"'.$cat.'"%')
                                ->orderBy('product_id', 'desc')
                                ->value('product_id');

                $product_ids[]= $pro_id;

        }

                $pro_ids = array_unique($product_ids);
                
                $data['product_data'] = Product::whereIn('product_id',$pro_ids)->get(); 

                return view('masters.product.product_list',compact('page_title'),$data);

    }

    public function exportSelctedProductPage(Request $request)
    {
        $pros_id = $request->pro_ids;
       
         return Excel::download(new UsersExport, 'products.xlsx');
    }
    
}
