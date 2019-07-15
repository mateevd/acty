<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
	    Schema::defaultStringLength(191);
	    setlocale(LC_ALL, "fr_FR.UTF8", 'French_France', 'French');
	    Carbon::setLocale("fr_FR");
	
	    $config_name = DB::table('config_app')
		    ->where('name', 'like', 'app_name')
		    ->get(['details']);
	    $config_logo = DB::table('config_app')
		    ->where('name', 'like', 'app_logo')
		    ->get(['details']);
	
	    Session::put('app_name', $config_name[0]->details);
	    Session::put('app_logo', $config_logo[0]->details);
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
