<?php

namespace App\Http\Controllers\masterAdmin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth,Redirect,View,File,Config,Image,Session;
use Validator;
use DB;
use App\Services\AdminRecycleBinService;
use App\Services\AdminMediaService;
use Schema;

class BannerController extends Controller
{
    public function bannerPage(Request $request)
    {
       $page_title = "Banner List - Zouple";

       $data['banner_data'] = AdminRecycleBinService::activeTable('banner')->orderby('banner_id', 'Desc')->get();
       return view('masters.banner.banner',compact('page_title'), $data);
    }
    
    public function bannerUpdate(Request $request,$banner_id)
  {
      $data['banner_datas'] =  AdminRecycleBinService::activeTable('banner')->where('banner_id', 1)->get();
      $page_title = "Edit Banner - Zouple";
      return view('masters.banner.edit_banner',compact('page_title'), $data);
  }
    
    public function bannerEditStore(Request $request)
    {
      $input = $request->all();
   

     
      if($request->file('image')!='')
      {
          $data=DB::table('banner')->where('banner_id', 1)->value('image');
          $this->deleteSingleImage('banner', 'banner_id', 1, 'banner', $data);
          $upload = app(AdminMediaService::class)->uploadImage($request->file('image'), 'banners', 'banner');
          $input['image']= $upload['path'];
          $this->setPublicId($input, 'banner', $upload['public_id']);

      } 
      else
      {
          unset($input['image']);
      }
      
       DB::table('banner')->where('banner_id', 1)->update($input); 
        $request->session()->flash('alert-success','Banner has been sucessfully updated.');
        return Redirect::route('banner');
    

    }
    
    
    /* Flash Banner */
    
    public function flashBannerList(Request $request)
    {
       $page_title = "Flash Banner List - Zouple";

       $data['flash_banner_data'] = DB::table('flash_banner')->orderby('flash_banner_id', 'Desc')->get();
       return view('masters.offerbanner.flash_sale_banner',compact('page_title'), $data);
    }
    
    public function flashBannerUpdate(Request $request,$flash_banner_id)
  {
      $data['flash_banner_datass'] =  DB::table('flash_banner')->where('flash_banner_id', 1)->get();
      $page_title = "Edit Flash Banner - Zouple";
      return view('masters.offerbanner.edit_flash_sale_banner',compact('page_title'), $data);
  }
    
    public function flashBannerEditStore(Request $request)
    {
      $input = $request->all();
    

     
      if($request->file('image')!='')
      {
          $data=DB::table('flash_banner')->where('flash_banner_id', 1)->value('image');
          $this->deleteSingleImage('flash_banner', 'flash_banner_id', 1, 'flashbanner', $data);
          $upload = app(AdminMediaService::class)->uploadImage($request->file('image'), 'flash-banners', 'flashbanner');
          $input['image']= $upload['path'];
          $this->setPublicId($input, 'flash_banner', $upload['public_id']);

      } 
      else
      {
          unset($input['image']);
      }
      
       DB::table('flash_banner')->where('flash_banner_id', 1)->update($input); 
        $request->session()->flash('alert-success','Flash Banner has been sucessfully updated.');
        return Redirect::route('flashBanner');
    

    }
    
     /* -------------------------------------------------------------- Login Banner  --------------------------------------------------------------*/
    
    public function loginBannerList(Request $request)
    {
       $page_title = "Login Banner List - Zouple";

       $data['login_banner_data'] = DB::table('login_banner')->orderby('login_banner_id', 'Desc')->get();
       return view('masters.loginbanner.loginbanner',compact('page_title'), $data);
    }
    
    public function loginBannerUpdatePage(Request $request,$login_banner_id)
  {
      $data['login_banner_datass'] =  DB::table('login_banner')->where('login_banner_id', $login_banner_id)->get();
      $page_title = "Edit Login Banner - Zouple";
      return view('masters.loginbanner.edit_loginbanner',compact('page_title'), $data);
  }
    
