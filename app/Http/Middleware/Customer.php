<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Brand;
use Illuminate\Support\Facades\Auth;

class Customer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next,$guard = 'customer')
    {   

        $input = $request->all();

        if(env('APP_ENV') == 'local') {

            $sub_domain = (explode('/', $_SERVER['REQUEST_URI']))[1];
    
            $game = (explode('.', $_SERVER['SERVER_NAME']))[0];
    
        } else {

            $sub_domain = (explode('/', $_SERVER['REQUEST_URI']))[1];
    
            $game = (explode('.', $_SERVER['HTTP_HOST']))[0];
    
        }
    
        $brand = Brand::whereSubdomain($sub_domain)->first();

        if(strtolower($brand->game->name) !== strtolower($game)) {
            
            // Auth::guard('customer')->logout();
            // dd('test1');
            // dd('location: https://"'.$brand->game->name.'".casinoauto.io/"'.$brand->subdomain.'"/member/');

        }
        
        if ((Auth::guard($guard)->check())) {

            if((Auth::guard($guard)->user()->brand_id == $brand->id)) {

                return $next($request);

            } 
            else {
                
                // Auth::guard('customer')->logout();
                // dd('test2');
                // dd('location: https://"'.$brand->game->name.'".casinoauto.io/"'.$brand->subdomain.'"/member/');
                // header( "location: https://".$brand->game->name.".casinoauto.io/".$brand->subdomain."/member/redirect" );
                // exit();

            }

        }

        return redirect()->route(strtolower($brand->game->name).'.member.login', $brand->subdomain);

    }
}
