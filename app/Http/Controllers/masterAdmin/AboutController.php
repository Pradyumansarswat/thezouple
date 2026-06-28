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
use App\Services\AdminRecycleBinService;
use App\Services\AdminMediaService;
use Schema;
use Illuminate\Support\Facades\Log;

class AboutController extends Controller
{
    public function aboutPage(REQUEST $request)
    {
        $data['about_data'] = AdminRecycleBinService::activeTable('about')->orderby('about_id', 'asc')->get();
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
       $this->validate($request, [
           'title' => 'required|string|max:255',
           'description' => 'required|string',
           'image' => 'required|image|mimes:jpeg,jpg,png,gif,webp|max:4096',
       ], [
           'image.required' => 'Please upload an About image.',
           'image.max' => 'The About image must be 4 MB or smaller.',
       ]);

       $input = $this->safeInput($request);

      if($request->hasFile('image') && $request->file('image')->isValid())
      {
          try {
              $upload = app(AdminMediaService::class)->uploadImage($request->file('image'), 'about', 'about');
              $input['image']= $upload['path'];
              if (Schema::hasColumn('about', 'image_public_id')) {
                  $input['image_public_id'] = $upload['public_id'];
              }
          } catch (\Exception $exception) {
              Log::error('About image upload failed', ['error' => $exception->getMessage()]);
              $request->session()->flash('alert-danger','The About image could not be uploaded. Please check Cloudinary settings and try again.');
              return Redirect::back()->withInput();
          }
      }

      if (empty($input['image'])) {
          $request->session()->flash('alert-danger','The About image was not saved. Please choose a valid image and try again.');
          return Redirect::back()->withInput();
      }

           DB::table('about')->insert($input);
            $request->session()->flash('alert-success','About us has been sucessfully added.');
            return Redirect::route('addAbout');
    }

    public function aboutUpdatePage(REQUEST $request, $about_id)
    {
        $data['abouts_data'] = AdminRecycleBinService::activeTable('about')->where('about_id', $about_id)->get();
        $page_title = "Edit About us - Zouple";
       return view('masters.about.edit_about',compact('page_title'), $data);
    }

    public function aboutEditStore(Request $request)
    {
      $about_id = $request->about_id;
      $currentImage = DB::table('about')->where('about_id','=',$about_id)->value('image');
      $needsImage = !z_media_exists($currentImage, 'about');

      $this->validate($request, [
          'about_id' => 'required|integer',
          'title' => 'required|string|max:255',
          'description' => 'required|string',
          'image' => ($needsImage ? 'required' : 'nullable') . '|image|mimes:jpeg,jpg,png,gif,webp|max:4096',
      ], [
          'image.required' => 'Please upload an About image because this record does not have a saved image.',
          'image.max' => 'The About image must be 4 MB or smaller.',
      ]);

      $input = $this->safeInput($request);
     
      if($request->hasFile('image') && $request->file('image')->isValid())
      {
          $publicId = Schema::hasColumn('about', 'image_public_id')
              ? DB::table('about')->where('about_id', $about_id)->value('image_public_id')
              : null;
          try {
              $upload = app(AdminMediaService::class)->uploadImage($request->file('image'), 'about', 'about');
              $input['image']= $upload['path'];
              if (Schema::hasColumn('about', 'image_public_id')) {
                  $input['image_public_id'] = $upload['public_id'];
              }
              app(AdminMediaService::class)->deleteMedia($currentImage, 'about', $publicId, 'image');
          } catch (\Exception $exception) {
              Log::error('About image upload failed', ['about_id' => $about_id, 'error' => $exception->getMessage()]);
              $request->session()->flash('alert-danger','The About image could not be uploaded. Please check Cloudinary settings and try again.');
              return Redirect::back()->withInput();
          }

      } 
      else
      {
          unset($input['image']);
      }
      
       DB::table('about')->where('about_id','=',$about_id)->update($input); 
        $request->session()->flash('alert-success','About us has been sucessfully updated.');
        return Redirect::route('about');
    

    }

    private function safeInput(Request $request)
    {
      $input = $request->only(['title', 'description']);

      if (Schema::hasColumn('about', '_token')) {
          $input['_token'] = $request->input('_token');
      }

      return $input;
    }

     public function aboutDeleteFormat(Request $request,$about_id)
  {
      AdminRecycleBinService::softDelete('about', $about_id);
      $request->session()->flash('alert-success','About record moved to Recycle Bin.');
      return Redirect::route('about');
  }
}
