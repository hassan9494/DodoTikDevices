<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\{About,
    Device,
    DeviceParametersValues,
    General,
    User};

class GeneralController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        $now = Carbon::now();
        if ($user->role == 'Administrator') {
            $admin = User::orderBy('id', 'desc')->count();
            $devices = Device::all();
        } else {
            $admin = User::orderBy('id', 'desc')->count();
            $devices = Device::where('user_id', $user->id)->get();
        }
        $state = [];
        $status = "Offline";
        $warning = [];
        $lastMinDanger = [];
        $lastdangerRead = [];
        $long = 0;
        $lat = 0;
        foreach ($devices as $key=>$device){
            $long += $device->longitude;
            $lat += $device->latitude;
            $warning[$key] = 0;
            $lastMinDanger[$key] = null;
            $lastdangerRead[$key] = ["#000000","#000000","#000000","#000000","#000000","#000000","#000000","#000000",];
            $parameters = $device->deviceParameters;
            $lastPara = DeviceParametersValues::where('device_id', $device->id)->orderBy('id', 'desc')->first();
            if (count($parameters) > 0) {
                if ($now->diff(date("m/d/Y H:i", strtotime($lastPara->time_of_read)))->h == 0 && $now->diff(date("m/d/Y H:i", strtotime($lastPara->time_of_read)))->i < $device->time_between_two_read) {
                    $status = "Online";
                } else {
                    $status = "Offline";
                }
            }else{
                $status = "Offline";
            }
            array_push($state,$status );
            foreach ($device->deviceType->deviceParameters as $key2=>$tPara) {
                        if (isset($device->limitValues)) {
                            if ($device->limitValues->min_warning == 1) {
//                                dd(json_decode($device->deviceParameters->last()->parameters, true)[$tPara->code] );
                                if ($device->deviceParameters->last() != null)
                                if (json_decode($device->deviceParameters->last()->parameters, true)[$tPara->code] < json_decode($device->limitValues->min_value, true)[$tPara->code]) {

                                    $warning[$key] += 1;
                                    $lastMinDanger[$key] = $device->deviceParameters->last();
                                    $lastdangerRead[$key][$key2] = "red";
                                }
                            }
                            if ($device->limitValues->max_warning == 1) {
                                if ($device->deviceParameters->last() != null)
                                if (json_decode($device->deviceParameters->last()->parameters, true)[$tPara->code] > json_decode($device->limitValues->max_value, true)[$tPara->code]) {
                                    $warning[$key] += 1;
                                    $lastMinDanger[$key] = $device->deviceParameters->last();
                                    $lastdangerRead[$key][$key2] = "red";
                                }
                            }
                        }
            }
        }
        if (count($devices) > 0){
            $long = $long / count($devices);
            $lat = $lat / count($devices);
        }

        return view('admin.dashboard', compact('admin','long','lat','lastdangerRead', 'devices','state','warning','lastMinDanger'));
    }

    public function general()
    {
        $general = General::find(1);
        return view('admin.general', [
            'general' => $general
        ]);
    }

    public function generalUpdate(Request $request)
    {
        \Validator::make($request->all(), [

            "title" => "required",
            "address1" => "required",
            "phone" => "required",
            "email" => "required",
            "footer" => "required",
            "gmaps" => "required"
        ])->validate();

        $general = General::find(1);
        $general->title = $request->title;
        $general->address1 = $request->address1;
        $general->address2 = $request->address2;
        $general->phone = $request->phone;
        $general->email = $request->email;
        $general->twitter = $request->twitter;
        $general->facebook = $request->facebook;
        $general->instagram = $request->instagram;
        $general->linkedin = $request->linkedin;
        $general->footer = $request->footer;
        $general->gmaps = $request->gmaps;
        $general->tawkto = $request->tawkto;
        $general->disqus = $request->disqus;
        $general->sharethis = $request->sharethis;
        $general->gverification = $request->gverification;
        $general->keyword = $request->keyword;
        $general->meta_desc = $request->meta_desc;

        $new_logo = $request->file('logo');

        if ($new_logo) {
            if ($general->logo && file_exists(storage_path('app/public/' . $general->logo))) {
                \Storage::delete('public/' . $general->logo);
            }

            $new_cover_path = $new_logo->store('images/general', 'public');

            $general->logo = $new_cover_path;
        }

        $new_favicon = $request->file('favicon');

        if ($new_favicon) {
            if ($general->favicon && file_exists(storage_path('app/public/' . $general->favicon))) {
                \Storage::delete('public/' . $general->favicon);
            }

            $new_cover_path = $new_favicon->store('images/general', 'public');

            $general->favicon = $new_cover_path;
        }
        if ($general->save()) {

            return redirect()->route('admin.general')->with('success', 'Data updated successfully');

        } else {

            return redirect()->route('admin.general')->with('error', 'Data failed to update');

        }
    }

    public function about()
    {
        $about = About::find(1);
        return view('admin.about', [
            'about' => $about
        ]);
    }

    public function aboutUpdate(Request $request)
    {
        $about = About::find(1);
        $about->title = $request->title;
        $about->subject = $request->subject;
        $about->desc = $request->desc;

        if ($about->save()) {

            return redirect()->route('admin.about')->with('success', 'Data updated successfully');

        } else {

            return redirect()->route('admin.about')->with('error', 'Data failed to update');

        }

    }
}
