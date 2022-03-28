<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeviceRequest;
use App\Models\Device;
use App\Models\DeviceParameters;
use App\Models\DeviceSettings;
use App\Models\DeviceType;
use Illuminate\Http\Request;

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
        return view('admin.device.index',compact('devices'));
    }


    public function add()
    {
        $devices = Device::all();
//        dd($devices);
        return view('admin.device.add',compact('devices'));
    }

    public function get_devices()
    {
        $user = auth()->user();
//        dd($user->id);
        $devices = Device::where('user_id',$user->id)->get();
//        dd($devices);
        return view('admin.device.get_devices',compact('devices'));
    }

    /**
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function remove_device($id)
    {
        $device = Device::findOrFail($id);
        $user = auth()->user();
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
        return view ('admin.device.create',compact('types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(DeviceRequest $request)
    {
        $device = new Device();
        $device->name = $request->name;
        $device->device_id = $request->device_id;
        $device->user_id = 0;
        $device->type_id = $request->type;
         if ($device->save()) {
            return redirect()->route('admin.devices')->with('success', 'Data added successfully');
        }else {

            return redirect()->route('admin.devices.create')->with('error', 'Data failed to add');

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
        $device = Device::findOrFail($id);
        $types = DeviceType::all();
        return view('admin.device.edit',compact('device','types'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DeviceRequest $request, $id)
    {
        $device = Device::findOrFail($id);
        $device->name = $request->name;
        $device->device_id = $request->device_id;
        $device->user_id = 0;
        $device->type_id = $request->type;
        if ( $device->save()) {
            return redirect()->route('admin.devices')->with('success', 'Data added successfully');

        } else {

            return redirect()->route('admin.devices.edit')->with('error', 'Data failed to add');

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
        $device = Device::findOrFail($id);

        $device->delete();

        return redirect()->route('admin.devices')->with('success', 'Data deleted successfully');
    }
}
