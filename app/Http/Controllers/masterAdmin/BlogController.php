<?php

namespace App\Http\Controllers\masterAdmin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth,Redirect,View,File,Config,Image,Session;
use Validator;
use DB;

use App\Helper\BasicHelper;
use App\Blog;
use App\Services\AdminRecycleBinService;
use App\Services\AdminMediaService;
use Schema;

class BlogController extends Controller
{
    public function blog_list(Request $request)
    {
       $page_title = "Blog List - Zouple";

       $data['blog_data'] = AdminRecycleBinService::activeTable('blog')->orderby('blog_id', 'Desc')->get();
       return view('masters.blog.blog',compact('page_title'), $data);
    }

    public function add_blog_page(Request $request)
    {
       $page_title = "Blog Add Page - Zouple";

       
       return view('masters.blog.add_blog',compact('page_title'));
    }

    public function blog_store(Request $request)
    {
       $input = $request->all();
        /*$this->validate($request , array
         (    
             'image' => 'required|dimensions:width=565,height=407',
         ));*/ 

         $cmsObj = new Blog();
         $input['slug'] = BasicHelper::getblogSlug($cmsObj, $request->heading);

      if($request->file('image')!='')
      {
          $upload = app(AdminMediaService::class)->uploadImage($request->file('image'), 'blogs', 'blog');
          $input['image']= $upload['path'];
          if (Schema::hasColumn('blog', 'image_public_id')) {
              $input['image_public_id'] = $upload['public_id'];
          }
      }
        
      if($request->file('front_image')!='')
      {
          $upload = app(AdminMediaService::class)->uploadImage($request->file('front_image'), 'blogs', 'blog');
          $input['front_image']= $upload['path'];
          if (Schema::hasColumn('blog', 'front_image_public_id')) {
              $input['front_image_public_id'] = $upload['public_id'];
          }
      } 
           DB::table('blog')->insert($input);
            $request->session()->flash('alert-success','Blog  has been sucessfully added.');
            return Redirect::route('add_blog');
    }

    public function blogUpdatePage(Request $request,$blog_id)
  {
      $data['blog_datas'] =  AdminRecycleBinService::activeTable('blog')->where('blog_id','=',$blog_id)->get();
      $page_title = "Edit Blog - Zouple";
      return view('masters.blog.edit_blog',compact('page_title'), $data);
  }

  public function blog_edit_Update(Request $request)
    {
      $input = $request->all();
      $blog_id = $request->blog_id;
    /*$this->validate($request , array
     (    
         'image' => 'dimensions:width=700,height=500',
     )); */

     $cmsObj = new Blog();
     $input['slug'] = BasicHelper::getblogSlug($cmsObj, $request->heading);
     
      if($request->file('image')!='')
      {
          $data=DB::table('blog')->where('blog_id','=',$blog_id)->value('image');
          $publicId = Schema::hasColumn('blog', 'image_public_id')
              ? DB::table('blog')->where('blog_id', $blog_id)->value('image_public_id')
              : null;
          app(AdminMediaService::class)->deleteMedia($data, 'blog', $publicId, 'image');
          $upload = app(AdminMediaService::class)->uploadImage($request->file('image'), 'blogs', 'blog');
          $input['image']= $upload['path'];
          if (Schema::hasColumn('blog', 'image_public_id')) {
              $input['image_public_id'] = $upload['public_id'];
          }

      } 
      else
      {
          unset($input['image']);
      }
      
      if($request->file('front_image')!='')
      {
          $data1=DB::table('blog')->where('blog_id','=',$blog_id)->value('front_image');
          $publicId = Schema::hasColumn('blog', 'front_image_public_id')
              ? DB::table('blog')->where('blog_id', $blog_id)->value('front_image_public_id')
              : null;
          app(AdminMediaService::class)->deleteMedia($data1, 'blog', $publicId, 'image');
          $upload = app(AdminMediaService::class)->uploadImage($request->file('front_image'), 'blogs', 'blog');
          $input['front_image']= $upload['path'];
          if (Schema::hasColumn('blog', 'front_image_public_id')) {
              $input['front_image_public_id'] = $upload['public_id'];
          }

      } 
      else
      {
          unset($input['front_image']);
      }
      
       DB::table('blog')->where('blog_id',$blog_id)->update($input); 
        $request->session()->flash('alert-success','Blog has been sucessfully updated.');
        return Redirect::route('blog_page');
    

    }

    public function blogDeleteFormat(Request $request,$blog_id)
  {
      AdminRecycleBinService::softDelete('blogs', $blog_id);
      $request->session()->flash('alert-success','Blog moved to Recycle Bin.');
      return Redirect::route('blog_page');
  }

  public function commentPage(Request $request,$blog_id)
  {

      $data['comment_datas'] = DB::table('comment')
      ->join('users','users.id','comment.user_id')
      ->select('comment.*', 'users.name')
      ->where('comment.blog_id',$blog_id)
      ->get();


      /*$data['comment_datas'] =  DB::table('comment')->where('blog_id','=',$blog_id)->get();*/
      $page_title = "Comment Page - Zouple";
      return view('masters.blog.comment',compact('page_title'), $data);
  }

  public function comment_status_update_save(Request $request)
    {
        $input = $request->all();
        DB::table('comment')->where('comment_id',$input['comment_id'])->update($input);
        $request->session()->flash('alert-success','Comment Status Updated !!');
        return Redirect::route('blog_page');
    }
    
    
    public function blogSeeMorePage(Request $request,$blog_id)
  {
      $data['blog_datass'] =  AdminRecycleBinService::activeTable('blog')->where('blog_id','=',$blog_id)->get();
      $page_title = "Blog See More - Zouple";
      return view('masters.blog.see_more_blog',compact('page_title'), $data);
  }
}
