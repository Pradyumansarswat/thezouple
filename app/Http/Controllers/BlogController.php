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
use Session;
use Mail;
use App\Helper\BasicHelper;
use App\Services\AdminRecycleBinService;

class BlogController extends Controller
{
    public function blogPage(Request $request)
    {
    	$page_title = "Blog - Zouple";


    	$data['blog_data'] = AdminRecycleBinService::activeTable('blog')->orderby('blog_id', 'desc')->paginate(5);

    	return view('front.blog.blog',compact('page_title'), $data); 
    }

    public function blogShowPage(Request $request, $slug)
    {
    	$blog = AdminRecycleBinService::activeTable('blog')->where('slug', $slug)->first();
        abort_if(!$blog, 404);
    	$title = $blog->heading;
    	$page_title =  $title;
        $meta_description = $blog->description;
        $meta_keyword = $title;

    	$data['blogs_datas'] = AdminRecycleBinService::activeTable('blog')->where('slug', $slug)->get();

        if(isset(Auth::user()->id))
        {
            $data['wishs_lists'] = DB::table('wishlists')->join('products', 'products.product_id', '=', 'wishlists.product_id')->join('product_quantity', 'product_quantity.product_quantity_id', '=', 'wishlists.product_qty_id')->orderBy('products.product_id', 'asc')->where('wishlists.user_id',Auth::user()->id)->get();
        }
    	

    	return view('front.blog.blog_details',compact('page_title', 'title', 'meta_description', 'meta_keyword'), $data); 
    }
}
