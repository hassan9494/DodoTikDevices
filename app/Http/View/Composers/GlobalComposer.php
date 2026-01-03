<?php

namespace App\Http\View\Composers;

use App\Models\Device;
use App\Models\DeviceType;
use App\Models\General;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class GlobalComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        $data = $this->website();
        $data['settings'] = $this->settings();

        $view->with($data);
    }

    private function settings()
    {
        return Cache::remember('global-settings', 120, function () {
            return General::all()->keyBy('key');
        });
    }

    private function website(): array
    {
        $user = auth()->user();

        if ($user === null) {
            return ['all_devices' => collect()];
        }

        $cacheKey = sprintf('website-user-%d', $user->id);

        return Cache::remember($cacheKey, 120, function () use ($user) {
            $all_devices = $user->role === 'Administrator'
                ? Device::all()
                : Device::where('user_id', $user->id)->get();

            return ['all_devices' => $all_devices];
        });
    }
}
