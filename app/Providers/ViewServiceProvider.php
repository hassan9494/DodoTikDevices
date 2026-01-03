<?php

namespace App\Providers;

use App\Http\View\Composers\GlobalComposer;
use App\Http\View\Composers\SiteComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
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
        View::composer('*', GlobalComposer::class);
        View::composer('site.partials.header', SiteComposer::class);
    }
}
