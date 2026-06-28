<?php

namespace App\Http\Controllers\masterAdmin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Session;
use DB;
use Auth;
use App\Services\AdminRecycleBinService;

class DashboardController extends Controller
{
    public function showdashboard(Request $request)
    {
        $request->session()->put('Page_Title',"Admin - Dashboard"); 
        $request->session()->save();
        $dashboard_users = AdminRecycleBinService::activeTable('users')->where('user_role',"FRONT")->count();

        $dashboard_product = AdminRecycleBinService::activeTable('products')->count();
        $dashboard_order = AdminRecycleBinService::activeTable('order_system')->count();
        $dashboard_review = AdminRecycleBinService::activeTable('review')->sum('star');
        return view('masters.dashboard.dashboard',compact('dashboard_users', 'dashboard_product', 'dashboard_order', 'dashboard_review'));
    }
    
    public function logout()
    {
        Auth::guard('admin')->logout();
        Session::flash('flash_notice',trans("messages.Login.logout"));
        return redirect(route('admin.login'));
    }
}
