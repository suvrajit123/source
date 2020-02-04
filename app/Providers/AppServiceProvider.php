<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Log;
use DB;
use App\Visitor;
use Illuminate\Support\Carbon;

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
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Schema::defaultStringLength(191); //NEW: Increase StringLength
        DB::listen(function($query){
            Log::info($query->sql,$query->bindings);
        });

        /*$visitors = Visitor::where('host', $_SERVER['REMOTE_ADDR'])->whereDate('created_at', Carbon::today())->first();
        if (blank($visitors)) {
            Visitor::create(['host' => $_SERVER['REMOTE_ADDR']]);
        }*/

    }
}
