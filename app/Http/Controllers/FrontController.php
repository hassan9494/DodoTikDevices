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
