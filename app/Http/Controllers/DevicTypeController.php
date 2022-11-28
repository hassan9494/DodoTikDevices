<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeviceTypesRequest;
use App\Models\DeviceParameters;
use App\Models\DeviceSettings;
use App\Models\DeviceType;
use App\Models\DeviceTypeParameter;
use App\Models\DeviceTypeSetting;
use Illuminate\Http\Request;

class DevicTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()

    {
        $types = DeviceType::all();
        return view('admin.device_types.index',compact('types'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create()
    {
        $parameters = DeviceParameters::all();
        $devSettings = DeviceSettings::all();
        return view ('admin.device_types.create',compact('parameters','devSettings'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(DeviceTypesRequest $request)
    {
        $type = new DeviceType();
        $type->name = $request->name;
        if ($request->is_gateway == 'on'){
            $type->is_gateway = 1;
            $type->encode_type = $request->encode_type;
        }
        if ($request->is_need_response == 'on'){
            $type->is_need_response = 1;
        }
        if ($type->save()) {

            $type->deviceSettings()->attach(request('settings'));
            $type->deviceParameters()->attach(request('parameters'));
            return redirect()->route('admin.device_types.add_default_values',$type->id)->with('success', 'Data added successfully');
        }else {

            return redirect()->route('admin.device_types.create')->with('error', 'Data failed to add');

        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $typeid
     * @param  int  $settingid
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function add_default_values($typeid)
    {
        $type = DeviceType::findOrFail($typeid);
        $parameters = DeviceParameters::all();
        $settings = DeviceSettings::all();
        return view('admin.device_types.add_default_values',compact('type','parameters','settings'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function add_default(Request $request, $id)
    {
        $type = DeviceType::findOrFail($id);
        foreach ($type->deviceSettings as $setting){
            $typeSet = DeviceTypeSetting::where('device_settings_id',$setting->id)->where('device_type_id',$id)->first();
            if ($request[$setting->name] != null){
                $typeSet->value = $request[$setting->name];
            }else{
                $typeSet->value = 0;
            }
            $typeSet->save();
        }
        foreach ($type->deviceParameters()->orderBy('order')->get() as $key=>$parameter){
            $typePara = DeviceTypeParameter::where('device_parameters_id',$parameter->id)->where('device_type_id',$id)->first();
            if ($request[\Str::slug($parameter->code).'_'.$key] != null){
                $typePara->order = $request[\Str::slug($parameter->code).'_'.$key];
            }else{
                $typePara->order = 0;
            }
            if ($request[\Str::slug($parameter->code).'_'.$key.'_length'] != null){
                $typePara->length = $request[\Str::slug($parameter->code).'_'.$key.'_length'];
            }else{
                $typePara->length = 4;
            }
            if ($request[\Str::slug($parameter->code).'_'.$key.'_rate'] != null){
                $typePara->rate = $request[\Str::slug($parameter->code).'_'.$key.'_rate'];
            }else{
                $typePara->rate = 4;
            }
            if ($request[\Str::slug($parameter->code).'_'.$key.'_color'] != null){
                $typePara->color = $request[\Str::slug($parameter->code).'_'.$key.'_color'];
            }else{
                $typePara->color = '#000000';
            }

            $typePara->save();
        }
        return redirect()->route('admin.device_types')->with('success', 'Data updated successfully');


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function edit($id)
    {
        $type = DeviceType::findOrFail($id);
        $parameters = DeviceParameters::all();
        $devSettings = DeviceSettings::all();
        return view('admin.device_types.edit',compact('type','parameters','devSettings'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DeviceTypesRequest $request, $id)
    {

        $type = DeviceType::findOrFail($id);
        $type->name = $request->name;
        if ($request->is_gateway == 'on'){
            $type->is_gateway = 1;
            $type->encode_type = $request->encode_type;
        }else{
            $type->is_gateway = 0;
            $type->encode_type = 1;
        }
        if ($request->is_need_response == 'on'){
            $type->is_need_response = 1;
        }else{
            $type->is_need_response = 0;
        }
        if ( $type->save()) {
            $type->deviceSettings()->sync(request('settings'));
            $type->deviceParameters()->sync(request('parameters'));

            return redirect()->route('admin.device_types')->with('success', 'Data updated successfully');

        } else {

            return redirect()->route('admin.device_types.edit')->with('error', 'Data failed to update');

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $type = DeviceType::findOrFail($id);
        $type->delete();
        $type->deviceSettings()->detach();
        $type->deviceParameters()->detach();

        return redirect()->route('admin.device_types')->with('success', 'Data deleted successfully');
    }
}
