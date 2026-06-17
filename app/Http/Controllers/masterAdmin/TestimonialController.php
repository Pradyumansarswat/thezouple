<?php

namespace App\Http\Controllers\masterAdmin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth,Redirect,View,File,Config,Image;
use Validator;
use DB; 

class TestimonialController extends Controller
{
    public function testimonialList(Request $request)
    {
        $users['testimonial_data'] = DB::table('testimonial')->orderBy('testimonial_id', 'asc')->get();
        $page_title = "Testimonial List - Zouple";
       return view('masters.testimonial.testimonial',compact('page_title'),$users);
    }

    public function add_testimonialPage(Request $request)
    {
        
        $page_title = " Add Testimonial - Zouple";
       return view('masters.testimonial.add_testimonial',compact('page_title'));
    }

    public function testimonialStore(Request $request)
   {
      $input = $request->all();
       
        /*$this->validate($request , array
         (    
             'image' => 'required|dimensions:min_width=1920,min_height=700',
         ));*/ 
      if($request->file('image')!='')
      {
          $file=$request->file('image');
          $filename=$file->getClientOriginalName();
          $imgname = uniqid().$filename;
          
          $input['image']= $imgname;       
          $destinationPath=public_path('upload/testimonial/');       
          $request->file('image')->move($destinationPath, $imgname);
         
      } 
           DB::table('testimonial')->insert($input);
            $request->session()->flash('alert-success','Testimonial has been sucessfully added.');
            return Redirect::route('testimonial');   
   }

   public function testimonialUpdatePage(Request $request,$testimonial_id)
  {
      $users['testimonial_datas'] = DB::table('testimonial')->where('testimonial_id',$testimonial_id)->get();
      $page_title = "Edit Testimonial - Zouple";
      return view('masters.testimonial.edit_testimonial',compact('page_title'),$users);
  }

  public function testimonialEditUpdate(Request $request)
    {
      $input = $request->all();
      $testimonial_id = $request->testimonial_id;
    /*$this->validate($request , array
     (    
         'image' => 'required|dimensions:min_width=1920,min_height=700',
     )); */
      if($request->file('image')!='')
      {
          $data=DB::table('testimonial')->where('testimonial_id','=',$testimonial_id)->value('image');
          $fullpath=public_path('upload/testimonial/').$data;
          File::delete($fullpath);
          
          $file=$request->file('image');
          $filename=$file->getClientOriginalName();
          $imgname = uniqid().$filename;

          $input['image']= $imgname;       
          $destinationPath=public_path('upload/testimonial/');       
          $request->file('image')->move($destinationPath, $imgname);

      } 
      
      else
      {
          unset($input['image']);
      }
      
       DB::table('testimonial')->where('testimonial_id','=',$testimonial_id)->update($input);

        $request->session()->flash('alert-success','Testimonial has been sucessfully updated.');
        return Redirect::route('testimonial');
    

    }

    public function testimonialDeleteFormat(Request $request,$testimonial_id)
  {
      $data=DB::table('testimonial')->where('testimonial_id','=',$testimonial_id)->value('image');
      $fullpath=public_path('upload/testimonial/').$data;
      File::delete($fullpath);
      $m = DB::table('testimonial')->where('testimonial_id','=',$testimonial_id)->delete();
      $request->session()->flash('alert-success','Testimonial has been sucessfully deleted.
');
      return Redirect::route('testimonial');
  }
}
