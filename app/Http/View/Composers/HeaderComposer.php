<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use DB;
use Auth;
use App\Category;

class HeaderComposer
{
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $categories = collect([]);
        $siteinformation = collect([]);
        $cart_item = 0;
        $wish_item = 0;

        try {
            // 1. Categories
            $categories = Category::where('parent_id', 0)->where('is_show', 'SHOW')->get();

            // 2. Site Information
            $siteinformation = DB::table('siteinfos')->where('siteinfo_id', 1)->get();

            // 3. Cart & Wishlist counts
            if (Auth::check()) {
                $user_id = Auth::user()->id;
                $ip = request()->ip();

                $cart_item = DB::table('carts')
                    ->where(function ($query) use ($ip, $user_id) {
                        $query->where('user_id', $user_id)
                            ->orWhere(function ($guestQuery) use ($ip) {
                                $guestQuery->where('ip_address', $ip)
                                    ->where(function ($emptyUserQuery) {
                                        $emptyUserQuery->whereNull('user_id')
                                            ->orWhere('user_id', 0)
                                            ->orWhere('user_id', '');
                                    });
                            });
                    })
                    ->count('cart_id');

                $wish_item = DB::table('wishlists')
                    ->where('user_id', $user_id)
                    ->count('wishlist_id');
            } else {
                $ip = request()->ip();
                $cart_item = DB::table('carts')
                    ->where('ip_address', $ip)
                    ->where(function ($query) {
                        $query->whereNull('user_id')
                            ->orWhere('user_id', 0)
                            ->orWhere('user_id', '');
                    })
                    ->count('cart_id');
            }
        } catch (\Exception $e) {
            // Ignore DB errors (e.g. database not running or missing tables)
        }

        $view->with(compact('categories', 'siteinformation', 'cart_item', 'wish_item'));
    }
}
