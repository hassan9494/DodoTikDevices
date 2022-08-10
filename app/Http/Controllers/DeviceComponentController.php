<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeviceComponentRequest;
use App\Models\Component;
use App\Models\Device;
use App\Models\DeviceComponent;
use Illuminate\Http\Request;

class DeviceComponentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $device_components = DeviceComponent::all();
        return view('admin.device_components.index',compact('device_components'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $components = Component::all();
        $devices = Device::all();
        foreach ($devices as $key=>$device){
            if ($device->deviceComponent != null){
                $devices->forget($key);
            }
        }
        return view ('admin.device_components.create',compact('components','devices'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(DeviceComponentRequest $request)
    {
//        dd($request);
        $components = Component::all();
        $componentsOfDevice = [];
        $deviceComponent = new DeviceComponent();
        $deviceComponent->device_id = $request['device_id'];
        foreach ($components as $component){
            if ($request['component_'.$component->id] == "on"){
                array_push($componentsOfDevice,$component->id);
            }
        }
        $deviceComponent->components = json_encode($componentsOfDevice);
        $deviceComponent->settings = json_encode($componentsOfDevice);
        $deviceComponent->save();
        return redirect()->route('admin.devices.show', [$deviceComponent->device_id]);
//        dd($componentsOfDevice);
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
        $deviceComponent = DeviceComponent::findOrFail($id);
        $components = Component::all();
        $devices = Device::all();
        return view ('admin.device_components.edit',compact('components','devices','deviceComponent'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DeviceComponentRequest $request, $id)
    {
//        dd($request);
        $components = Component::all();
        $componentsOfDevice = [];
        $deviceComponent =  DeviceComponent::findOrFail($id);
        $deviceComponent->device_id = $request['device_id'];
        foreach ($components as $component){
            if ($request['component_'.$component->id] == "on"){
                array_push($componentsOfDevice,$component->id);
            }
        }
        $deviceComponent->components = json_encode($componentsOfDevice);
        $deviceComponent->settings = json_encode($componentsOfDevice);
        $deviceComponent->save();
        return redirect()->route('admin.device_components.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deviceComponent = DeviceComponent::findOrFail($id);

        $deviceComponent->delete();

        return redirect()->route('admin.device_components.index')->with('success', 'Data deleted successfully');
    }
}
