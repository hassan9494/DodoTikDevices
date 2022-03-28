<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeviceTypesRequest;
use App\Models\DeviceParameters;
use App\Models\DeviceSettings;
use App\Models\DeviceType;
use App\Models\DeviceTypeSetting;
use Illuminate\Http\Request;

class DevicTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
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
        $settings = DeviceSettings::all();
        return view ('admin.device_types.create',compact('parameters','settings'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(DeviceTypesRequest $request)
    {
//        dd($request);
        $type = new DeviceType();
        $type->name = $request->name;
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
//        $typeSetting = DeviceTypeSetting::where('device_settings_id',$settingid)->where('device_type_id',$typeid)->first();


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
            $typeSet->value = $request[$setting->name];
            $typeSet->save();
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
        $settings = DeviceSettings::all();
        return view('admin.device_types.edit',compact('type','parameters','settings'));
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
