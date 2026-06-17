<?php

namespace App\Http\Controllers\masterAdmin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Session;
use DB;

class DashboardController extends Controller
{
    public function showdashboard(Request $request)
    {
        $request->session()->put('Page_Title',"Admin - Dashboard"); 
        $request->session()->save();
        $dashboard_users = DB::table('users')->where('user_role',"FRONT")->count();

        $dashboard_product = DB::table('products')->count();
        $dashboard_order = DB::table('order_system')->count();
        $dashboard_review = DB::table('review')->sum('star');
        return view('masters.dashboard.dashboard',compact('dashboard_users', 'dashboard_product', 'dashboard_order', 'dashboard_review'));
    }
    
    public function logout()
    {
        Auth::logout();
        Session::flash('flash_notice',trans("messages.Login.logout"));
        return redirect(route('login'));
    }
}
