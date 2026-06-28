<?php

namespace App\Http\Controllers\masterAdmin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use App\Vendor;
use Auth,Redirect,route,Session,View,Validator,Config,Hash;
use App\Services\AdminRecycleBinService;

class VendorController extends Controller
{
    public function vendorList(REQUEST $request)
    {
        $data['vendor_data'] = Vendor::all();
        $page_title = "Zouple";
       return view('masters.vendor.vendor',compact('page_title'), $data);
    }
    
    public function addVendorPage(REQUEST $request)
    {
        $page_title = "Zouple";
       return view('masters.vendor.add_vendor',compact('page_title'));
    }

    public function vendorStore(Request $request)
    {
       $input = $request->all();
        Vendor::insert($input);

        $request->session()->flash('alert-success','Vendor has been sucessfully added.');
        return Redirect::route('addVendor');
    }
    
    public function vendorUpdatePage(REQUEST $request, $vendor_id)
    {
        $data['vendor_datas'] = Vendor::where('vendor_id', $vendor_id)->get();
        $page_title = "Zouple";
       return view('masters.vendor.edit_vendor',compact('page_title'), $data);
    }

    public function vendorEditStore(Request $request)
    {
       $input = $request->all();
       $vendor_id = $request->vendor_id;
        Vendor::where('vendor_id','=',$vendor_id)->update($input);

        $request->session()->flash('alert-success','Vendor has been sucessfully updated.');
        return Redirect::route('vendor');
    }

     public function vendorDeleteformat(Request $request,$vendor_id)
        {
            AdminRecycleBinService::softDelete('vendors', $vendor_id);
            $request->session()->flash('alert-success','Vendor moved to Recycle Bin.');
            return Redirect::route('vendor');
        }
}
