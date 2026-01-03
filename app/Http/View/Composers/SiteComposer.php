<?php

namespace App\Http\View\Composers;

use App\Models\Category;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class SiteComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        $view->with('categories', $this->categories());
    }

    private function categories()
    {
        return Cache::remember('site.categories', 60, function () {
            return $this->tree(Category::all());
        });
    }

    private function tree($elements)
    {
        // TODO: port tree-building logic when available in legacy project.
        return $elements;
    }
}
