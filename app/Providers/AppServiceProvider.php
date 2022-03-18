<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        // $this->app->forgetMiddleware('Illuminate\Http\FrameGuard');
        if(env('APP_ENV') === 'production') {

            $this->app['request']->server->set('HTTPS', true);

        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Validator::extend('register_brand_unqiue', function ($attribute, $value, $parameters, $validator) {
            echo $attribute.' value '. $value. ' parameter '.$parameters.' validator '.$validator;
            return $value;
        });
    }
}
