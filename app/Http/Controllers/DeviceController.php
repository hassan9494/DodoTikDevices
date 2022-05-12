<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeviceRequest;
use App\Models\Device;
use App\Models\DeviceParameters;
use App\Models\DeviceSettingPerDevice;
use App\Models\DeviceSettings;
use App\Models\DeviceType;
use App\Models\DeviceTypeSetting;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        $devices = Device::all();
        $x = 'test';
        return view('admin.device.index', compact('devices','x'));
    }


    public function add()
    {
        $devices = Device::all();
//        dd($devices);
        return view('admin.device.add', compact('devices'));
    }

    public function get_devices()
    {
        $user = auth()->user();
//        dd($user->id);
        $devices = Device::where('user_id', $user->id)->get();
//        dd($devices);
        return view('admin.device.get_devices', compact('devices'));
    }

    /**
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function add_device($id)
    {
        $device = Device::findOrFail($id);
        $user = auth()->user();
        $device->user_id = $user->id;
        $device->save();

        return redirect()->route('admin.devices.add')->with('success', 'Device added successfully');
    }

    /**
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function remove_device($id)
    {
        $device = Device::findOrFail($id);
        $user = auth()->user();
        $device->user_id = 0;
        $device->save();

        return redirect()->route('admin.devices.get_devices')->with('success', 'Device added successfully');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create()
    {
        $types = DeviceType::all();
        return view('admin.device.create', compact('types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(DeviceRequest $request)
    {
        $device = new Device();
        $device->name = $request->name;
        $device->device_id = $request->device_id;
        $device->user_id = 0;
        $device->type_id = $request->type;
        if ($device->save()) {
            return redirect()->route('admin.devices')->with('success', 'Data added successfully');
        } else {

            return redirect()->route('admin.devices.create')->with('error', 'Data failed to add');

        }

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function show($id,$zone)
    {
        $device = Device::findOrFail($id);
        $device_type = $device->deviceType;
        $now = Carbon::now();
        if ((int)$zone > 0){
            $n = $now->subMinutes(abs($zone));
        }else{
            $n = $now->addMinutes(abs($zone));
        }
        $parameters = $device->deviceParameters;
        $xValues = [];
        $yValues = [];
        $paraValues = [];
        foreach ($parameters as $parameter) {
//dd(getDate(strtotime($parameter->time_of_read))['minutes']);
//            dd(abs(strtotime($now) - strtotime($parameter->time_of_read))/(60*60));
//            if ( abs(strtotime($now) - strtotime($parameter->time_of_read))/(60*60) < 12){
            if ($now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->d == 0) {
                array_push($xValues, date(DATE_ISO8601, strtotime($parameter->time_of_read)));
            }

        }
//        dd($xValues);
        foreach ($device_type->deviceParameters as $tPara) {
            foreach ($parameters as $parameter) {
                if ($now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->d == 0) {
                    array_push($yValues, json_decode($parameter->parameters, true)[$tPara->code]);
                }

            }
            array_push($paraValues, $yValues);
            $yValues = [];
        }
        return view('admin.device.show', compact('device', 'xValues', 'yValues', 'paraValues'));
    }


    public function showWithDate($id,$from,$to)
    {

        $device = Device::findOrFail($id);
        $device_type = $device->deviceType;
        $now = Carbon::now();
        $parameters = $device->deviceParameters;
        $xValues = [];
        $yValues = [];
        $paraValues = [];
        foreach ($parameters as $parameter) {
//
//            dd(abs(strtotime($now) - strtotime($parameter->time_of_read))/(60*60));
//            if ( abs(strtotime($now) - strtotime($parameter->time_of_read))/(60*60) < 12){
            if ($now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->d == 0) {
                array_push($xValues, date(DATE_ISO8601, strtotime($parameter->time_of_read)));
            }

        }
//        dd($xValues);
        foreach ($device_type->deviceParameters as $tPara) {
            foreach ($parameters as $parameter) {
                if ($now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->d == 0) {
                    array_push($yValues, json_decode($parameter->parameters, true)[$tPara->code]);
                }

            }
            array_push($paraValues, $yValues);
            $yValues = [];
        }
        return view('admin.device.show', compact('device', 'xValues', 'yValues', 'paraValues'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $typeid
     * @param int $settingid
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function add_device_setting_values($id)
    {
        $device = Device::findOrFail($id);
        return view('admin.device.add_device_setting_values', compact('device'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function add_setting_values(Request $request, $id)
    {
        $device = Device::findOrFail($id);
        $devSet = DeviceSettingPerDevice::where('device_id', $id)->first();
        $test = [];
        if ($devSet != null) {
            foreach ($device->deviceType->deviceSettings as $setting) {
                if ($request[$setting->name] != null) {
                    $test[$setting->code] = $request[$setting->name];
                } else {
                    $test[$setting->code] = "0";
                }

//                dd($setting);
            }
        } else {
            $devSet = new DeviceSettingPerDevice();
            foreach ($device->deviceType->deviceSettings as $setting) {
                if ($request[$setting->name] != null) {
                    $test[$setting->code] = $request[$setting->name];
                } else {
                    $test[$setting->code] = "0";
                }
            }
            $devSet->device_id = $id;
        }
        $devSet->settings = json_encode($test);
        $devSet->save();
//        dd($test);

        return redirect()->route('admin.devices.show', $id)->with('success', 'Data updated successfully');


    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $device = Device::findOrFail($id);
        $types = DeviceType::all();
        return view('admin.device.edit', compact('device', 'types'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(DeviceRequest $request, $id)
    {
//        dd($request);
        $device = Device::findOrFail($id);
        $device->name = $request->name;
        $device->device_id = $request->device_id;
        $device->user_id = 0;
        $device->type_id = $request->type;
        $device->longitude = $request->longitude;
        $device->latitude = $request->latitude;
        if ($device->save()) {
            return redirect()->route('admin.devices')->with('success', 'Data added successfully');

        } else {

            return redirect()->route('admin.devices.edit')->with('error', 'Data failed to add');

        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update_location(Request $request, $id)
    {
//        dd($request);
        $device = Device::findOrFail($id);
        $device->longitude = $request->longitude;
        $device->latitude = $request->latitude;
        if ($device->save()) {
            return redirect()->route('admin.devices.show', $id)->with('success', 'Data updated successfully');

        } else {

            return redirect()->route('admin.devices.show', $id)->with('error', 'Data failed to updated');

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $device = Device::findOrFail($id);

        $device->delete();

        return redirect()->route('admin.devices')->with('success', 'Data deleted successfully');
    }
}
