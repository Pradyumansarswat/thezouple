<?php

namespace App\Helper;

use Html;
use File;
use Auth,View,Redirect,Response,Hash,Validator,DB;
use App\User;
use App\Product;
use App\Category;
use App\Blog;
use Session;
use Mail;

use App\AttributValue;
use App\ShirtCategory;
use App\ShirtAttribut;

class BasicHelper {
    
    //Attribut Value 
    public static function getattributvalueSlug($model, $value) {
        $slug = \Illuminate\Support\Str::slug(trim($value));
        $slugCount = count($model->whereRaw("slug REGEXP '^{$slug}(-[0-9]+)?$' and element_value_id != '{$model->element_value_id}'")->get());

        return ($slugCount > 0) ? "{$slug}-{$slugCount}" : $slug;
    }
    
    //Shirt Category Value 
    public static function getshirtcategorySlug($model, $value) {
        $slug = \Illuminate\Support\Str::slug(trim($value));
        $slugCount = count($model->whereRaw("slug REGEXP '^{$slug}(-[0-9]+)?$' and febric_id != '{$model->febric_id}'")->get());

        return ($slugCount > 0) ? "{$slug}-{$slugCount}" : $slug;
    }
    
    //Shirt Attribut Value 
    public static function getshirtattributSlug($model, $value) {
        $slug = \Illuminate\Support\Str::slug(trim($value));
        $slugCount = count($model->whereRaw("slug REGEXP '^{$slug}(-[0-9]+)?$' and element_id != '{$model->element_id}'")->get());

        return ($slugCount > 0) ? "{$slug}-{$slugCount}" : $slug;
    }
    
    
    // Unique Slug for Blog
     public static function getblogSlug($model, $value) {
        $slug = \Illuminate\Support\Str::slug(trim($value));
        $slugCount = count($model->whereRaw("slug REGEXP '^{$slug}(-[0-9]+)?$' and blog_id != '{$model->blog_id}'")->get());

        return ($slugCount > 0) ? "{$slug}-{$slugCount}" : $slug;
    }
    
    
    // Unique Slug for Category
     public static function getUniqueSlug($model, $value) {
        $slug = \Illuminate\Support\Str::slug(trim($value));
        $slugCount = count($model->whereRaw("slug REGEXP '^{$slug}(-[0-9]+)?$' and category_id != '{$model->category_id}'")->get());

        return ($slugCount > 0) ? "{$slug}-{$slugCount}" : $slug;
    }
    
    /* Attribute Slug Code Start */
    public static function getattributeSlug($model, $value) {
        $slug = \Illuminate\Support\Str::slug(trim($value));
        $slugCount = count($model->whereRaw("slug REGEXP '^{$slug}(-[0-9]+)?$' and attribute_id != '{$model->attribute_id}'")->get());

        return ($slugCount > 0) ? "{$slug}-{$slugCount}" : $slug;
    }
    
    
    public static function getAccessoriesSlug($model, $value) {
        $slug = \Illuminate\Support\Str::slug(trim($value));
        $slugCount = count($model->whereRaw("slug REGEXP '^{$slug}(-[0-9]+)?$' and access_id != '{$model->access_id}'")->get());

        return ($slugCount > 0) ? "{$slug}-{$slugCount}" : $slug;
    } 
    
    public static function getproductSlug($model, $value) {
        $slug = \Illuminate\Support\Str::slug(trim($value));
        $slugCount = count($model->whereRaw("slug REGEXP '^{$slug}(-[0-9]+)?$' and product_id != '{$model->product_id}'")->get());

        return ($slugCount > 0) ? "{$slug}-{$slugCount}" : $slug;
    }
    
    public static function getpageSlug($model, $value) {
        $slug = \Illuminate\Support\Str::slug(trim($value));
        $slugCount = count($model->whereRaw("slug REGEXP '^{$slug}(-[0-9]+)?$' and other_id != '{$model->other_id}'")->get());

        return ($slugCount > 0) ? "{$slug}-{$slugCount}" : $slug;
    }
    
    public static function getemailtemplateSlug($model, $value) {
        $slug = \Illuminate\Support\Str::slug(trim($value));
        $slugCount = count($model->whereRaw("slug REGEXP '^{$slug}(-[0-9]+)?$' and emailtemplate_id != '{$model->emailtemplate_id}'")->get());

        return ($slugCount > 0) ? "{$slug}-{$slugCount}" : $slug;
    }
    
    public static function getwishlist($user_id)
    {
         $data['wish_list'] = DB::table('wishlists')
                             ->join('products', 'products.product_id', '=', 'wishlists.product_id')
                            ->where('user_id',$user_id)->get();
        return $data;
    }
    
    public static function sendOrderMail($data)
    {
         $emailActions		=	DB::table('email_actions')->where('action','=','thanks_for_registration')->get()->toArray();
         $emailTemplates		=	DB::table('email_templates')->where('action','=','thanks_for_registration')->get(array('name','subject','action','body'))->toArray();
        foreach($emailActions as $key => $st)
        {
            $email_option = $st->options;
        }
        $fullName = "Mahesh";
        $email = "jangid995@gmail.com";
        
        $token = User::where('email',$email)->value('_token');
        $user_data['_token'] = $token;
        $url = WEBSITE_URL."/verify_email?token=".$token;
        
        $link = "<a href=$url> Click Here </a>";
        $cons = explode(',',$email_option);
        $constants = array();
        foreach($cons as $key=>$val){
            $constants[] = '{'.$val.'}';
        }
        foreach($emailTemplates as $key => $st1)
        {
            $subject = $st1->subject;
            $body = $st1->body;
        }
        $rep_Array 	=  array($fullName,$email,$link);
        $messageBody		=  str_replace($constants, $rep_Array, $body);
        $subject = $subject;
        $data['msg']=$messageBody;
        $data['subject']=$subject;
        $data['email']=$email;
        $data=Mail::send([],[],  function ($message)  use($data) 
        {
            $message->to($data['email'])->subject($data['subject'])
                ->setBody($data['msg'], 'text/html'); 
        });
            
        return "send";
    }
   

    
    
}
?>