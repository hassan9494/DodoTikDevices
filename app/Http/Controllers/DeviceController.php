<?php

namespace App\Http\Controllers;

use App\Exports\ParametersDataExport;
use App\Http\Requests\DeviceRequest;
use App\Models\Component;
use App\Models\Device;
use App\Models\DeviceParameters;
use App\Models\DeviceParametersValues;
use App\Models\DevicesComponents;
use App\Models\DeviceSettingPerDevice;
use App\Models\DeviceType;
use App\Models\LimitValues;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

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
        return view('admin.device.index', compact('devices'));
    }


    public function add()
    {
        $devices = Device::all();
        return view('admin.device.add', compact('devices'));
    }

    public function get_devices()
    {
        $user = auth()->user();
        $devices = Device::where('user_id', $user->id)->get();
        return view('admin.device.get_devices', compact('devices'));
    }

    /**
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove_device($id)
    {
        $device = Device::findOrFail($id);
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
        $user = auth()->user();
        $device->user_id = $user->id;
        $device->name = $request->name;
        $device->device_id = $request->device_id;
        $device->type_id = $request->type;
        $device->time_between_two_read = $request->time_between_two_read;
        if ($device->save()) {
            if ($user->role == 'Administrator') {
                return redirect()->route('admin.devices')->with('success', 'Data added successfully');
            } else {
                return redirect()->route('admin.devices.get_devices')->with('success', 'Data added successfully');
            }
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
    public function show($id)
    {

        $device = Device::findOrFail($id);
        $parametersFromSetting = DevicesComponents::where('device_id' , $id)->get();
//dd(json_decode($parametersFromSetting->settings)->parameters);
//        dd($device->deviceComponent);
        if (count($parametersFromSetting) == 0){

            $components = Component::all();
//            $deviceComponent = $parametersFromSetting;
//            dd($deviceComponent);
            return view ('admin.device_components.init',compact('components','device'));
        }
        $device_type = $device->deviceType;
//        dd($device_type->deviceParameters);
        $now = Carbon::now();
        $parameters = $device->deviceParameters;
        $xValues = [];
        $yValues = [];
        $paraValues = [];
        $dangerColor = [];
        $testPara = [];
        $deviceComponent = DevicesComponents::where('device_id',$id)->where('component_id',9)->first();
        foreach (json_decode($deviceComponent->settings)->parameters as $key=>$test){
            $testPara[$key] = DeviceParameters::findOrFail((int)$test);
        }
        $lastPara = DeviceParametersValues::where('device_id', $id)->orderBy('id', 'desc')->first();
        if (count($parameters) > 0) {
            if ($now->diff(date("m/d/Y H:i", strtotime($lastPara->time_of_read)))->m == 0 && $now->diff(date("m/d/Y H:i", strtotime($lastPara->time_of_read)))->d == 0 && $now->diff(date("m/d/Y H:i", strtotime($lastPara->time_of_read)))->h == 0 && $now->diff(date("m/d/Y H:i", strtotime($lastPara->time_of_read)))->i <= $device->time_between_two_read) {
                $status = "Online";
            } else {
                $status = "Offline";
            }
            foreach ($parameters as $parameter) {
                if ($now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->d == 0 && $now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->m == 0 && $now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->y == 0) {
                    array_push($xValues, date(DATE_ISO8601, strtotime($parameter->time_of_read)));
                }
            }
            $warning = 1;
            $dangerColor = [];
            foreach ($testPara as $index => $tPara) {
                $dangerColor[$index] = '#000000';
                foreach ($parameters as $parameter) {
                    if ($now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->d == 0 && $now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->m == 0 && $now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->y == 0) {
                        array_push($yValues, json_decode($parameter->parameters, true)[$tPara->code]);
                    }
                }
                array_push($paraValues, $yValues);
                $yValues = [];
                if (isset($device->limitValues)) {
                    if ($device->limitValues->min_warning == 1) {
                        if (json_decode($parameters->last()->parameters, true)[$tPara->code] < json_decode($device->limitValues->min_value, true)[$tPara->code]) {
                            $warning += 1;
                            $dangerColor[$index] = 'red';
                        }
                    }
                    if ($device->limitValues->max_warning == 1) {
                        if (json_decode($parameters->last()->parameters, true)[$tPara->code] > json_decode($device->limitValues->max_value, true)[$tPara->code]) {
                            $warning += 1;
                            $dangerColor[$index] = 'red';
                        }
                    }
                }
            }

            $label = 1;
        } else {
            $warning = 1;
            $status = "Offline";
            $label = 1;
        }
        $deviceComponents = DevicesComponents::where('device_id',$device->id)->orderBy('order','asc')->get();
//        dd($testPara);

//        dd(json_decode($device->deviceComponent->settings));
        return view('admin.device.custom_show', compact('testPara','device','deviceComponents', 'dangerColor', 'warning', 'status', 'label', 'xValues', 'yValues', 'paraValues'));
    }


    public function showWithDate($id, $from, $to)
    {

        $device = Device::findOrFail($id);

        $device_type = $device->deviceType;
        $now = Carbon::now();
        if ($from == 7 && $to == 0) {
            $label = 7;
        } elseif ($from == 30 && $to == 0) {
            $label = 30;
        } else {
            $label = 2;
        }
        $parameters = $device->deviceParameters;
        $xValues = [];
        $yValues = [];
        $paraValues = [];
        $lastPara = DeviceParametersValues::where('device_id', $id)->orderBy('id', 'desc')->first();

        if (count($parameters) > 0) {
            if ($now->diff(date("m/d/Y H:i", strtotime($lastPara->time_of_read)))->h == 0 && $now->diff(date("m/d/Y H:i", strtotime($lastPara->time_of_read)))->i < $device->time_between_two_read) {
                $status = "Online";
            } else {
                $status = "Offline";
            }
            foreach ($parameters as $parameter) {
                if ($now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->d <= $from && $now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->d >= $to && $now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->m == 0 && $now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->y == 0) {
                    array_push($xValues, date(DATE_ISO8601, strtotime($parameter->time_of_read)));
                }
            }
            $warning = 1;
            foreach ($device_type->deviceParameters as $tPara) {
                foreach ($parameters as $parameter) {
                    if ($now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->d <= $from && $now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->d >= $to && $now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->m == 0 && $now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->y == 0) {
                        array_push($yValues, json_decode($parameter->parameters, true)[$tPara->code]);
                        if (isset($device->limitValues)) {
                            if ($device->limitValues->min_warning == 1) {
                                if (json_decode($parameter->parameters, true)[$tPara->code] < json_decode($device->limitValues->min_value, true)[$tPara->code]) {
                                    $warning += 1;
                                }
                            }
                            if ($device->limitValues->max_warning == 1) {
                                if (json_decode($parameter->parameters, true)[$tPara->code] > json_decode($device->limitValues->max_value, true)[$tPara->code]) {
                                    $warning += 1;
                                }
                            }
                        }
                    }
                }
                array_push($paraValues, $yValues);
                $yValues = [];
            }
        } else {
            $warning = 1;
            $status = "Offline";
            $label = 1;
        }
        return array($paraValues, $xValues, $device, $warning, $status, $label);
    }


    public function getColumnChartData($id)
    {
        $device = Device::findOrFail($id);
        $device_type = $device->deviceType;
        $parameters = $device->deviceParameters;
        $testPara = [];
        $deviceComponent = DevicesComponents::where('device_id',$id)->where('component_id',6)->first();
        foreach (json_decode($deviceComponent->settings)->parameters as $key=>$test){
            $testPara[$key] = DeviceParameters::findOrFail((int)$test);
        }
//        dd($parameters);
        $xValues = [];
        $yValues = [];
        $paraValues = [];
        $parametersFromSetting = DevicesComponents::where('device_id' , $id)->where('component_id',6)->get();
        $now = Carbon::now();
        $lastPara = DeviceParametersValues::where('device_id', $id)->orderBy('id', 'desc')->first();
        if (count($parameters) > 0) {


            foreach ($testPara as $index => $tPara) {

                foreach ($parameters as $parameter) {
                    if ($now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->d == 0 && $now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->m == 0 && $now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->y == 0) {
                        array_push($yValues, json_decode($parameter->parameters, true)[$tPara->code]);
                    }
                }
                $sum = 0;
                foreach ($yValues as $yValue) {
                    $sum += $yValue;
                }
                if (count($yValues) != 0){
                    array_push($paraValues, round($sum / count($yValues)));
                }


                $yValues = [];
            }
        } else {

        }
        $test1['paravalues'] = $paraValues;
        $test1['para'] = $testPara;
//        dd($test1);
        return array($test1);
    }


    public function getGaugeWithBandsData($id)
    {
        $device = Device::findOrFail($id);
        $device_type = $device->deviceType;
        $parameters = $device->deviceParameters;
        $yValues = [];
        $paraValues = [];

        $now = Carbon::now();
        $lastPara = DeviceParametersValues::where('device_id', $id)->orderBy('id', 'desc')->first();
        if (count($parameters) > 0) {


            foreach ($device_type->deviceParameters as $index => $tPara) {
                if ($tPara->code == "Temperature") {
                    foreach ($parameters as $parameter) {

                        if ($now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->d == 0 && $now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->m == 0 && $now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->y == 0) {
                            array_push($yValues, json_decode($parameter->parameters, true)[$tPara->code]);
//                        dd($tPara->code);
                        }
                    }
                    $sum = 0;
                    foreach ($yValues as $yValue) {
                        $sum += $yValue;
                    }
                    if (count($yValues) != 0) {
                        array_push($paraValues, round($sum / count($yValues)));
                    }


                    $yValues = [];
                }


            }
        } else {

        }

        return array($paraValues);
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

        return redirect()->route('admin.devices.show', $id)->with('success', 'Data updated successfully');


    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $typeid
     * @param int $settingid
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function add_device_limit_values($id)
    {
        $device = Device::findOrFail($id);
        return view('admin.device.add_device_limit_values', compact('device'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function add_limit_values(Request $request, $id)
    {
        $device = Device::findOrFail($id);
        $validate = [];
        foreach ($device->deviceType->deviceParameters as $limit) {
            $validate[$limit->code . "_max"] = "required|numeric";
            $validate[$limit->code . "_min"] = "required|numeric";
        }
        \Validator::make($request->all(), $validate)->validate();
        $devLim = LimitValues::where('device_id', $id)->first();
        $min = [];
        $max = [];
        if ($devLim != null) {
            foreach ($device->deviceType->deviceParameters as $para) {
                if ($request[$para->code . "_min"] != null && $request[$para->code . "_max"] != null) {
                    $min[$para->code] = $request[$para->code . "_min"];
                    $max[$para->code] = $request[$para->code . "_max"];
                } else {
                    $min[$para->code] = "0";
                    $max[$para->code] = "0";
                }
            }
        } else {
            $devLim = new LimitValues();
            foreach ($device->deviceType->deviceParameters as $para) {
                if ($request[$para->code . "_min"] != null) {
                    $min[$para->code] = $request[$para->code . "_min"];
                    $max[$para->code] = $request[$para->code . "_max"];
                } else {
                    $min[$para->code] = "0";
                    $max[$para->code] = "0";
                }
            }
            $devLim->device_id = $id;
        }
        if ($request['min_warning'] == 'on') {
            $devLim->min_warning = true;
        } else {
            $devLim->min_warning = false;
        }
        if ($request['max_warning'] == 'on') {
            $devLim->max_warning = true;
        } else {
            $devLim->max_warning = false;
        }
        $devLim->min_value = json_encode($min);
        $devLim->max_value = json_encode($max);
        $devLim->save();

        return redirect()->route('admin.devices', $id)->with('success', 'Data updated successfully');


    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
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
        $device = Device::findOrFail($id);
        $device->name = $request->name;
        $device->device_id = $request->device_id;
        $user = auth()->user();
        $device->user_id = $user->id;
        $device->time_between_two_read = $request->time_between_two_read;
        $device->type_id = $request->type;
        $device->longitude = $request->longitude;
        $device->latitude = $request->latitude;
        if ($device->save()) {
            if ($user->role == 'Administrator') {

                return redirect()->route('admin.devices')->with('success', 'Data added successfully');
            } else {

                return redirect()->route('admin.devices.get_devices')->with('success', 'Data added successfully');
            }
        } else {

            return redirect()->route('admin.devices.edit')->with('error', 'Data failed to add');

        }
    }

    public function location($id)
    {
        $device = Device::findOrFail($id);

        return view('admin.device.location', compact('device'));
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
        $device = Device::findOrFail($id);
        $device->longitude = $request->longitude;
        $device->latitude = $request->latitude;
        if ($device->save()) {
            return redirect()->route('admin.devices', $id)->with('success', 'Data updated successfully');

        } else {

            return redirect()->route('admin.devices.location', $id)->with('error', 'Data failed to updated');

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


    public function export($id)
    {
        $device = Device::findOrFail($id);
        return view('admin.device.export', compact('device'));
        return Excel::download(new ParametersDataExport($from, $to, $devType), 'parameter.xlsx');
    }

    public function exportToDatasheet(Request $request)
    {
        $validate = [
            'from' => 'required',
            'to' => 'required'
        ];
        \Validator::make($request->all(), $validate)->validate();
        $from = $request->from;
        $to = $request->to;
        $dev = $request->id;
        return Excel::download(new ParametersDataExport($from, $to, $dev), 'parameter.xlsx');
    }
}