    public function loginBannerEditStore(Request $request)
    {
      $input = $request->all();
      $login_banner_id = $request->login_banner_id;

     
      if($request->file('image')!='')
      {
          $data=DB::table('login_banner')->where('login_banner_id', $login_banner_id)->value('image');
          $this->deleteSingleImage('login_banner', 'login_banner_id', $login_banner_id, 'loginbanner', $data);
          $upload = app(AdminMediaService::class)->uploadImage($request->file('image'), 'login-banners', 'loginbanner');
          $input['image']= $upload['path'];
          $this->setPublicId($input, 'login_banner', $upload['public_id']);

      } 
      else
      {
          unset($input['image']);
      }
      
       DB::table('login_banner')->where('login_banner_id', $login_banner_id)->update($input); 
        $request->session()->flash('alert-success','Login Banner has been sucessfully updated.');
        return Redirect::route('loginBanner');
    

    }
    
    
    /* Get Notification Code Start */
        
        
        public function getNotificationList(Request $request)
        {
           $page_title = "Get Notification List - Zouple";
           $data['noti_data'] = AdminRecycleBinService::activeTable('getnotification')
           ->join('products', 'products.product_id', '=', 'getnotification.product_id')
           ->whereNull('products.deleted_at')
           ->orderby('getnotification.notifi_id', 'Desc')->get();
           return view('masters.getnotification.getnotification',compact('page_title'), $data);
        }
        
        public function getNotificationDeleteFormat(Request $request, $notifi_id)
        {
            AdminRecycleBinService::softDelete('notifications', $notifi_id);
            $request->session()->flash('alert-success','Notification moved to Recycle Bin.');
            return Redirect::route('getNotification');
        }
        
        
        
        
        
         /* --------------------------------------------------------------  Customer Shirt  --------------------------------------------------------------*/
    
    public function customerShirtList(Request $request)
    {
       $page_title = "Customer Shirt List - Zouple";

       $data['customer_shirt_data'] = DB::table('customer_shirt')->orderby('customer_shirt_id', 'asc')->get();
       return view('masters.customershirt.customer_shirt',compact('page_title'), $data);
    }
    
    public function customerShirtUpdatePage(Request $request,$customer_shirt_id)
  {
      $data['customer_shirt_datass'] =  DB::table('customer_shirt')->where('customer_shirt_id', $customer_shirt_id)->get();
      $page_title = "Edit Login Banner - Zouple";
      return view('masters.customershirt.edit_customer_shirt',compact('page_title'), $data);
  }
    
    public function customerShirtEditStore(Request $request)
    {
      $input = $request->all();
      $customer_shirt_id = $request->customer_shirt_id;

     
      if($request->file('image')!='')
      {
          $data=DB::table('customer_shirt')->where('customer_shirt_id', $customer_shirt_id)->value('image');
          $this->deleteSingleImage('customer_shirt', 'customer_shirt_id', $customer_shirt_id, 'customershirt', $data);
          $upload = app(AdminMediaService::class)->uploadImage($request->file('image'), 'customer-shirts', 'customershirt');
          $input['image']= $upload['path'];
          $this->setPublicId($input, 'customer_shirt', $upload['public_id']);

      } 
      else
      {
          unset($input['image']);
      }
      
       DB::table('customer_shirt')->where('customer_shirt_id', $customer_shirt_id)->update($input); 
        $request->session()->flash('alert-success','Customer Shirt Banner has been sucessfully updated.');
        return Redirect::route('customerShirt');
    

    }

    private function setPublicId(array &$input, $table, $publicId)
    {
        if (Schema::hasColumn($table, 'image_public_id')) {
            $input['image_public_id'] = $publicId;
        }
    }

    private function deleteSingleImage($table, $keyColumn, $keyValue, $localFolder, $image)
    {
        $publicId = Schema::hasColumn($table, 'image_public_id')
            ? DB::table($table)->where($keyColumn, $keyValue)->value('image_public_id')
            : null;

        app(AdminMediaService::class)->deleteMedia($image, $localFolder, $publicId, 'image');
    }

    
}
