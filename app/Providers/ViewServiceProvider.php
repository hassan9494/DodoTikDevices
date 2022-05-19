<?php

namespace App\Providers;

use App\Http\View\Composers\GlobalComposer;
use App\Http\View\Composers\ProfileComposer;
use App\Http\View\Composers\SiteComposer;
use Backpack\Settings\app\Models\Setting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
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

        // Using closure based composers...
        View::composer('*', GlobalComposer::class);
        View::composer('site.partials.header', SiteComposer::class);
    }
}
