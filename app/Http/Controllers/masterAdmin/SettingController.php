<?php

namespace App\Http\Controllers\masterAdmin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth,Redirect,route,Session,View,Validator,Config,Hash;
use DB;

use App\Pincode;
use App\User;
use App\Siteinfo;
use App\Imports\pinImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

class SettingController extends Controller
{
    public function mail_list(Request $request)
     {
        $datas['mail_datas'] = DB::table('mail_settings')->orderby('id','desc')->get();
          $page_title = "Mail Setting List - Zouple";
          return view('masters.setting.mail_setting_list',compact('page_title'),$datas);
     }
     public function mail_update(Request $request, $id)
    {
        $datas['mail_data'] = DB::table('mail_settings')->where('id',$id)->get();
          $page_title = "Mail Setting - Zouple";
          return view('masters.setting.mail_setting',compact('page_title'),$datas);
    }
    public function mail_update_save(Request $request)
    {
        $input = $request->all();
        $id = $request->id;
        DB::table('mail_settings')->where('id',$id)->update($input);
        $request->session()->flash('alert-success','Mail Setting has been sucessfully updated.');
        return Redirect::route('mail_page');
    }
    
    /* Pincode */
    public function pincode_list()
    {
        $data['code_data'] = DB::table('pincodes')
                    
                      ->get();
        $page_title = "Pincode - Zouple";
        return view('masters.setting.pincode',compact('page_title'),$data);
    }
    
    public function add_pincode()
    {
    
       $page_title = "Add Pincode - Zouple";
       return view('masters.setting.add_pincode',compact('page_title'));
    }
    
    public function state_mange(Request $request,$id)
    {
        $state = DB::table('states')->where('country_id',$id)->pluck("state_name","id");
       
       return json_encode($state);
    }
    public function city_mange(Request $request,$id)
    {
        $state = DB::table('cities')->where('state_id',$id)->pluck("city_name","id");
       
       return json_encode($state);
    }
    
    public function pincode_store(Request $request)
    {
       $input  = $request->all();
        
        $this->validate($request , array
         (    
             'pincode' =>'unique:pincodes|required|digits:6|numeric'
         ));     
        
       Pincode::insert($input);
        $request->session()->flash('alert-success','Pincode Added !!');
        return Redirect::route('pincode');
    }

    public function pinUpdate(Request $request,$pincode_id)
    {
      $data['pin_data'] = Pincode::where('pincode_id',$pincode_id)->get();
      /*$data['country_list'] = DB::table('countries')->get();*/
      $page_title = "Edit Pin - Zouple";
      return view('masters.setting.edit_pincode',compact('page_title'),$data);
    }

    public function pincode_update_save(Request $request)
    {
        $input = $request->all();
         $this->validate($request , array
         (    
             'pincode' =>'unique:pincodes|required|digits:6|numeric'
         ));     
        
        $pincode_id = $request->pincode_id;
       Pincode::where('pincode_id',$pincode_id)->update($input);

        $request->session()->flash('alert-success','Pin Code has been sucessfully updated.');
        return Redirect::route('pincode');
    }

    public function pinDelete(Request $request , $pincode_id)
    {
      $m = Pincode::where('pincode_id','=',$pincode_id)->delete();
      $request->session()->flash('alert-success','Pincode has been sucessfully deleted.');
      return Redirect::route('pincode');   
    }
    
    public function import_pincode(Request $request)
    {
        $page_title = "Import Pincode - Zouple";
        return view('masters.setting.import_pincode',compact('page_title'));
    }
    
