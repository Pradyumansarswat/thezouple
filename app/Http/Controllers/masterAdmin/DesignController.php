<?php

namespace App\Http\Controllers\masterAdmin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth,Redirect,View,File,Config,Image,Session;
use Validator;
use DB;

use App\AttributValue;
use App\ShirtCategory;
use App\ShirtAttribut;

use App\Helper\BasicHelper;

class DesignController extends Controller
{
   /* Shirt Design Code Start */
    
    public function shirtCategoryList(Request $request)
    {   
        $data['shirt_category_data'] = DB::table('febric')->orderby('febric_id','desc')->get();
        $page_title = "Febric List - Zouple";
        return view('masters.shirtdesign.shirt_category',compact('page_title'),$data);
    }
    
    public function addShirtDesignPage(Request $request)
    {   
        $page_title = "Add Febric - Zouple";
        return view('masters.shirtdesign.add_shirt_category',compact('page_title'));
    }
    
    public function shirtCategoryStore(Request $request)
    {
       $input = $request->all();
        /*$this->validate($request , array
         (    
             'image' => 'required|dimensions:width=565,height=407',
         ));*/ 

         $cmsObj = new ShirtCategory();
         $input['slug'] = BasicHelper::getshirtcategorySlug($cmsObj, $request->name);

      if($request->file('image')!='')
      {
          $file=$request->file('image');
          $filename=$file->getClientOriginalName();
          $imgname = uniqid().$filename;
          
          $input['image']= $imgname;       
          $destinationPath=public_path('upload/shirt/');       
          $request->file('image')->move($destinationPath, $imgname);  
      } 
           DB::table('febric')->insert($input);
            $request->session()->flash('alert-success','Febric has been sucessfully added.');
            return Redirect::route('addShirtDesign');
    }
    
    public function shirtCategoryUpdatePage(Request $request, $febric_id)
    {   
        $data['shirt_categorys_datas'] = DB::table('febric')->where('febric_id',$febric_id)->get();
        $page_title = "Edit Febric - Zouple";
        return view('masters.shirtdesign.edit_shirt_category',compact('page_title'),$data);
    }
    
    public function shirtCategoryEditStore(Request $request)
    {
      $input = $request->all();
      $febric_id = $request->febric_id;
    /*$this->validate($request , array
     (    
         'image' => 'dimensions:width=700,height=500',
     )); */

     $cmsObj = new ShirtCategory();
     $input['slug'] = BasicHelper::getshirtcategorySlug($cmsObj, $request->name);
     
      if($request->file('image')!='')
      {
          $data=DB::table('febric')->where('febric_id','=',$febric_id)->value('image');
          $fullpath=public_path('upload/shirt/').$data;
          File::delete($fullpath);
          
          $file=$request->file('image');
          $filename=$file->getClientOriginalName();
          $imgname = uniqid().$filename;

          $input['image']= $imgname;       
          $destinationPath=public_path('upload/shirt/');       
          $request->file('image')->move($destinationPath, $imgname);

      } 
      else
      {
          unset($input['image']);
      }
        
      
       DB::table('febric')->where('febric_id','=',$febric_id)->update($input); 
        $request->session()->flash('alert-success','Febric has been sucessfully updated.');
        return Redirect::route('shirtCategory');
    

    }
    
    public function shirtCategoryDeleteFormat(Request $request,$febric_id)
  {
      $data=DB::table('febric')->where('febric_id','=',$febric_id)->value('image');
          $fullpath=public_path('upload/shirt/').$data;
          File::delete($fullpath);
      $m = DB::table('febric')->where('febric_id','=',$febric_id)->delete();
      $request->session()->flash('alert-success','Febric has been sucessfully deleted.');
      return Redirect::route('shirtCategory');
  }


    
    
    /* Shirt Design Code End */
    
    
    /* Shirt Attribut Code Start */
    
    public function shirtAttributList(Request $request)
    {   
        $data['shirt_attribut_data'] = DB::table('element')->orderby('element_id','desc')->get();
        $page_title = "Elements List - Zouple";
        return view('masters.shirtdesign.shirt_attribut',compact('page_title'),$data);
    }
    
    public function addShirtAttributPage(Request $request)
    {   
        $page_title = "Add Elements - Zouple";
        return view('masters.shirtdesign.add_shirt_attribut',compact('page_title'));
    }
    
    public function shirtAttributStore(Request $request)
    {
       $input = $request->all();
        /*$this->validate($request , array
         (    
             'image' => 'required|dimensions:width=565,height=407',
         ));*/ 

         $cmsObj = new ShirtAttribut();
         $input['slug'] = BasicHelper::getshirtattributSlug($cmsObj, $request->name);

      if($request->file('image')!='')
      {
          $file=$request->file('image');
          $filename=$file->getClientOriginalName();
          $imgname = uniqid().$filename;
          
          $input['image']= $imgname;       
          $destinationPath=public_path('upload/shirt/');       
          $request->file('image')->move($destinationPath, $imgname);  
      } 
           DB::table('element')->insert($input);
            $request->session()->flash('alert-success','Elements has been sucessfully added.');
            return Redirect::route('addShirtAttribut');
    }
    
