<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\DeviceParametersValues;
use Carbon\Carbon;
use http\Env\Response;
use Illuminate\Http\Request;

class DeviceApiController extends Controller
{
    public function index()
    {
        $devices = Device::all();
        $response = ['message' => 'index function'];
        return $devices;
    }

    public function show($id)
    {
        $device = Device::findOrfail($id);
        return $device;
    }

    public function store( Request $request)
    {

        $para = $request->getContent();
        $test = explode(',', $para);
        $device = Device::where('device_id', $test[0])->first();
        if ($device != null) {
            $type = $device->deviceType;
            foreach ($type->deviceParameters as $key => $parameter) {
                $parameters = new DeviceParametersValues();
                $jsonParameters[$parameter->name] = $test[$key + 1];
                $parameters->parameters = json_encode($jsonParameters);
                $parameters->device_id = $device->id;
                $parameters->time_of_read = last($test);

            }
            $parameters->save();


            $response = '##';
            if ($device->deviceSetting != null) {
                $x = json_decode($device->deviceSetting->settings, true);
                $x['time'] = gmdate("Y-m-dTH:i:s");
                foreach ($device->deviceType->deviceSettings as $setting) {
                    $response = $response . '' . $setting->name . '=' . $x[$setting->name] . ',';
                }
                $response = $response . '' . 'time=' . $x['time'];
            } else {
                foreach ($device->deviceType->deviceSettings as $setting) {
                    $response = $response . '' . $setting->name . '=' . $setting->pivot->value . ',';
                }
                $response = $response . '' . 'time=' . gmdate("Y-m-dTH:i:s");
            }
            return response()->json($response, 201);
        } else {
            return response()->json('device id is not exist', 404);
        }

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
