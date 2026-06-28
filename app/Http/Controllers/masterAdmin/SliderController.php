<?php

namespace App\Http\Controllers\masterAdmin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth,Redirect,View,File,Config,Image;
use App\Slider;
use App\Services\AdminRecycleBinService;
use App\Services\AdminMediaService;
use Validator;
use DB;
use Schema;


class SliderController extends Controller
{
    public $model	=	'Slider';
    public function __construct() {
        View::share('modelName',$this->model);
    }
    
   public function slider_list(Request $request)
   {
        $users['slide_data'] = Slider::all();
        $page_title = "Slider List - Zouple";
       return view('masters.slider.slider',compact('page_title'),$users);
   }
    
   public function add_slide(Request $request)
   {
       
       $page_title = "Add Slider - Zouple";
       return view('masters.slider.add_slider',compact('page_title'));
   }
    
   public function slider_saves(Request $request)
   {
      $input = $request->all();
       
       
      if($request->file('image')!='')
      {
          $upload = app(AdminMediaService::class)->uploadImage($request->file('image'), 'sliders', 'slider');
          $input['image']= $upload['path'];
          if (Schema::hasColumn('sliders', 'image_public_id')) {
              $input['image_public_id'] = $upload['public_id'];
          }
         
      } 
      
           Slider::insert($input);
            $request->session()->flash('alert-success','Slider image has been sucessfully added.');
            return Redirect::route('slider_mange');
        
       
   }
    
  public function slider_status_changed(Request $request)
  {
     
      $status = $request->status;
      $slider_id = $request->slider_id;
      $input['is_active'] = $status;
      
      $check = Slider::where('slider_id',$slider_id)->update($input);
      if($check>0)
      {
          echo "Mahi";
      }
      else
      {
           echo "hup";
      }
      
      echo $status;
      
  }
    
  public function sliderDelete(Request $request,$id)
  {
      AdminRecycleBinService::softDelete('sliders', $id);
      $request->session()->flash('alert-success','Slider moved to Recycle Bin.');
      return Redirect::route('slider_mange');
  }
    
  public function slideredit(Request $request,$id)
  {
      $users['slide_data'] = Slider::where('slider_id',$id)->get();
      $page_title = "Edit Slider - Zouple";
      return view('masters.slider.edit_slider',compact('page_title'),$users);
  }
  public function slider_edit_save(Request $request)
    {
      $input = $request->all();
      $slider_id = $request->slider_id;
    
      if($request->file('image')!='')
      {
          $data=DB::table('sliders')->where('slider_id','=',$slider_id)->value('image');
          $publicId = Schema::hasColumn('sliders', 'image_public_id')
              ? DB::table('sliders')->where('slider_id', $slider_id)->value('image_public_id')
              : null;
          app(AdminMediaService::class)->deleteMedia($data, 'slider', $publicId, 'image');
          $upload = app(AdminMediaService::class)->uploadImage($request->file('image'), 'sliders', 'slider');
          $input['image']= $upload['path'];
          if (Schema::hasColumn('sliders', 'image_public_id')) {
              $input['image_public_id'] = $upload['public_id'];
          }

      } 
      else
      {
           unset($input['image']);
      }
     
       Slider::where('slider_id',$slider_id)->update($input);

        $request->session()->flash('alert-success','Slider image has been sucessfully updated.');
        return Redirect::route('slider_mange');
    

    }

    public function slider_status_update_save(Request $request)
    {
        $input = $request->all();
        DB::table('sliders')->where('slider_id',$input['slider_id'])->update($input);
        $request->session()->flash('alert-success','Slider image Status has been sucessfully updated.');
        return Redirect::route('slider_mange');
    }
}
