<?php

namespace App\Http\Controllers\masterAdmin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth,Redirect,View,File,Config,Image;
use Validator;
use DB;

class VideoController extends Controller
{
    /* Main Video Code Start */
    
    public function mainVideoList(Request $request)
   {
        $users['video_data'] = DB::table('video')->where('video_id', 1)->get();
        $page_title = "Main Video List - Zouple";
       return view('masters.video.mainvideo',compact('page_title'),$users);
   }
    
   public function mainVideoUpdatePage(Request $request)
   {
        $users['videos_data'] = DB::table('video')->where('video_id', 1)->get();
        $page_title = "Main Video List - Zouple";
       return view('masters.video.edit_mainvideo',compact('page_title'),$users);
   }
    
    public function mainvideoUpdateStore(Request $request)
    {
      $input = $request->all();
      if($request->file('video')!='')
      {
          $data=DB::table('video')->where('video_id','=', 1)->value('video');
          $fullpath=public_path('upload/video/').$data;
          File::delete($fullpath);
          
          $file=$request->file('video');
          $filename=$file->getClientOriginalName();
          $imgname = uniqid().$filename;

          $input['video']= $imgname;       
          $destinationPath=public_path('upload/video/');       
          $request->file('video')->move($destinationPath, $imgname);
      } 
      else
      {
           unset($input['video']);
      }
       DB::table('video')->where('video_id',1)->update($input);

        $request->session()->flash('alert-success','Video Updated !!');
        return Redirect::route('mainVideo');
    
    }
    
    /* Main Video Code End */
    
    /* Sub Video Code Start */
    
    public function subVideoList(Request $request)
   {
        $users['subvideo_data'] = DB::table('video')->where('video_id', 2)->get();
        $page_title = "Sub Video List - Zouple";
       return view('masters.video.subvideo',compact('page_title'),$users);
   }
    
   public function subVideoUpdatePage(Request $request)
   {
        $users['videos_data'] = DB::table('video')->where('video_id', 2)->get();
        $page_title = "Main Video List - Zouple";
       return view('masters.video.edit_subvideo',compact('page_title'),$users);
   }
    
    public function subvideoUpdateStore(Request $request)
    {
      $input = $request->all();
      
          $data=DB::table('video')->where('video_id','=', 2)->value('video');
          $fullpath=public_path('upload/video/').$data;
          //File::delete($fullpath);
          
          $file=$request->file('video');
          if($file != "")
          {
              $filename=$file->getClientOriginalName();
             $imgname = uniqid().$filename;
             $input['video']= $imgname;     
             $destinationPath=public_path('upload/video/');       
            $request->file('video')->move($destinationPath, $imgname);
          }
          

         
          
      
       DB::table('video')->where('video_id',2)->update($input);

        $request->session()->flash('alert-success','Video Updated !!');
        return Redirect::route('subVideo');
    
    }
    
    /* Sub Video Code End */
}
