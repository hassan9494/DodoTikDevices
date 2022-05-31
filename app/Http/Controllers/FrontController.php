<?php

namespace App\Http\Controllers;

use App\Models\{About,
    General
};

class FrontController extends Controller
{
    public function home()
    {

        return view('auth.login');
    }

    public function about()
    {
        $about = About::find(1);
        $faq = Faq::all();
        $general = General::find(1);
        $link = Link::orderBy('name', 'asc')->get();
        $lpost = Post::where('status', '=', 'PUBLISH')->orderBy('id', 'desc')->limit(5)->get();
        $partner = Partner::orderBy('name', 'asc')->get();
        $team = Team::orderBy('id', 'asc')->get();
        return view('front.about', compact('about', 'faq', 'general', 'link', 'lpost', 'partner', 'team'));
    }

    public function contact()
    {
        $about = About::find(1);
        $faq = Faq::all();
        $general = General::find(1);
        $link = Link::orderBy('name', 'asc')->get();
        $lpost = Post::where('status', '=', 'PUBLISH')->orderBy('id', 'desc')->limit(5)->get();
        $partner = Partner::orderBy('name', 'asc')->get();
        $team = Team::orderBy('id', 'asc')->get();
        return view('front.cotact', compact('about', 'faq', 'general', 'link', 'lpost', 'partner', 'team'));
    }

    public function migrate()
    {
        try {
            \Artisan::call("migrate");
            return \Artisan::call("migrate");
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }

    }

}
