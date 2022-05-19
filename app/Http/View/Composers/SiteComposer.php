<?php

namespace App\Http\View\Composers;

use App\Models\Category;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;
use Backpack\Settings\app\Models\Setting;

class SiteComposer
{

    /**
     * Create a new site composer.
     * @return void
     */
    public function __construct()
    {
        // Dependencies are automatically resolved by the service container...
    }

    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\View\View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('categories', $this->categories());
    }

    private function categories()
    {
        return Cache::remember(
            'site.categories', 60, fn () => $this->tree(Category::all())
        );
    }

    private function tree($elements)
    {
    }

}
