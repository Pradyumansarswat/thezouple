<?php

namespace App\Http\Middleware;

use Closure;
use View;
use DB;
use App\Otherpage;
use App\Category;
use App\Helper\CurrencyHelper;
use Auth;
use config;

class webMiddleware
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
        try {
        //Mail Setting 
           /*$mail = DB::table('mail_settings')->where('slug','customer-care')->first();
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
        */
        
        
        
        
        /* Category */
        $categories = Category::where('parent_id', '=', 0)->where('is_active','ACTIVE')->get(); 
        View::share('categories', $categories);
        
        /*Attributes List */
        $attributeName = DB::table('attribute')->get();
        foreach($attributeName as $data)
        {
            $attributeName[$data->attribute_id] = $data->name;   
        }
        if(!$attributeName->isEmpty())
        {
           View::share('attributeName', $attributeName); 
        }
        
        
        
        
        
      /*  $others =  Otherpage::where('parent_id', '=', 0)->where('is_active','ACTIVE')->get();
        $allothers = Otherpage::pluck('page_title','other_id')->where('is_active','ACTIVE')->all();
       
        View::share('others', $others);
        View::share('allothers', $allothers);*/
        
        $currency = CurrencyHelper::normalizeSessionCurrency(session('currency'));
        session(['currency' => $currency]);
        View::share('currencySession', $currency);
        

        
        
        $ip = $request->ip();
        $wish_item = 0;
        if(isset(Auth::user()->id))
        {   
            $mywishList = DB::table('wishlists')->where('user_id',Auth::user()->id)->pluck('product_id')->toArray();
             $cart_item = DB::table('carts')->where('ip_address',$ip)->orWhere ('user_id', Auth::user()->id)->count('cart_id');
            $wish_item = DB::table('wishlists')->where('user_id',Auth::user()->id)->count('wishlist_id');
            
             $wishList = DB::table('wishlists')
                            ->join('products','products.product_id','wishlists.product_id')
                            ->where('user_id',Auth::user()->id)->get();
            
            
            View::share('mywishList', $mywishList);
            View::share('wishList', $wishList);
            View::share('cart_item', $cart_item);
            View::share('wish_item',  $wish_item);
        }
        else
        {
             View::share('wish_item', $wish_item);
            $cart_item = DB::table('carts')->where('ip_address',$ip)->count('cart_id');
            View::share('cart_item', $cart_item);
        }

        
          
        
        // Category Name //
        
        $cateName = DB::table('categorys')->get();
        foreach($cateName as $data)
        {
            $categoryName[$data->category_id] = $data->title;
        }
        if(!$cateName->isEmpty())
        {
           View::share('cateName', $categoryName); 
        }
        
        $cats = DB::table('categorys')->where('parent_id',0)->get();
        if(!$cats->isEmpty())
        {
           View::share('cats', $cats); 
        }
        // Accessoires Name & image//
        
        
        
        //Product Details//
        
        
        
        // Site Information Code Start 
        
        $siteinfo = DB::table('siteinfos')->get(); 
        View::share('siteinformation', $siteinfo);
        
        
        /* Banenr Code Start */
        
        $banner = DB::table('banner')->get(); 
        View::share('banner', $banner);
        
        /* Login Banenr Code Start */
        
        $loginban = DB::table('login_banner')->where('login_banner_id', 1)->get(); 
        View::share('loginban', $loginban);
        
        /* Signp Banenr Code Start */
        
        $signupbann = DB::table('login_banner')->where('login_banner_id', 2)->get(); 
        View::share('signupbann', $signupbann);
        
        
         //Customer Coupon User  Details//
        
        $user = DB::table('users')->get();
        foreach($user as $data)
        {
            $userTitle[$data->id] = $data->name;
        }
        if(!$user->isEmpty())
        {
            View::share('userTitle', $userTitle);
        }
        
        } catch (\Exception $e) {
            // Database not available - application will continue without database-driven data
        }
        
        return $next($request);
    }
}