    public function import_pincodeStore(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|file|mimes:csv,txt,xlsx,xls|max:4096',
        ]);

        try {
            Excel::import(new pinImport, $request->file('file'));
            $request->session()->flash('alert-success','Pincode Import !!');
        } catch (\Exception $exception) {
            Log::error('Pincode import failed', ['error' => $exception->getMessage()]);
            $request->session()->flash('alert-danger','Pincode import failed. Please check the file format and try again.');
        }

        return Redirect::route('pincode');
    }

    /* Change Password */

    public function change_admin_panel_list()
    {
       $data['change_password_data'] = User::where('user_role','ADMIN')->get();
       $page_title = "Change Password List - Zouple";
       return view('masters.user.change_password_list',compact('page_title'),$data);
    } 

    public function chanegpasswordUpdate(Request $request,$id)
    {
      $data['password_data'] = User::where('id',$id)->get();
      $page_title = "Edit Change Password - Zouple";
      return view('masters.user.edit_change_password',compact('page_title'),$data);
    }

    public function change_password_update_save(Request $request)
    {
      $id = $request->id;
      $old_pass = Auth::guard('admin')->user()->password;
      $old_password = $request->oldpassword;
        
        $this->validate($request , array
         (    
             'password' => 'required|string|min:8|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/'
         ));  
      if((Hash::check($old_password , $old_pass)))
      {
          
        $input['password']=Hash::make($request['password']);
         $input['email']=$request->email; 
         User::where('id','=',$id)->update($input); 
          
          Auth::guard('admin')->logout();
          $request->session()->invalidate();
          $request->session()->regenerateToken();
          $request->session()->flash('alert-danger','Password has been sucessfully updated.'); 
          return redirect('masterAdmin');
      }
      else
      {
         
          $request->session()->flash('alert-danger','Old Password does not match !!');
          return Redirect::route('change_admin_panel');
          
      }
  }

    /* Change Password */

    /* Site Information */

      public function site_information_list()
    {
       $data['site_infor_data'] = Siteinfo::all();
       $page_title = "Site Information List - Zouple";
       return view('masters.setting.siteinformatio_list',compact('page_title'),$data);
    } 


    public function siteinformationUpdate_format(Request $request)
    {
      $data['siteinfor_data'] = Siteinfo::where('siteinfo_id',1)->get();

      /*$data['country_data'] = DB::table('countries')->orderby('id', 'asc')->get();*/
      
      $page_title = "Edit Site Information - Zouple";
      return view('masters.setting.edit_siteinformation',compact('page_title'),$data);
    }

    public function country_manges(Request $request,$id)
    {
        $state = DB::table('states')->where('country_id',$id)->pluck("state_name","id");
       
       return json_encode($state);
    }

    public function state_manges(Request $request,$id)
    {
        $state = DB::table('cities')->where('state_id',$id)->pluck("city_name","id");
       
       return json_encode($state);
    }

    public function site_information_save_update_format(Request $request)
    {
        $this->validate($request, [
            'meta_email' => 'required|email',
            'whatsapp_number' => 'nullable|string|max:30',
            'recycle_cleanup_days' => 'required|in:30,60,90',
        ]);
        $input = $request->all();
       Siteinfo::where('siteinfo_id',1)->update($input);

        $request->session()->flash('alert-success','Site Information Code has been sucessfully updated.');
        return Redirect::route('siteinformationUpdate');
    }

    /* Site Information */

    public function payment_settings(Request $request)
    {
        $page_title = "Payment Gateway Settings - Zouple";
        $gatewayStatus = [
            [
                'name' => 'Cash on Delivery',
                'status' => 'Enabled',
                'mode' => 'Manual',
                'message' => 'Customers can place COD orders without gateway credentials.',
                'required' => [],
            ],
            [
                'name' => 'Paytm',
                'status' => (config('paytm.merchant_id') && config('paytm.merchant_key') && config('paytm.merchant_website')) ? 'Configured' : 'Missing credentials',
                'mode' => config('paytm.env', 'local'),
                'message' => 'Add Paytm credentials in .env before using live Paytm payments.',
                'required' => [
                    'PAYTM_ENVIRONMENT',
                    'PAYTM_MERCHANT_ID',
                    'PAYTM_MERCHANT_KEY',
                    'PAYTM_MERCHANT_WEBSITE',
                    'PAYTM_CHANNEL',
                    'PAYTM_INDUSTRY_TYPE',
                ],
            ],
            [
                'name' => 'PayPal',
                'status' => (config('paypal.client_id') && config('paypal.secret')) ? 'Configured' : 'Missing credentials',
                'mode' => config('paypal.settings.mode', 'live'),
                'message' => 'Add PayPal credentials in .env before using live PayPal payments.',
                'required' => [
                    'PAYPAL_CLIENT_ID',
                    'PAYPAL_SECRET',
                    'PAYPAL_MODE',
                ],
            ],
        ];

        return view('masters.setting.payment_settings', compact('page_title', 'gatewayStatus'));
    }
}
