<?php

namespace App\Http\Controllers\masterAdmin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth,Redirect,View,File,Config,Image,Session;
use Validator;
use DB;
use Input;

use App\Currency;

class CurrencyController extends Controller
{
    public function currencyPage(REQUEST $request)
    {
        $data['currencyData'] = Currency::all();
        $page_title = "Currency - Zouple";
        return view('masters.currency.currency',compact('page_title'), $data);
    }

    public function addCurrencyPage (Request $request)
    {
    	 $page_title = "Add Currency - Zouple";
        return view('masters.currency.addCurrency',compact('page_title'));
    }

    public function currencyStore(Request $request)
    {
    	$input = $request->all();
    	Currency::insert($input);
    	$request->session()->flash('alert-success','Currency has been sucessfully added.');
        return Redirect::route('addCurrency');
    }

    public function currencyUpdatePage(REQUEST $request, $currency_id)
    {
        $data['currencyDatass'] = Currency::where('currency_id', $currency_id)->get();
        $page_title = "Edit Currency - Zouple";
        return view('masters.currency.editCurrency',compact('page_title'), $data);
    }

    public function currencyEditStore(Request $request)
    {
    	$input = $request->all();
    	$currency_id = $request->currency_id;
    	Currency::where('currency_id', $currency_id)->update($input);
    	$request->session()->flash('alert-success','Currency has been sucessfully updated.');
        return Redirect::route('currency');
    }

    public function currencyDeleteFormat(Request $request,$currency_id)
  {
      Currency::where('currency_id', $currency_id)->delete();
      $request->session()->flash('alert-success','Currency has been sucessfully deleted.');
      return Redirect::route('currency');
  }
}
