<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Validator;

use Laravel\Dusk\DuskServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('geolocation', function($attribute, $value, $parameters, $validator) {
        	$positions = explode(',', $value);
        	$pass = true;
        	
        	foreach($positions as $positionIndex => $position){
        		if(!is_numeric($position)){
        			$pass = false;
        		}
        	}
        	
        	return $pass;
        });
        
        Schema::defaultStringLength(191);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    	if ($this->app->environment('local', 'testing')) {
    		$this->app->register(DuskServiceProvider::class);
    	}
    }
}
