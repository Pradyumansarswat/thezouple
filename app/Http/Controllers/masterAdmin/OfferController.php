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
          $file=$request->file('image');
          $filename=$file->getClientOriginalName();
          $imgname = uniqid().$filename;
          
          $input['image']= $imgname;       
          $destinationPath=public_path('upload/offerbanner/');       
          $request->file('image')->move($destinationPath, $imgname);
         
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
          $fullpath=public_path('upload/offerbanner/').$data;
          File::delete($fullpath);
          
          $file=$request->file('image');
          $filename=$file->getClientOriginalName();
          $imgname = uniqid().$filename;

          $input['image']= $imgname;       
          $destinationPath=public_path('upload/offerbanner/');       
          $request->file('image')->move($destinationPath, $imgname);

      } 
      
       Offerbanner::where('offerbanners_id',$offerbanners_id)->update($input);

        $request->session()->flash('alert-success','Advertisement Banner has been sucessfully updated.');
        return Redirect::route('offer');
    }

    public function offerbannerDelete(Request $request , $offerbanners_id)
    {
      $data=DB::table('offerbanners')->where('offerbanners_id','=',$offerbanners_id)->value('image');
      $fullpath=public_path('upload/offerbanner/').$data;
      File::delete($fullpath);
      $m = Offerbanner::where('offerbanners_id','=',$offerbanners_id)->delete();
      $request->session()->flash('alert-success','Advertisement Banner has been sucessfully deleted.');
      return Redirect::route('offer');   
    }
}
