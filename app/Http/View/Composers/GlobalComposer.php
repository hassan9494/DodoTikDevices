<?php

namespace App\Http\View\Composers;

use App\Models\Device;
use App\Models\DeviceType;
use App\Models\General;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class GlobalComposer
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
        $data = $this->website();
        $data['settings'] = $this->settings();
        $view->with($data);
    }

    private function settings()
    {
        return Cache::remember(
        /**
         * @return Setting[]|\Illuminate\Database\Eloquent\Collection
         */ 'global-settings',
            120,
            function () {
                return General::all()->keyBy('key');
            }
        );
    }

    private function website()
    {
        return Cache::remember('website',120, function (){
            $user = auth()->user();
//            dd($user);
            if ($user != null){
                if ($user->role == 'Administrator') {
                    $admin = User::orderBy('id', 'desc')->count();
                    $all_devices = Device::all();
                } else {
                    $admin = User::orderBy('id', 'desc')->count();
                    $all_devices = Device::where('user_id', $user->id)->get();
                }
//            dd($all_devices);
                return compact('all_devices');
            }

        });
    }

}
