<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeviceComponentRequest;
use App\Models\Component;
use App\Models\Device;
use App\Models\DeviceComponent;
use App\Models\DevicesComponents;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Element;

class DeviceComponentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $device_components = DevicesComponents::all();
        return view('admin.device_components.index', compact('device_components'));
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
        foreach ($devices as $key => $device) {
            if ($device->deviceComponent != null) {
                $devices->forget($key);
            }
        }
        return view('admin.device_components.create', compact('components', 'devices'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(DeviceComponentRequest $request)
    {
//        dd($request);
        $components = Component::all();
        $componentsOfDevice = [];
        $componentsOfDevices = [];
        $settingsOfDevice = [];
//        $deviceComponent = new DeviceComponent();
//        $deviceComponent->device_id = $request['device_id'];
        foreach ($components as $key => $component) {
            $setting = [];
            if ($request['component_' . $component->id] == "on") {
                $deviceComponents = new DevicesComponents();
                $deviceComponents->device_id = $request['device_id'];
                $deviceComponents->component_id = $component->id;
                $deviceComponents->order = (int)$request['order_' . $component->id];
                $deviceComponents->width = (int)$request['width_' . $component->id];
//                dd(count(json_decode($components[$key]->componentSettings)));
                if (count(json_decode($components[$key]->componentSettings)) > 0) {
                    if (json_decode($component->componentSettings[0]->settings)->name == "parameters") {
                        $setting[json_decode($component->componentSettings[0]->settings)->name] = $request['parameters_' . $component->id];
                    } elseif (json_decode($component->componentSettings[0]->settings)->name == "settings") {
                        $setting[json_decode($component->componentSettings[0]->settings)->name] = $request['settings_' . $component->id];
                    }

                    $deviceComponents->settings = json_encode($setting);
//                    dd($deviceComponents);
                } else {
                    $deviceComponents->settings = null;
                }

//        dd($setting);


//                array_push($componentsOfDevice,$component->id);
//                $settings['component'] = $component->id;
//                $settings['order'] = (int)$request['order_'.$component->id];
//                $settings['width'] = (int)$request['width_'.$component->id];
//
//                array_push($settingsOfDevice,$settings);
//                $deviceComponents->settings = json_encode($settingsOfDevice);
                array_push($componentsOfDevices, $deviceComponents);
            }
        }
//        dd($componentsOfDevices);
//        $deviceComponent->components = json_encode($componentsOfDevice);
//        $deviceComponent->settings = json_encode($settingsOfDevice);
//        $deviceComponent->save();
        foreach ($componentsOfDevices as $componentsOfDevicee) {
            $componentsOfDevicee->save();
        }
        return redirect()->route('admin.devices.show', [$request['device_id']]);
//        dd($componentsOfDevice);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function editDisplay($id)
    {
        $device = Device::findOrFail($id);
        $deviceComponents = DevicesComponents::where('device_id', $id)->get();
//dd($device->deviceComponents[0]->component);
        $components = Component::all();
        return view('admin.device_components.editDisplay', compact('components', 'device', 'deviceComponents'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function updateDisplay(DeviceComponentRequest $request, $id)
    {
//        dd($request);
        $components = Component::all();
        $componentsOfDevices = [];
        $componentIds = [];

        foreach ($components as $key => $component) {
            $setting = [];
            if ($request['component_' . $component->id] == "on") {
                array_push($componentIds, $component->id);
                $deviceComponents = DevicesComponents::where('device_id', $id)->where('component_id', $component->id)->first();
                if ($deviceComponents == null) {
                    $deviceComponents = new DevicesComponents();
                }
                $deviceComponents->device_id = $id;
                $deviceComponents->component_id = $component->id;
                $deviceComponents->order = (int)$request['order_' . $component->id];
                $deviceComponents->width = (int)$request['width_' . $component->id];
                if (count(json_decode($components[$key]->componentSettings)) > 0) {
                    if (json_decode($component->componentSettings[0]->settings)->name == "parameters") {
                        $setting[json_decode($component->componentSettings[0]->settings)->name] = $request['parameters_' . $component->id];
                    } elseif (json_decode($component->componentSettings[0]->settings)->name == "settings") {
                        $setting[json_decode($component->componentSettings[0]->settings)->name] = $request['settings_' . $component->id];
                    }

                    $deviceComponents->settings = json_encode($setting);
//                    dd($deviceComponents);
                } else {
                    $deviceComponents->settings = null;
                }
                array_push($componentsOfDevices, $deviceComponents);
            }
        }
        foreach ($componentsOfDevices as $componentsOfDevicee) {
            $componentsOfDevicee->save();
        }
        $thisDeviceComponent = DevicesComponents::where('device_id', $id)->whereNotIn('component_id', $componentIds)->get();
        foreach ($thisDeviceComponent as $comdev) {
            $comdev->delete();
        }
//        dd($thisDeviceComponent);
//        foreach ($thisDeviceComponent as $key=>$devcom){
//
//        }
        return redirect()->route('admin.devices.show', [$request['device_id']]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $deviceComponent = DevicesComponents::findOrFail($id);
        $components = Component::all();
//        dd(json_decode($deviceComponent->settings)->parameters);
        $devices = Device::all();
        return view('admin.device_components.edit1', compact('components', 'devices', 'deviceComponent'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(DeviceComponentRequest $request, $id)
    {

        $setting = [];
        $deviceComponent = DevicesComponents::findOrFail($id);
        $deviceComponent->device_id = $request['device_id'];
        $deviceComponent->component_id = $request['component_id'];
        $deviceComponent->order = $request['order'];
        $deviceComponent->width = $request['width'];
        $setting[json_decode($deviceComponent->component->componentSettings[0]->settings)->name] = $request['parameters'];
//        dd($setting);
        $deviceComponent->settings = json_encode($setting);

        $deviceComponent->save();
        return redirect()->route('admin.device_components.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deviceComponent = DevicesComponents::findOrFail($id);

        $deviceComponent->delete();

        return redirect()->route('admin.device_components.index')->with('success', 'Data deleted successfully');
    }
}
