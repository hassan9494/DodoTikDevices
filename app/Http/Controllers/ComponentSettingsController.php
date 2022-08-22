<?php

namespace App\Http\Controllers;

use App\Models\ComponentSettings;
use Illuminate\Http\Request;

class ComponentSettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $componentSetting = ComponentSettings::all();
        return view('admin.component_settings.index',compact('componentSetting'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view ('admin.component_settings.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $componentSetting = new ComponentSettings();
        $keyValues = [];
        $componentSetting->name = $request['name'];
        $componentSetting->slug = \Str::slug($request['name']);
        foreach ($request['key'] as $key=>$keyname){
            $keyValues[$keyname] = $request['value'][$key];
        }
        $componentSetting->settings = json_encode($keyValues);
        if ($componentSetting->save()) {
            return redirect()->route('admin.component_settings')->with('success', 'Data added successfully');
        }else {

            return redirect()->route('admin.component_settings.create')->with('error', 'Data failed to add');

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
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $component_setting = ComponentSettings::findOrFail($id);
        $lastKeys = json_decode($component_setting->settings);
        $latestKey = '';
        foreach ($lastKeys as $key=>$lastKey){
            $latestKey = $key;
        }
        return view('admin.component_settings.edit',compact('component_setting','latestKey'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $componentSetting = ComponentSettings::findOrFail($id);
        $keyValues = [];
        $componentSetting->name = $request['name'];
        foreach ($request['key'] as $key=>$keyname){
            if ($request['value'][$key] != null && $keyname != null)
            $keyValues[$keyname] = $request['value'][$key];
        }
        $componentSetting->settings = json_encode($keyValues);
        if ($componentSetting->save()) {
            return redirect()->route('admin.component_settings')->with('success', 'Data added successfully');
        }else {

            return redirect()->route('admin.component_settings.edit')->with('error', 'Data failed to add');

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
        $component = ComponentSettings::findOrFail($id);
        $component->delete();



        return redirect()->route('admin.component_settings')->with('success', 'Data deleted successfully');
    }
}
