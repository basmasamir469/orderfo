<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        Schema::defaultStringLength(191);
        if(!$this->app->request->is('api/*')){
            request()->headers->set('Accept-Language','ar-sa,ar:q=0.9');
        }
        $lang=request()->header('X-Language')??'ar';
        $lang=str_contains($lang,'en')?'en':'ar';
        App::setLocale($lang);

    }
}
