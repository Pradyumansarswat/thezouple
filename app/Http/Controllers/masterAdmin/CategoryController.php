<?php

namespace App\Http\Controllers\masterAdmin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Category;
use App\Product;
use App\Otherpage;
use Auth,Redirect,View,File,Config,Image,Session;
use Validator;
use DB;
use Input;
use App\Helper\BasicHelper;

use App\Attribute;
use App\Services\AdminRecycleBinService;
use App\Services\AdminMediaService;
use Schema;

class CategoryController extends Controller
{
    
    /*Level 1*/
    public function category_show(Request $request)
    {
        Session::put('cate_level', 1);
        Session::save();
        $data['category_data'] = Category::where('parent_id',0)->get();
        $attributeName = [];
        $attributes = Attribute::select('attribute_id', 'name')->get();
        foreach ($attributes as $attribute) {
            $attributeName[$attribute->attribute_id] = $attribute->name;
        }
        $page_title = "Categories List - Zouple";
        return view('masters.category.category',compact('page_title', 'attributeName'),$data);
    }
    
    public function add_category(Request $request)
    {
       
       $page_title = "Add Category - Zouple";
        
       $data['attribute_data'] = Attribute::all();
        
       return view('masters.category.add_category',compact('page_title'), $data);
    }
    
    public function category_store(Request $request)
    {
       $input = $this->safeInput($request);
       $input['attributesvalue'] = json_encode($request->attributesvalue);
      /*  echo $input['attributesvalue'];*/
       $cmsObj = new Category();
       $input['slug'] = BasicHelper::getUniqueSlug($cmsObj, $request->title);
        $input['parent_id'] = 0;
        $this->validate($request , array
         (    
             
             'title' => 'required',
         )); 
      if($request->hasFile('image') && $request->file('image')->isValid())
      {
          $upload = $this->uploadImage($request->file('image'), 'categories', 'category');
          $input['image']= $upload['path'];
          $this->setPublicId($input, 'categorys', $upload['public_id']);
         
      } 
           Category::insert($input);
        
        
         $meta_tag['meta_title'] = $request->meta_title;
            $meta_tag['meta_keyword'] = $request->meta_keyword;
            $meta_tag['meta_description'] = $request->description;
            $meta_tag['meta_page'] = $request->title;
            $meta_tag['meta_slug'] = $input['slug'];
         $meta_tag['_token'] =  $request->_token;

          DB::table('meta_tags')->insert($meta_tag);
        
            $request->session()->flash('alert-success','Category has been sucessfully added.');
            return Redirect::route('category_list');
    }
       
       
    public function category_update(Request $request,$id)
    {
      $data['category_data'] = Category::where('category_id',$id)->get();
      $page_title = "Edit Category - Zouple";
      $data['attribute_data'] = Attribute::all();
      return view('masters.category.edit_category',compact('page_title'),$data);
    }
    public function category_update_store(Request $request)
    {
        $input = $this->safeInput($request);
        $category_id = $request->category_id;
        $input['attributesvalue'] = json_encode($request->attributesvalue);
        $cmsObj = new Category();
       $input['slug'] = BasicHelper::getUniqueSlug($cmsObj, $request->title);
      if($request->hasFile('image') && $request->file('image')->isValid())
      {
          $this->validate($request , array
         (    
             
             'title' => 'required'
         ));
          $data=DB::table('categorys')->where('category_id','=',$category_id)->value('image');
          $this->deleteImage('categorys', 'category_id', $category_id, 'category', $data);
          $upload = $this->uploadImage($request->file('image'), 'categories', 'category');
          $input['image']= $upload['path'];
          $this->setPublicId($input, 'categorys', $upload['public_id']);

      } 
      
       Category::where('category_id',$category_id)->update($input);

        $request->session()->flash('alert-success','Category has been sucessfully updated.');
        return Redirect::route('category_list');
    }
    
    public function categoryDestory(Request $request , $id)
    {
      AdminRecycleBinService::softDelete('categories', $id);
      $children = Category::where('parent_id', $id)->pluck('category_id');
      foreach($children as $childId) {
          AdminRecycleBinService::softDelete('categories', $childId);
      }
      $request->session()->flash('alert-success','Category moved to Recycle Bin.');
      return Redirect::route('category_list');
        
        
    }