    public function shirtAttributUpdatePage(Request $request, $element_id)
    {   
        $data['shirt_attribut_datas'] = DB::table('element')->where('element_id',$element_id)->get();
        $page_title = "Edit Elements - Zouple";
        return view('masters.shirtdesign.edit_shirt_attribut',compact('page_title'),$data);
    }
    
    public function shirtAttributEditStore(Request $request)
    {
      $input = $request->all();
      $element_id = $request->element_id;
    /*$this->validate($request , array
     (    
         'image' => 'dimensions:width=700,height=500',
     )); */

     $cmsObj = new ShirtAttribut();
     $input['slug'] = BasicHelper::getshirtattributSlug($cmsObj, $request->name);
     
      if($request->file('image')!='')
      {
          $data=DB::table('element')->where('element_id','=',$element_id)->value('image');
          $fullpath=public_path('upload/shirt/').$data;
          File::delete($fullpath);
          
          $file=$request->file('image');
          $filename=$file->getClientOriginalName();
          $imgname = uniqid().$filename;

          $input['image']= $imgname;       
          $destinationPath=public_path('upload/shirt/');       
          $request->file('image')->move($destinationPath, $imgname);

      } 
      else
      {
          unset($input['image']);
      }
      
       DB::table('element')->where('element_id','=',$element_id)->update($input); 
        $request->session()->flash('alert-success','Elements has been sucessfully updated.');
        return Redirect::route('shirtAttribut');
    

    }
    
    public function shirtAttributDeleteFormat(Request $request,$element_id)
  {
      $data=DB::table('element')->where('element_id','=',$element_id)->value('image');
          $fullpath=public_path('upload/shirt/').$data;
          File::delete($fullpath);
      $m = DB::table('element')->where('element_id','=',$element_id)->delete();
      $request->session()->flash('alert-success','Elements has been sucessfully deleted.');
      return Redirect::route('shirtAttribut');
  }
    
    /* Shirt Attribut Code End */
    
    
    /*  Attribut Value Code End */
    
    public function attributValueList(Request $request)
    {   
        $data['attribut_value_data'] = DB::table('element_value')
            ->join('element', 'element.element_id', '=', 'element_value.element_id')
            ->select('element_value.*','element.name')
            ->orderBy('element_value.element_value_id', 'DESC')
            ->get();
        $page_title = "Elements Value List - Zouple";
        return view('masters.shirtdesign.attribut_value',compact('page_title'),$data);
    }
    
    public function addAttributValuePage(Request $request)
    {   
        $data['shirt_attribut_data'] = DB::table('element')->orderby('element_id','desc')->get();
        $page_title = "Elements Value List - Zouple";
        return view('masters.shirtdesign.add_attribut_value',compact('page_title'),$data);
    }
    
    public function attributValueStore(Request $request)
    {
       $input = $request->all();
        /*$this->validate($request , array
         (    
             'image' => 'required|dimensions:width=565,height=407',
         ));*/ 

        $cmsObj = new AttributValue();
        $input['slug'] = BasicHelper::getattributvalueSlug($cmsObj, $request->attribut_name);

      if($request->file('image')!='')
      {
          $file=$request->file('image');
          $filename=$file->getClientOriginalName();
          $imgname = uniqid().$filename;
          
          $input['image']= $imgname;       
          $destinationPath=public_path('upload/shirt/');       
          $request->file('image')->move($destinationPath, $imgname);  
      } 
           DB::table('element_value')->insert($input);
            $request->session()->flash('alert-success','Elements Value has been sucessfully added.');
            return Redirect::route('addAttributValue');
    }
    
    public function attributValueUpdatePage(Request $request, $element_value_id)
    {   
        
        $data['shirt_attribut_data'] = DB::table('element')->orderby('element_id','desc')->get();
        
        $data['attribut_value_datas'] = DB::table('element_value')->where('element_value_id',$element_value_id)->get();
        
        $page_title = "Edit Elements Value - Zouple";
        return view('masters.shirtdesign.edit_attribut_value',compact('page_title'),$data);
    }
    
