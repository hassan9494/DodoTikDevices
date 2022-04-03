<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\DeviceParametersValues;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DeviceApiController extends Controller
{
    public function index()
    {
        $devices = Device::all();
        $response = ['message' =>  'index function'];
        return $devices;
    }

    public function show($id)
    {
        $device = Device::findOrfail($id);
        return $device;
    }

    public function store(Request $request)
    {
        $para = $request['parameters'];
        $test = explode(',',$para);

        $device = Device::where('device_id',$test[0])->first();
        $type = $device->deviceType;
        foreach ($type->deviceParameters as $key=>$parameter){
            $parameters = new DeviceParametersValues();
            $jsonParameters[$parameter->name] = $test[$key+1];
            $parameters->parameters = json_encode($jsonParameters);
            $parameters->device_id = $device->id;
            $parameters->time_of_read = last($test);

        }
        $parameters->save();



        if ($device->deviceSetting != null){
            $x = json_decode($device->deviceSetting->settings,true) ;
            $x['time'] = gmdate('H:i',time());
        }else{

        }


        return response()->json($x, 201);
    }

    public function update(Request $request, $id)
    {
        $device = Device::findOrfail($id);
        $device->update($request->all());

        return response()->json($device, 200);
    }

    public function delete($id)
    {
        $device = Device::findOrfail($id);
        $device->delete();

        return response()->json(null, 204);
    }
}
