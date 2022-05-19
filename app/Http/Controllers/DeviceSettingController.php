<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeviceSettingRequest;
use App\Models\DeviceSettings;
use Illuminate\Http\Request;

class DeviceSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        $type_settings = DeviceSettings::all();
//        dd($settings);
        return view('admin.device_setting.index',compact('type_settings'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create()
    {
        return view ('admin.device_setting.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(DeviceSettingRequest $request)
    {
        $setting = new DeviceSettings();
        $setting->name = $request->name;
        $setting->code = $request->code;
        if ($setting->save()) {
            return redirect()->route('admin.device_setting')->with('success', 'Data added successfully');
        }else {

            return redirect()->route('admin.device_setting.create')->with('error', 'Data failed to add');

        }
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
        $setting = DeviceSettings::findOrFail($id);
        return view('admin.device_setting.edit',compact('setting'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(DeviceSettingRequest $request, $id)
    {
        $setting = DeviceSettings::findOrFail($id);
        $setting->name = $request->name;
        $setting->code = $request->code;
        if ( $setting->save()) {

            return redirect()->route('admin.device_setting')->with('success', 'Data updated successfully');

        } else {

            return redirect()->route('admin.device_setting.edit')->with('error', 'Data failed to update');

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $setting = DeviceSettings::findOrFail($id);

        $setting->delete();

        return redirect()->route('admin.device_setting')->with('success', 'Data deleted successfully');
    }
}