    public function category_status_update(Request $request)
    {
        $input = $request->all();
        DB::table('categorys')->where('category_id',$input['category_id'])->update($input);
        $request->session()->flash('alert-success','Category Status has been sucessfully updated.
');
        return Redirect::route('category_list');
    }
    
     
    public function category_show_update_store(Request $request)
    {
        $input = $request->all();
        DB::table('categorys')->where('category_id',$input['category_id'])->update($input);
        $request->session()->flash('alert-success','Category Sub Status has been sucessfully updated.');
        return Redirect::route('category_list');
    }
    
    
    /* Level 2 */
    
    public function sub_category_show(Request $request , $id)
    {   
        $cate_level = Session::get('cate_level');
        $cate_level++;
        Session::put('cate_level', $cate_level);
        Session::save();
        $data['category_data'] = Category::where('parent_id',$id)->get();
        $sup_parrent=DB::table('categorys')->where('category_id','=',$id)->value('title');
        $page_title = $sup_parrent." sub Categories List - Zouple";
        return view('masters.category.sub_category',compact('page_title','sup_parrent','id'),$data);
        
    }
    public function add_sub_category(Request $request ,$id)
    {
        $page_title = "Add Category - Zouple";
        $data['attribute_data'] = Attribute::all();
       return view('masters.category.add_sub_category',compact('page_title','id'),$data);
    }
    public function sub_category_store(Request $request)
    {
        $p_id = $request->parent_id;
        $input = $this->safeInput($request);
        $input['attributesvalue'] = json_encode($request->attributesvalue);
        $this->validate($request , array
         (     
             'title' => 'required'
         )); 
      if($request->hasFile('image') && $request->file('image')->isValid())
      {
          $upload = $this->uploadImage($request->file('image'), 'categories', 'category');
          $input['image']= $upload['path'];
          $this->setPublicId($input, 'categorys', $upload['public_id']);
         
      } 
        
        
        $cmsObj = new Category();
        $input['slug'] = BasicHelper::getUniqueSlug($cmsObj, $request->title);
        Category::insert($input);
        $request->session()->flash('alert-success','Sub Category has been sucessfully added.');
        return Redirect::route('sub_category',$p_id);
        
    }
    public function sub_category_update(Request $request,$id)
    {
      $data['category_data'] = Category::where('category_id',$id)->get();
      $data['attribute_data'] = Attribute::all();
      $page_title = "Edit Sub Category - Zouple";
      return view('masters.category.edit_sub_category',compact('page_title','id'),$data);
   }
   public function sub_category_update_store(Request $request)
    {
        $p_id = $request->parent_id;
        $category_id = $request->category_id;
        $input = $this->safeInput($request);
        
       if($request->hasFile('image') && $request->file('image')->isValid())
      {
          $this->validate($request , array
         (    
             
             'title' => 'required'
         ));
          $data=DB::table('categorys')->where('category_id','=',$category_id)->value('image');
          $this->deleteImage('categorys', 'category_id', $category_id, 'category', $data);
          $upload = $this->uploadImage($request->file('image'), 'categories', 'category');
          $input['image']= $upload['path'];
          $this->setPublicId($input, 'categorys', $upload['public_id']);

      }  
       
        $input['attributesvalue'] = json_encode($request->attributesvalue);
        $cmsObj = new Category();
        $input['slug'] = BasicHelper::getUniqueSlug($cmsObj, $request->title);
        Category::where('category_id',$category_id)->update($input);
        $request->session()->flash('alert-success','Sub Category Update !!');
        return Redirect::route('sub_category', $p_id); 
    }

    public function sub_category_status_update(Request $request)
    {
        $p_id = $request->parent_id;
        $input = $request->all();
        Category::where('category_id',$input['category_id'])->update($input);
        $request->session()->flash('alert-success','Sub Category has been sucessfully updated.
');
        return Redirect::route('sub_category',$p_id);
    }
    
   
    
     /*Other Page */
    
    public function other_page(Request $request)
    {
        Session::put('cate_level', 1);
        Session::save();
        $data['other_page'] = Otherpage::where('parent_id',0)->get();
        $page_title = "Policy Other Page - Zouple";
        return view('masters.otherpages.otherpage',compact('page_title'),$data);
    }
    public function add_other_page(Request $request)
    { 
        $page_title = "Add Other Page - Zouple";
        return view('masters.otherpages.add_other_page',compact('page_title'));
    }
        
   public function otherpage_status_update(Request $request)
   {
        $input = $request->all();
        DB::table('other_page')->where('other_id',$input['other_id'])->update($input);
        $request->session()->flash('alert-success','Page Status Updated !!');
        return Redirect::route('other_page');
   }
    public function other_page_save(Request $request)
   {
        $this->validate($request , array
         (    
             'page_title' => 'required',
             'image' => 'required|dimensions:width=400,height=400'
         ));
        $input = $request->all();
        
          if($request->file('image')!='')
          {
              $upload = $this->uploadImage($request->file('image'), 'pages', 'morepage');
              $input['image']= $upload['path'];
              $this->setPublicId($input, 'other_page', $upload['public_id']);
          } 
        
        $cmsObj = new Otherpage();
        $input['slug'] = BasicHelper::getpageSlug($cmsObj, $request->page_title);
        $input['parent_id'] = 0;
        
        DB::table('other_page')->insert($input);
        $request->session()->flash('alert-success','Page Added !!');
        return Redirect::route('other_page');
   }
    
    public function other_page_edit_store(Request $request)
    {
        $input = $request->all();
        $this->validate($request , array
         (    
             'page_title' => 'required',
             'image' => 'required|dimensions:width=400,height=400'
         ));
        
        if($request->file('image')!='')
      {
          $data=DB::table('other_page')->where('other_id','=',$input['other_id'])->value('image');
          $this->deleteImage('other_page', 'other_id', $input['other_id'], 'morepage', $data);
          $upload = $this->uploadImage($request->file('image'), 'pages', 'morepage');
          $input['image']= $upload['path'];
          $this->setPublicId($input, 'other_page', $upload['public_id']);

      } 
        
        $cmsObj = new Otherpage();
        $input['slug'] = BasicHelper::getpageSlug($cmsObj, $request->page_title);
        DB::table('other_page')->where('other_id',$input['other_id'])->update($input);
        $request->session()->flash('alert-success','Page Updated !!');
        return Redirect::route('other_page');
    } 
    
    public function other_page_edit(Request $request,$id)
    {
        /*echo $id;*/
        
        $data['page_data'] = DB::table('other_page')->where('other_id',$id)->get();
        $page_title = "Edit Other Page - Zouple";
        return view('masters.otherpages.edit_other_page',compact('page_title'),$data);
    }
    public function sub_other_page(Request $request,$id)
    {
        $cate_level = Session::get('cate_level');
        $cate_level++;
        Session::put('cate_level', $cate_level);
        Session::save();
        $sup_parrent=DB::table('other_page')->where('other_id','=',$id)->value('page_title');
        $data['other_page'] = DB::table('other_page')->where('parent_id',$id)->get();
        $page_title = "Sub Other Page - Zouple";
        return view('masters.otherpages.sub_page',compact('page_title','sup_parrent','id'),$data);
    }
    public function add_other_sub_page(Request $request,$id)
    {
        $page_title = "Add Sub Other Page - Zouple";
        return view('masters.otherpages.add_other_sub_page',compact('page_title','id'));
    }
    public function other_sub_page_save(Request $request)
    {
         $input = $request->all();
        $p_id = $request->parent_id;
        
        $this->validate($request , array
         (    
             'page_title' => 'required',
             'image' => 'required|dimensions:width=400,height=400'
         ));
        $input = $request->all();
        
          if($request->file('image')!='')
          {
              $upload = $this->uploadImage($request->file('image'), 'pages', 'morepage');
              $input['image']= $upload['path'];
              $this->setPublicId($input, 'other_page', $upload['public_id']);
          } 
        
         $cmsObj = new Otherpage();
        $input['slug'] = BasicHelper::getpageSlug($cmsObj, $request->page_title);
        DB::table('other_page')->insert($input);
        $request->session()->flash('alert-success','Page Updated !!');
        return Redirect::route('sub_other_page',$p_id);
    }
    public function other_sub_page_edit(Request $request,$id)
    {
        /*echo $id;*/
        
        $data['other_page'] = DB::table('other_page')->where('other_id',$id)->get();
        $page_title = "Edit Other Page - Zouple";
        return view('masters.otherpages.other_sub_page_edit',compact('page_title','id'),$data);
    }
    public function other_sub_page_update(Request $request)
    {
        
        $input = $request->all();
        $p_id = $request->parent_id;
        
        $this->validate($request , array
         (    
             'page_title' => 'required',
             'image' => 'required|dimensions:width=400,height=400'
         ));
        
        if($request->file('image')!='')
      {
          $data=DB::table('other_page')->where('other_id','=',$input['other_id'])->value('image');
          $this->deleteImage('other_page', 'other_id', $input['other_id'], 'morepage', $data);
          $upload = $this->uploadImage($request->file('image'), 'pages', 'morepage');
          $input['image']= $upload['path'];
          $this->setPublicId($input, 'other_page', $upload['public_id']);

      } 
        
        $cmsObj = new Otherpage();
        $input['slug'] = BasicHelper::getpageSlug($cmsObj, $request->page_title);
        DB::table('other_page')->where('other_id',$input['other_id'])->update($input);
        $request->session()->flash('alert-success','Page Updated !!');
        return Redirect::route('sub_other_page',$p_id);
    }
    public function othersubpage_status_update(Request $request)
   {
        $input = $request->all();
         $p_id = $request->parent_id;
        DB::table('other_page')->where('other_id',$input['other_id'])->update($input);
        $request->session()->flash('alert-success','Page Status Updated !!');
        return Redirect::route('sub_other_page',$p_id);
   }

    private function safeInput(Request $request)
    {
        $input = array_merge($request->query->all(), $request->request->all());
        unset($input['existing_image']);

        return $input;
    }

    private function uploadImage($file, $cloudFolder, $localFolder)
    {
        return app(AdminMediaService::class)->uploadImage($file, $cloudFolder, $localFolder);
    }

    private function setPublicId(array &$input, $table, $publicId)
    {
        if (Schema::hasColumn($table, 'image_public_id')) {
            $input['image_public_id'] = $publicId;
        }
    }

    private function deleteImage($table, $keyColumn, $keyValue, $localFolder, $image)
    {
        $publicId = Schema::hasColumn($table, 'image_public_id')
            ? DB::table($table)->where($keyColumn, $keyValue)->value('image_public_id')
            : null;

        app(AdminMediaService::class)->deleteMedia($image, $localFolder, $publicId, 'image');
    }
    
    
    
}
