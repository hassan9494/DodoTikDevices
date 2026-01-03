<?php

namespace App\Http\Controllers;

use App\Models\{About,
    General
};

class FrontController extends Controller
{
    public function home()
    {
        if (auth()->check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('auth.login');
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
