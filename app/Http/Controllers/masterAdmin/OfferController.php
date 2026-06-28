<?php

namespace App\Http\Controllers\masterAdmin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth,Redirect,View,File,Config,Image;
use App\Offerbanner;
use Validator;
use DB;
use App\Pincode;
use App\Product;
use App\Services\AdminRecycleBinService;
use App\Services\AdminMediaService;
use Schema;


class OfferController extends Controller
{
    public function offer_list()
    {
    	$data['offerbanner_data'] = Offerbanner::all();
        $page_title = "Banner List - Zouple";
        return view('masters.offerbanner.offerbanner',compact('page_title'), $data);
    }

    public function add_offerbanner()
    {
       $page_title = "Add Pincode - Zouple";
       return view('masters.offerbanner.add_offerbanner',compact('page_title'));
    }

    public function offerbanner_save(Request $request)
    {
       $input = $request->all();
      if($request->file('image')!='')
      {
          $upload = app(AdminMediaService::class)->uploadImage($request->file('image'), 'offer-banners', 'offerbanner');
          $input['image']= $upload['path'];
          if (Schema::hasColumn('offerbanners', 'image_public_id')) {
              $input['image_public_id'] = $upload['public_id'];
          }
         
      } 
           Offerbanner::insert($input);
            $request->session()->flash('alert-success','Advertisement Banner has been sucessfully added.');
            return Redirect::route('offer');
    }

    public function offerbanner_edit(Request $request,$offerbanners_id)
    {
      $data['offerbanner_data'] = Offerbanner::where('offerbanners_id',$offerbanners_id)->get();
      $page_title = "Edit Banner - Zouple";
      return view('masters.offerbanner.edit_offerbanner',compact('page_title'),$data);
    }

    public function offerbanner_update_save(Request $request)
    {
        $input = $request->all();
        $offerbanners_id = $request->offerbanners_id;
        
      if($request->file('image')!='')
      {
          $data=DB::table('offerbanners')->where('offerbanners_id','=',$offerbanners_id)->value('image');
          $publicId = Schema::hasColumn('offerbanners', 'image_public_id')
              ? DB::table('offerbanners')->where('offerbanners_id', $offerbanners_id)->value('image_public_id')
              : null;
          app(AdminMediaService::class)->deleteMedia($data, 'offerbanner', $publicId, 'image');
          $upload = app(AdminMediaService::class)->uploadImage($request->file('image'), 'offer-banners', 'offerbanner');
          $input['image']= $upload['path'];
          if (Schema::hasColumn('offerbanners', 'image_public_id')) {
              $input['image_public_id'] = $upload['public_id'];
          }

      } 
      
       Offerbanner::where('offerbanners_id',$offerbanners_id)->update($input);

        $request->session()->flash('alert-success','Advertisement Banner has been sucessfully updated.');
        return Redirect::route('offer');
    }

    public function offerbannerDelete(Request $request , $offerbanners_id)
    {
      AdminRecycleBinService::softDelete('offerbanners', $offerbanners_id);
      $request->session()->flash('alert-success','Advertisement banner moved to Recycle Bin.');
      return Redirect::route('offer');   
    }
}
