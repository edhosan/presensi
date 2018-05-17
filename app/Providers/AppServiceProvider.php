<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Validator;
use App\Model\OPD;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('is_exists_opd', function($attribute, $value, $parameters, $validator) {        
            $opd = OPD::where('id_unker','=',$value)->first();
            if($opd != null) {
              return true;
            }
            return false;
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
