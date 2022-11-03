<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\DeviceParametersValues;
use App\Models\TestApi;
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
        $testsApi = new TestApi();
        $testsApi->settings = json_encode($para);
//        $testsApi->save();
        $test = explode(',', $para);
//        dd($test[0]);
        $device = Device::where('device_id', $test[0])->first();
        if ($device != null) {
            $type = $device->deviceType;
            foreach ($type->deviceParameters()->orderBy('order')->get() as $key => $parameter) {
                $parameters = new DeviceParametersValues();
                $jsonParameters[$parameter->code] = $test[$key + 1];
                $parameters->parameters = json_encode($jsonParameters);
                $parameters->device_id = $device->id;
                if (count($test) == count($type->deviceParameters) + 2){
                    $parameters->time_of_read = last($test);
                }else{
                    $parameters->time_of_read = Carbon::now();
                }

            }
            $parameters->save();
            $response = '##';
            if ($type->is_need_response ==1){
                if ($device->deviceSetting != null) {
                    $x = json_decode($device->deviceSetting->settings, true);
                    $x['time'] = gmdate("Y-m-dTH:i:s");
                    foreach ($device->deviceType->deviceSettings as $setting) {
                        $response = $response . '' . $setting->name . '=' . $x[$setting->name] . ',';
                    }
                    $response = $response . '' . 'time=' . $x['time'];
                    return response()->json($response, 201);
                }
                else {
                    foreach ($device->deviceType->deviceSettings as $setting) {
                        $response = $response . '' . $setting->name . '=' . $setting->pivot->value . ',';
                    }
                    $response = $response . '' . 'time=' . gmdate("Y-m-dTH:i:s");
                }
            }


        } else {
            return response()->json('device id is not exist', 404);
        }
    }

    public function read1( Request $request)
    {

        $para = $request->getContent();
        $testsApi = new TestApi();
        $testsApi->settings = json_encode($para);
//        $testsApi->save();
        if (isset(json_decode($para)->data) ){
            $dev_id_base64= json_decode($para)->devEUI;
            $dev_id_ascii = base64_decode($dev_id_base64);
            $dev_id = bin2hex($dev_id_ascii);
            $device = Device::where('device_id', $dev_id)->first();
            if ($device != null){
                $data_base64 = json_decode($para)->data;
                $data_ascii = base64_decode($data_base64);
                $finaldata = bin2hex($data_ascii);
//                $data1 = substr($data, 10);
//                $finaldata = substr($data1, 0, -2);

                $vol = substr($finaldata, 0, 4);
                $vol_dec = hexdec( $vol );
                $last_vol = $vol_dec / 10;

                $temp = substr($finaldata, 4, 4);
                $temp_dec = hexdec( $temp );
                $last_temp = $temp_dec / 10;

                $hum = substr($finaldata, 8, 4);
                $hum_dec = hexdec( $hum);
                $last_hum = $hum_dec / 10;

                $gaz = substr($finaldata, 12, 8);
                $gaz_dec = hexdec( $gaz);
                $last_gaz = $gaz_dec / 1000;

                $type = $device->deviceType;
                foreach ($type->deviceParameters as $key => $parameter) {
                    $parameters = new DeviceParametersValues();
                    if ($parameter->code == "Bat_v"){
                        $jsonParameters[$parameter->code] = $last_vol;
                    }elseif ($parameter->code == "Temperature"){
                        $jsonParameters[$parameter->code] = $last_temp;
                    }elseif ($parameter->code == "Humidity"){
                        $jsonParameters[$parameter->code] = $last_hum;
                    }elseif ($parameter->code == "Gas_Resistance"){
                        $jsonParameters[$parameter->code] = $last_gaz;
                    }
                    $parameters->parameters = json_encode($jsonParameters);
                    $parameters->device_id = $device->id;
                    $parameters->time_of_read = Carbon::now();
                }
                $parameters->save();
            }
            if ($device != null) {
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
        }else{
            return response()->json('No data was sent', 404);
        }
    }

    public function read( Request $request)
    {

        $para = $request->getContent();
        $testsApi = new TestApi();
        $testsApi->settings = json_encode($para);
//        $testsApi->save();
        if (isset(json_decode($para)->data) ){
            $dev_id_base64= json_decode($para)->devEUI;
            $dev_id_ascii = base64_decode($dev_id_base64);
            $dev_id = bin2hex($dev_id_ascii);
            $device = Device::where('device_id', $dev_id)->first();
            if ($device != null){
                $data_base64 = json_decode($para)->data;
                $data_ascii = base64_decode($data_base64);
                $finaldata = bin2hex($data_ascii);
//                $data1 = substr($data, 10);
//                $finaldata = substr($data1, 0, -2);
                $index = 0;
                foreach ($device->deviceType->deviceParameters()->orderBy('order')->get() as $key=>$para){
                    $paraRead[$key] = substr($finaldata, $index, $para->pivot->length);
                    $paraRead_dec[$key] = hexdec( $paraRead[$key] );
                    $last_paraRead[$key] = $paraRead_dec[$key] / $para->pivot->rate;
                    $index += $para->pivot->length;
                }

                $type = $device->deviceType;
                foreach ($type->deviceParameters()->orderBy('order')->get() as $key1 => $parameter) {
                    $parameters = new DeviceParametersValues();
                    $jsonParameters[$parameter->code] = $last_paraRead[$key1];
                    $parameters->parameters = json_encode($jsonParameters);
                    $parameters->device_id = $device->id;
                    $parameters->time_of_read = Carbon::now();
                }
                $parameters->save();
            }
            if ($device != null) {
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
        }else{
            return response()->json('No data was sent', 404);
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
