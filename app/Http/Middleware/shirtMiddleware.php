<?php

namespace App\Http\Middleware;

use Closure;
use View;
use DB;
use App\Otherpage;
use App\Category;
use Auth;
use config;

class shirtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Element values data
        $element_data = DB::table('element_value')->get();
        foreach($element_data as $data)
        {
            $elementValueImage[$data->element_value_id] = $data->image;
            $elementValueName[$data->element_value_id] = $data->attribut_name;
           
        }
        if(!$element_data->isEmpty())
        {
            View::share('elementValueImage', $elementValueImage);
            View::share('elementValueName', $elementValueName);
        }
        
        //Febric data
        $febric = DB::table('febric')->get();
        foreach($febric as $data)
        {
            $febricImage[$data->febric_id] = $data->image;
            $febricName[$data->febric_id] = $data->name;
            $febricAmount[$data->febric_id] = $data->rupee_price;
            $febricDollrtAmount[$data->febric_id] = $data->dollar_price;
            $febricEuroAmount[$data->febric_id] = $data->euro_price;
           
        }
        if(!$febric->isEmpty())
        {
            View::share('febricImage', $febricImage);
            View::share('febricName', $febricName);
            View::share('febricAmount', $febricAmount);
            View::share('febricDollrtAmount', $febricDollrtAmount);
            View::share('febricEuroAmount', $febricEuroAmount);
        }
        
        return $next($request);
    }
}