    public function attributValueEditStore(Request $request)
    {
      $input = $request->all();
      $element_value_id = $request->element_value_id;
    /*$this->validate($request , array
     (    
         'image' => 'dimensions:width=700,height=500',
     )); */

     $cmsObj = new AttributValue();
     $input['slug'] = BasicHelper::getattributvalueSlug($cmsObj, $request->attribut_name);
     
      if($request->file('image')!='')
      {
          $data=DB::table('element_value')->where('element_value_id','=',$element_value_id)->value('image');
          $fullpath=public_path('upload/shirt/').$data;
          File::delete($fullpath);
          
          $file=$request->file('image');
          $filename=$file->getClientOriginalName();
          $imgname = uniqid().$filename;

          $input['image']= $imgname;       
          $destinationPath=public_path('upload/shirt/');       
          $request->file('image')->move($destinationPath, $imgname);

      } 
      else
      {
          unset($input['image']);
      }
      
       DB::table('element_value')->where('element_value_id','=',$element_value_id)->update($input); 
        $request->session()->flash('alert-success','Elements Value has been sucessfully updated.');
        return Redirect::route('attributValue');
    

    }
    
    public function attributValueDeleteFormat(Request $request,$element_value_id)
  {
      $data=DB::table('element_value')->where('element_value_id','=',$element_value_id)->value('image');
          $fullpath=public_path('upload/shirt/').$data;
          File::delete($fullpath);
      $m = DB::table('element_value')->where('element_value_id','=',$element_value_id)->delete();
      $request->session()->flash('alert-success','Elements Value has been sucessfully deleted.');
      return Redirect::route('attributValue');
  }
    
    
    
    
    /* Shirt Size Design Code Start */
    
    
    
    
    public function shirtSizeList(Request $request)
    {   
        $data['shirt_size_data'] = DB::table('shirt_size')->orderby('shirt_size_id','desc')->get();
        $page_title = "Shirt Size List - Zouple";
        return view('masters.shirtdesign.shirt_size',compact('page_title'),$data);
    }
    
    public function addShirtSizePage(Request $request)
    {   
        $page_title = "Add Shirt Size - Zouple";
        return view('masters.shirtdesign.add_shirt_size',compact('page_title'));
    }
    
    public function shirtSizeStore(Request $request)
    {
       $input = $request->all();
        /*$this->validate($request , array
         (    
             'image' => 'required|dimensions:width=565,height=407',
         ));*/ 

         
      if($request->file('image')!='')
      {
          $file=$request->file('image');
          $filename=$file->getClientOriginalName();
          $imgname = uniqid().$filename;
          
          $input['image']= $imgname;       
          $destinationPath=public_path('upload/shirt/');       
          $request->file('image')->move($destinationPath, $imgname);  
      } 
           DB::table('shirt_size')->insert($input);
            $request->session()->flash('alert-success','Shirt Size has been sucessfully added.');
            return Redirect::route('addShirtSize');
    }
    
    public function shirtSizeUpdatePage(Request $request, $shirt_size_id)
    {   
        $data['shirt_size_datass'] = DB::table('shirt_size')->where('shirt_size_id',$shirt_size_id)->get();
        $page_title = "Edit Shirt Size - Zouple";
        return view('masters.shirtdesign.edit_shirt_size',compact('page_title'),$data);
    }
    
    public function shirtSizeEditStore(Request $request)
    {
      $input = $request->all();
      $shirt_size_id = $request->shirt_size_id;
    /*$this->validate($request , array
     (    
         'image' => 'dimensions:width=700,height=500',
     )); */

    
     
      if($request->file('image')!='')
      {
          $data=DB::table('shirt_size')->where('shirt_size_id','=',$shirt_size_id)->value('image');
          $fullpath=public_path('upload/shirt/').$data;
          File::delete($fullpath);
          
          $file=$request->file('image');
          $filename=$file->getClientOriginalName();
          $imgname = uniqid().$filename;

          $input['image']= $imgname;       
          $destinationPath=public_path('upload/shirt/');       
          $request->file('image')->move($destinationPath, $imgname);

      } 
      else
      {
          unset($input['image']);
      }
        
      
       DB::table('shirt_size')->where('shirt_size_id','=',$shirt_size_id)->update($input); 
        $request->session()->flash('alert-success','Shirt Size has been sucessfully updated.');
        return Redirect::route('shirtSize');
    

    }
    
    public function shirtSizeDeleteFormat(Request $request,$shirt_size_id)
  {
      $data=DB::table('shirt_size')->where('shirt_size_id','=',$shirt_size_id)->value('image');
          $fullpath=public_path('upload/shirt/').$data;
          File::delete($fullpath);
      $m = DB::table('shirt_size')->where('shirt_size_id','=',$shirt_size_id)->delete();
      $request->session()->flash('alert-success','Shirt Size has been sucessfully deleted.');
      return Redirect::route('shirtSize');
  }
    
    
    
    
    
    /* Shirt Size Design Code End */

    
    
    
    
    
    /*  Attribut Value Code End */
}
