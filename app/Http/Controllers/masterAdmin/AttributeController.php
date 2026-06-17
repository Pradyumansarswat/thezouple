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

class AttributeController extends Controller
{
    public function attributeList(REQUEST $request)
    {
        $data['attribute_data'] = Attribute::all();
        $page_title = "Attribute - Zouple";
       return view('masters.attribute.attribute',compact('page_title'), $data);
    }
    
    public function addAttributePage(REQUEST $request)
    {
        $page_title = "Attribute - Zouple";
       return view('masters.attribute.add_attribute',compact('page_title'));
    }

    public function attributeStore(Request $request)
    {
       $input = $request->all();
       $cmsObj = new Attribute();
       $input['slug'] = BasicHelper::getattributeSlug($cmsObj, $request->name);
        Attribute::insert($input);

        $request->session()->flash('alert-success','Attribute has been sucessfully added.');
        return Redirect::route('addAttribute');
    }
    
    public function attributeUpdatePage(REQUEST $request, $attribute_id)
    {
        $data['attributes_datas'] = Attribute::where('attribute_id', $attribute_id)->get();
        $page_title = "Attribute - Zouple";
       return view('masters.attribute.edit_attribute',compact('page_title'), $data);
    }

    public function attributeEditStore(Request $request)
    {
       $input = $request->all();
       $attribute_id = $request->attribute_id;
       $cmsObj = new Attribute();
       $input['slug'] = BasicHelper::getattributeSlug($cmsObj, $request->name);
        Attribute::where('attribute_id','=',$attribute_id)->update($input);

        $request->session()->flash('alert-success','Attribute has been sucessfully updated.
');
        return Redirect::route('attribute');
    }

     public function attributeDeleteformat(Request $request,$attribute_id)
        {
            $m = Attribute::where('attribute_id','=', $attribute_id)->delete();
            $request->session()->flash('alert-success','Attribute has been sucessfully deleted.');
            return Redirect::route('attribute');
        }
}
