<?php

namespace App\Http\Controllers;

use App\Exports\FactoryDeviceValueExport;
use App\Exports\ParametersDataExport;
use App\Http\Requests\FactoryRequest;
use App\Models\Device;
use App\Models\DeviceFactory;
use App\Models\DeviceFactoryValue;
use App\Models\Factory;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;

class FactoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|\Illuminate\Contracts\View\Factory|View|Response
     */
    public function index()
    {
        $factories = Factory::all();
        return view('admin.factory.index', compact('factories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|\Illuminate\Contracts\View\Factory|View|Response
     */
    public function create()
    {

        return view('admin.factory.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param FactoryRequest $request
     * @return RedirectResponse
     */
    public function store(FactoryRequest $request)
    {
        $factory = new Factory();
        $factory->name = $request->name;
        if ($factory->save()){
            return redirect()->route('admin.factories')->with('success', 'Data added successfully');
        }else{
            return redirect()->route('admin.factories.create')->with('error', 'Data failed to add');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Application|\Illuminate\Contracts\View\Factory|View|Response
     */
    public function show($id)
    {
        $factory = Factory::findOrFail($id);
        return view('admin.factory.show',compact('factory'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Application|\Illuminate\Contracts\View\Factory|View|Response
     */
    public function edit($id)
    {
        $factory = Factory::findOrFail($id);
        return view('admin.factory.edit',compact('factory'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param FactoryRequest $request
     * @param int $id
     * @return RedirectResponse
     */
    public function update(FactoryRequest $request, $id)
    {
        $factory = Factory::findOrFail($id);
        $factory->name = $request->name;
        if ($factory->save()){
            return redirect()->route('admin.factories')->with('success', 'Data added successfully');
        }else{
            return redirect()->route('admin.factories.edit')->with('error', 'Data failed to add');
        }
    }



    public function start($id)
    {
        $factory = Factory::findOrFail($id);
        $devices = Device::all();
        foreach ($devices as $key=>$device){
            if (count($device->deviceFactories()->where('is_attached',1)->get()) > 0){
                $devices->forget($key);
            }
        }
        return view('admin.factory.start',compact('factory','devices'));
    }

    public function attach(Request $request,$id)
    {

        $factory = Factory::findOrFail($id);
        $device = Device::findOrFail((int)$request['device']);
        if (count($device->deviceFactories()->where('is_attached', 1)->get()) == 0){
            $deviceFactory = new DeviceFactory();
            $deviceFactory->device_id = $device->id;
            $deviceFactory->factory_id = $factory->id;
            $deviceFactory->start_date = Carbon::now();
            $deviceFactory->is_attached = true;
            if ($deviceFactory->save()){
                return redirect()->route('admin.factories')->with('success', 'Data added successfully');
            }else{
                return redirect()->route('admin.factories.start', [$factory->id])->with('error', 'There was an error try again later or contact with admin');
            }
        }else{
            return redirect()->route('admin.factories.start', [$factory->id])->with('error', 'The Device Is Used In Another Factory Now');
        }
        dd($device->deviceFactories()->where('is_attached', 0)->get());
    }

    public function stop($id)
    {
        $factory = Factory::findOrFail($id);
        $devices = Device::all();
        return view('admin.factory.stop',compact('factory','devices'));
    }

    public function detach($id)
    {

        $deviceFactory = DeviceFactory::findOrFail($id);
            $deviceFactory->is_attached = false;
            if ($deviceFactory->save()){
                return redirect()->route('admin.factories.stop', [$deviceFactory->factory->id])->with('success', 'The device has been stopped');
            }else{
                return redirect()->route('admin.factories.stop', [$deviceFactory->factory->id])->with('error', 'There was an error try again later or contact with admin');
            }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return RedirectResponse
     */
    public function destroy($id)
    {
        $factory = Factory::findOrFail($id);
        if ($factory->delete()){
            foreach ($factory->deviceFactories as $devFac){
                $devFac->is_attached = false;
                $devFac->save();
            }
        }

        return redirect()->route('admin.factories')->with('success', 'Data deleted successfully');
    }

    public function details($id)
    {
        $devFactory = DeviceFactory::findOrFail($id);
//        $data = DeviceFactoryValue::where('device_id',$devFactory->device_id)->where('factory_id',)
//        dd($devFactory->device->deviceType->deviceParameters);
        return view('admin.factory.details',compact('devFactory'));
    }

    public function export($id)
    {
        $deviceFactory = DeviceFactory::findOrFail($id);
        $device = $deviceFactory->device_id;
        $factory = $deviceFactory->factory_id;
        $from = $deviceFactory->start_date;
        if ($deviceFactory->is_attached == 0){
//            dd('2222222');
            $to = $deviceFactory->updated_at;
        }else{
//            dd('1111111');
            $to = Carbon::now();
        }
        return Excel::download(new FactoryDeviceValueExport($from, $to, $device,$factory), 'parameter.xlsx');
    }

    public function exportToDatasheet(Request $request)
    {
        $validate = [
            'from' => 'required',
            'to' => 'required'
        ];
        \Validator::make($request->all(), $validate)->validate();
        $from = $request->from;
        $to = $request->to;
        $dev = $request->id;
        $factory = $request->factory;
        return Excel::download(new FactoryDeviceValueExport($from, $to, $dev,$factory), 'parameter.xlsx');
    }
}
