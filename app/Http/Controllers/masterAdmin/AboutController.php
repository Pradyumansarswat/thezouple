<?php

namespace App\Http\Controllers\masterAdmin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Email_template;
use App\Order;
use App\Product;    
use Auth,Redirect,View,File,Config,Image;
use Validator;
use DB;
use Input;
use Mail;
use App\Helper\BasicHelper;
use App\Attribute;

class AboutController extends Controller
{
    public function aboutPage(REQUEST $request)
    {
        $data['about_data'] = DB::table('about')->orderby('about_id', 'asc')->get();
        $page_title = "About us - Zouple";
       return view('masters.about.about',compact('page_title'), $data);
    }

    public function addAboutPage(Request $request)
    {
    	 $page_title = "Add About us - Zouple";
       return view('masters.about.add_about',compact('page_title'));
    }

    public function aboutStore(Request $request)
    {
       $input = $request->all();

      if($request->file('image')!='')
      {
          $file=$request->file('image');
          $filename=$file->getClientOriginalName();
          $imgname = uniqid().$filename;
          
          $input['image']= $imgname;       
          $destinationPath=public_path('upload/about/');       
          $request->file('image')->move($destinationPath, $imgname);  
      } 
           DB::table('about')->insert($input);
            $request->session()->flash('alert-success','About us has been sucessfully added.');
            return Redirect::route('addAbout');
    }

    public function aboutUpdatePage(REQUEST $request, $about_id)
    {
        $data['abouts_data'] = DB::table('about')->where('about_id', $about_id)->get();
        $page_title = "Edit About us - Zouple";
       return view('masters.about.edit_about',compact('page_title'), $data);
    }

    public function aboutEditStore(Request $request)
    {
      $input = $request->all();
      $about_id = $request->about_id;
     
      if($request->file('image')!='')
      {
          $data=DB::table('about')->where('about_id','=',$about_id)->value('image');
          $fullpath=public_path('upload/about/').$data;
          File::delete($fullpath);
          
          $file=$request->file('image');
          $filename=$file->getClientOriginalName();
          $imgname = uniqid().$filename;

          $input['image']= $imgname;       
          $destinationPath=public_path('upload/about/');       
          $request->file('image')->move($destinationPath, $imgname);

      } 
      else
      {
          unset($input['image']);
      }
      
       DB::table('about')->where('about_id','=',$about_id)->update($input); 
        $request->session()->flash('alert-success','About us has been sucessfully updated.');
        return Redirect::route('about');
    

    }

     public function aboutDeleteFormat(Request $request,$about_id)
  {
      $data=DB::table('about')->where('about_id','=',$about_id)->value('image');
          $fullpath=public_path('upload/about/').$data;
          File::delete($fullpath);
      $m = DB::table('about')->where('about_id','=',$about_id)->delete();
      $request->session()->flash('alert-success','About us has been sucessfully deleted.');
      return Redirect::route('about');
  }
}
