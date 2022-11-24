<?php

namespace App\Http\Controllers;

use App\Exports\FactoryDeviceValueExport;
use App\Http\Requests\FactoryRequest;
use App\Models\Device;
use App\Models\DeviceFactory;
use App\Models\DeviceParameters;
use App\Models\DeviceParametersValues;
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
        if ($factory->save()) {
            return redirect()->route('admin.factories')->with('success', 'Data added successfully');
        } else {
            return redirect()->route('admin.factories.create')->with('error', 'Data failed to add');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Application|\Illuminate\Contracts\View\Factory|View|Response
     */
    public function show($id)
    {
        $factory = Factory::findOrFail($id);
        $devicesFactory = $factory->deviceFactories()->where('is_attached', 1)->get();
        $devices = [];
        $status = [];
        foreach ($devicesFactory as $item) {
            array_push($devices, $item->device);
        }
        $now = Carbon::now();
        foreach ($devices as $key => $device) {
            $parameters = $device->deviceParameters;
            $lastPara = DeviceParametersValues::where('device_id', $device->id)->orderBy('id', 'desc')->first();
            if (count($parameters) > 0) {
                if ($now->diff(date("m/d/Y H:i", strtotime($lastPara->time_of_read)))->d == 0 && $now->diff(date("m/d/Y H:i", strtotime($lastPara->time_of_read)))->h == 0 && $now->diff(date("m/d/Y H:i", strtotime($lastPara->time_of_read)))->i < ($device->time_between_two_read + $device->tolerance)) {
                    $state = "Online";
                } else {
                    $state = "Offline";
                }
            }
            array_push($status, $state);
        }
        return view('admin.factory.show', compact('factory', 'devices', 'status'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Application|\Illuminate\Contracts\View\Factory|View|Response
     */
    public function edit($id)
    {
        $factory = Factory::findOrFail($id);
        return view('admin.factory.edit', compact('factory'));
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
        if ($factory->save()) {
            return redirect()->route('admin.factories')->with('success', 'Data added successfully');
        } else {
            return redirect()->route('admin.factories.edit')->with('error', 'Data failed to add');
        }
    }


    public function start($id)
    {
        $factory = Factory::findOrFail($id);
        $devices = Device::all();
        foreach ($devices as $key => $device) {
            if (count($device->deviceFactories()->where('is_attached', 1)->get()) > 0) {
                $devices->forget($key);
            }
        }
        return view('admin.factory.start', compact('factory', 'devices'));
    }

    public function attach(Request $request, $id)
    {

        $factory = Factory::findOrFail($id);
        $device = Device::findOrFail((int)$request['device']);
        if (count($device->deviceFactories()->where('is_attached', 1)->get()) == 0) {
            $deviceFactory = new DeviceFactory();
            $deviceFactory->device_id = $device->id;
            $deviceFactory->factory_id = $factory->id;
            $deviceFactory->start_date = Carbon::now();
            $deviceFactory->is_attached = true;
            if ($deviceFactory->save()) {
                return redirect()->route('admin.factories')->with('success', 'Data added successfully');
            } else {
                return redirect()->route('admin.factories.start', [$factory->id])->with('error', 'There was an error try again later or contact with admin');
            }
        } else {
            return redirect()->route('admin.factories.start', [$factory->id])->with('error', 'The Device Is Used In Another Factory Now');
        }
        dd($device->deviceFactories()->where('is_attached', 0)->get());
    }

    public function stop($id)
    {
        $factory = Factory::findOrFail($id);
        $devices = Device::all();
        return view('admin.factory.stop', compact('factory', 'devices'));
    }

    public function detach($id)
    {

        $deviceFactory = DeviceFactory::findOrFail($id);
        $deviceFactory->is_attached = false;
        if ($deviceFactory->save()) {
            return redirect()->route('admin.factories.stop', [$deviceFactory->factory->id])->with('success', 'The device has been stopped');
        } else {
            return redirect()->route('admin.factories.stop', [$deviceFactory->factory->id])->with('error', 'There was an error try again later or contact with admin');
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy($id)
    {
        $factory = Factory::findOrFail($id);
        if ($factory->delete()) {
            foreach ($factory->deviceFactories as $devFac) {
                $devFac->is_attached = false;
                $devFac->save();
            }
        }

        return redirect()->route('admin.factories')->with('success', 'Data deleted successfully');
    }

    public function details($id)
    {
        $devFactory = DeviceFactory::findOrFail($id);
        $device = $devFactory->device;
        $device_type = $device->deviceType;
        $firstParameter = $device_type->deviceParameters()->orderBy('order')->first();
        $parameters = $devFactory->deviceFactoryValues;
        $xValues = [];
        $yValues = [];
        $paraValues = [];
        $now = Carbon::now();
        $thisMidnight = Carbon::now()->endOfDay();
        $color = [];
        $multiColor = [];
        $label = 0;
        $test = [];
        if (count($parameters) > 0) {

            foreach ($parameters as $parameter) {
                if ($now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->d == 1 && $thisMidnight->diff(date("m/d/Y H:I", strtotime($parameter->time_of_read)))->h <= 2 && $now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->m == 0 && $now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->y == 0) {
                    array_push($xValues, date(DATE_ISO8601, strtotime($parameter->time_of_read)));
                }
                if ($now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->d == 0 && $now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->m == 0 && $now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->y == 0) {
                    array_push($xValues, date(DATE_ISO8601, strtotime($parameter->time_of_read)));
                }
//                array_push($xValues, date(DATE_ISO8601, strtotime($parameter->time_of_read)));
            }
            $warning = 1;
            foreach ($device_type->deviceParameters()->orderBy('order')->get() as $tPara) {
                array_push($color, $tPara->pivot->color);
                foreach ($parameters as $parameter) {
                    if ($now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->d == 1 && $thisMidnight->diff(date("m/d/Y H:I", strtotime($parameter->time_of_read)))->h <= 2 && $now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->m == 0 && $now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->y == 0) {
                        if (isset(json_decode($parameter->parameters, true)[$tPara->code])) {
                            array_push($yValues, json_decode($parameter->parameters, true)[$tPara->code]);
                            if ($tPara->code == $device_type->deviceParameters()->orderBy('order')->first()->code) {
                                foreach ($tPara->parameterRangeColors as $key => $paraRangeColor) {
                                    if (json_decode($parameter->parameters, true)[$tPara->code] >= $paraRangeColor->from && json_decode($parameter->parameters, true)[$tPara->code] < $paraRangeColor->to) {
                                        array_push($multiColor, $paraRangeColor->color);
                                    }
                                }
                            }
                        } else {
                            array_push($yValues, 0);
                        }

                    }
                    if ($now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->d == 0 && $now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->m == 0 && $now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->y == 0) {
                        if (isset(json_decode($parameter->parameters, true)[$tPara->code])) {
                            array_push($yValues, json_decode($parameter->parameters, true)[$tPara->code]);

                            if ($tPara->code == $device_type->deviceParameters()->orderBy('order')->first()->code) {
                                foreach ($tPara->parameterRangeColors as $key => $paraRangeColor) {
                                    if (json_decode($parameter->parameters, true)[$tPara->code] >= $paraRangeColor->from && json_decode($parameter->parameters, true)[$tPara->code] < $paraRangeColor->to) {
                                        array_push($multiColor, $paraRangeColor->color);
                                    }
                                }
                            }
                        } else {
                            array_push($yValues, 0);
                        }
                    }


                }
                array_push($paraValues, $yValues);
                $yValues = [];
            }


        } else {
            $warning = 1;
            $status = "Offline";
            $label = 1;
        }
//        dd($multiColor);
        return view('admin.factory.details', compact('label', 'firstParameter', 'devFactory', 'device_type', 'multiColor', 'xValues', 'paraValues', 'color'));
    }

    public function flowchartWithDate($id, $from, $to)
    {
        $devFactory = DeviceFactory::findOrFail($id);

        $device = $devFactory->device;
        $device_type = $device->deviceType;
        $now = Carbon::now();
        $thisMidnight = Carbon::now()->endOfDay();
        $parameters = $devFactory->deviceFactoryValues;
        $xValues = [];
        $yValues = [];
        $paraValues = [];
        $color = [];
        if (count($parameters) > 0) {
            if ($from == 1 && $to == 0) {

                foreach ($parameters as $parameter) {
                    if ($now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->d == 1 && $thisMidnight->diff(date("m/d/Y H:I", strtotime($parameter->time_of_read)))->h <= 2 && $now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->m == 0 && $now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->y == 0) {
                        array_push($xValues, date(DATE_ISO8601, strtotime($parameter->time_of_read)));
                    }
                    if ($now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->d == 0 && $now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->m == 0 && $now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->y == 0) {
                        array_push($xValues, date(DATE_ISO8601, strtotime($parameter->time_of_read)));
                    }
                }
                $warning = 1;
                foreach ($device_type->deviceParameters()->orderBy('order')->get() as $tPara) {
                    array_push($color, $tPara->pivot->color);
                    foreach ($parameters as $parameter) {
                        if ($now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->d == 1 && $thisMidnight->diff(date("m/d/Y H:I", strtotime($parameter->time_of_read)))->h <= 2 && $now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->m == 0 && $now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->y == 0) {
                            if (isset(json_decode($parameter->parameters, true)[$tPara->code])) {
                                array_push($yValues, json_decode($parameter->parameters, true)[$tPara->code]);
                            } else {
                                array_push($yValues, 0);
                            }

                        }
                        if ($now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->d == 0 && $now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->m == 0 && $now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->y == 0) {
                            if (isset(json_decode($parameter->parameters, true)[$tPara->code])) {
                                array_push($yValues, json_decode($parameter->parameters, true)[$tPara->code]);
                            } else {
                                array_push($yValues, 0);
                            }
                        }
                    }
                    array_push($paraValues, $yValues);
                    $yValues = [];
                }
            }elseif ($from == 0 && $to == 0){
                foreach ($parameters as $parameter) {
                    array_push($xValues, date(DATE_ISO8601, strtotime($parameter->time_of_read)));
                }
                $warning = 1;
                foreach ($device_type->deviceParameters()->orderBy('order')->get() as $tPara) {
                    array_push($color, $tPara->pivot->color);
                    foreach ($parameters as $parameter) {
                        if (isset(json_decode($parameter->parameters, true)[$tPara->code])) {
                            array_push($yValues, json_decode($parameter->parameters, true)[$tPara->code]);
                        } else {
                            array_push($yValues, 0);
                        }
                    }
                    array_push($paraValues, $yValues);
                    $yValues = [];
                }
            } else {
                foreach ($parameters as $parameter) {
                    if ($now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->d <= $from && $now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->d >= $to && $now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->m == 0 && $now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->y == 0) {
                        array_push($xValues, date(DATE_ISO8601, strtotime($parameter->time_of_read)));
                    }
                }
                $warning = 1;
                foreach ($device_type->deviceParameters()->orderBy('order')->get() as $tPara) {
                    array_push($color, $tPara->pivot->color);
                    foreach ($parameters as $parameter) {
                        if ($now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->d <= $from && $now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->d >= $to && $now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->m == 0 && $now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->y == 0) {
                            if (isset(json_decode($parameter->parameters, true)[$tPara->code])) {
                                array_push($yValues, json_decode($parameter->parameters, true)[$tPara->code]);
                            } else {
                                array_push($yValues, 0);
                            }
                        }
                    }
                    array_push($paraValues, $yValues);
                    $yValues = [];
                }
            }
        } else {
            $warning = 1;
            $status = "Offline";
            $label = 1;
        }
        return array($paraValues, $xValues, $device, $color);
    }

    public function showParameterData($devFactory_id, $parameter_id)
    {
        $devFactory = DeviceFactory::findOrFail($devFactory_id);
        $device = $devFactory->device;
        $device_type = $device->deviceType;
        $requestedParameter = DeviceParameters::findOrFail($parameter_id);
        $parameters = $devFactory->deviceFactoryValues;
        $xValues = [];
        $yValues = [];
        $paraValues = [];
        $now = Carbon::now();
        $thisMidnight = Carbon::now()->endOfDay();
        $color = [];
        $multiColor = [];
        $test = [];
        if (count($parameters) > 0) {

            foreach ($parameters as $parameter) {
                if ($now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->d == 1 && $thisMidnight->diff(date("m/d/Y H:I", strtotime($parameter->time_of_read)))->h <= 2 && $now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->m == 0 && $now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->y == 0) {
                    array_push($xValues, date(DATE_ISO8601, strtotime($parameter->time_of_read)));
                }
                if ($now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->d == 0 && $now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->m == 0 && $now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->y == 0) {
                    array_push($xValues, date(DATE_ISO8601, strtotime($parameter->time_of_read)));
                }
//                array_push($xValues, date(DATE_ISO8601, strtotime($parameter->time_of_read)));
            }
            $warning = 1;
//            foreach ($device_type->deviceParameters()->orderBy('order')->get() as $tPara) {
//                array_push($color, $requestedParameter->pivot->color);
            foreach ($parameters as $parameter) {
                if ($now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->d == 1 && $thisMidnight->diff(date("m/d/Y H:I", strtotime($parameter->time_of_read)))->h <= 2 && $now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->m == 0 && $now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->y == 0) {
                    if (isset(json_decode($parameter->parameters, true)[$requestedParameter->code])) {
                        array_push($yValues, json_decode($parameter->parameters, true)[$requestedParameter->code]);
                        if ($requestedParameter->code == $requestedParameter->code) {
                            foreach ($requestedParameter->parameterRangeColors as $key => $paraRangeColor) {
                                if (json_decode($parameter->parameters, true)[$requestedParameter->code] >= $paraRangeColor->from && json_decode($parameter->parameters, true)[$requestedParameter->code] < $paraRangeColor->to) {
                                    array_push($multiColor, $paraRangeColor->color);
                                }
                            }
                        }
                    } else {
                        array_push($yValues, 0);
                    }

                }
                if ($now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->d == 0 && $now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->m == 0 && $now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->y == 0) {
                    if (isset(json_decode($parameter->parameters, true)[$requestedParameter->code])) {
                        array_push($yValues, json_decode($parameter->parameters, true)[$requestedParameter->code]);

                        if ($requestedParameter->code == $requestedParameter->code) {
                            foreach ($requestedParameter->parameterRangeColors as $key => $paraRangeColor) {
                                if (json_decode($parameter->parameters, true)[$requestedParameter->code] >= $paraRangeColor->from && json_decode($parameter->parameters, true)[$requestedParameter->code] < $paraRangeColor->to) {
                                    array_push($multiColor, $paraRangeColor->color);
                                }
                            }
                        }
                    } else {
                        array_push($yValues, 0);
                    }
                }


            }
            array_push($paraValues, $yValues);
            $yValues = [];
//            }


        }
        $color = $device_type->deviceParameters()->where('code', $requestedParameter->code)->orderBy('order')->first()->pivot->color;
//        dd($paraValues);
        return array($paraValues, $xValues, $device, $multiColor, $requestedParameter, $color);
//        return view('admin.factory.details', compact('devFactory','device_type','multiColor', 'xValues', 'paraValues', 'color'));
    }

    public function showParameterDataWithDate($devFactory_id, $parameter_id, $from, $to)
    {
        $devFactory = DeviceFactory::findOrFail($devFactory_id);
        $device = $devFactory->device;
        $device_type = $device->deviceType;
        $requestedParameter = DeviceParameters::findOrFail($parameter_id);
        $parameters = $devFactory->deviceFactoryValues;
        $xValues = [];
        $yValues = [];
        $paraValues = [];
        $now = Carbon::now();
        $thisMidnight = Carbon::now()->endOfDay();
        $color = [];
        $multiColor = [];
        if (count($parameters) > 0) {
            if ($from == 1 && $to == 0) {
                foreach ($parameters as $parameter) {
                    if ($now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->d == 1 && $thisMidnight->diff(date("m/d/Y H:I", strtotime($parameter->time_of_read)))->h <= 2 && $now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->m == 0 && $now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->y == 0) {
                        array_push($xValues, date(DATE_ISO8601, strtotime($parameter->time_of_read)));
                    }
                    if ($now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->d == 0 && $now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->m == 0 && $now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->y == 0) {
                        array_push($xValues, date(DATE_ISO8601, strtotime($parameter->time_of_read)));
                    }
                }
                $warning = 1;
                foreach ($parameters as $parameter) {
                    if ($now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->d == 1 && $thisMidnight->diff(date("m/d/Y H:I", strtotime($parameter->time_of_read)))->h <= 2 && $now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->m == 0 && $now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->y == 0) {
                        if (isset(json_decode($parameter->parameters, true)[$requestedParameter->code])) {
                            array_push($yValues, json_decode($parameter->parameters, true)[$requestedParameter->code]);
                            if ($requestedParameter->code == $requestedParameter->code) {
                                foreach ($requestedParameter->parameterRangeColors as $key => $paraRangeColor) {
                                    if (json_decode($parameter->parameters, true)[$requestedParameter->code] >= $paraRangeColor->from && json_decode($parameter->parameters, true)[$requestedParameter->code] < $paraRangeColor->to) {
                                        array_push($multiColor, $paraRangeColor->color);
                                    }
                                }
                            }
                        } else {
                            array_push($yValues, 0);
                        }

                    }
                    if ($now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->d == 0 && $now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->m == 0 && $now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->y == 0) {
                        if (isset(json_decode($parameter->parameters, true)[$requestedParameter->code])) {
                            array_push($yValues, json_decode($parameter->parameters, true)[$requestedParameter->code]);

                            if ($requestedParameter->code == $requestedParameter->code) {
                                foreach ($requestedParameter->parameterRangeColors as $key => $paraRangeColor) {
                                    if (json_decode($parameter->parameters, true)[$requestedParameter->code] >= $paraRangeColor->from && json_decode($parameter->parameters, true)[$requestedParameter->code] < $paraRangeColor->to) {
                                        array_push($multiColor, $paraRangeColor->color);
                                    }
                                }
                            }
                        } else {
                            array_push($yValues, 0);
                        }
                    }


                }
                array_push($paraValues, $yValues);
                $yValues = [];
            }
            elseif ($from == 0 && $to == 0) {
                foreach ($parameters as $parameter) {
                    array_push($xValues, date(DATE_ISO8601, strtotime($parameter->time_of_read)));
                }
                $warning = 1;
                foreach ($parameters as $parameter) {
                    if (isset(json_decode($parameter->parameters, true)[$requestedParameter->code])) {
                        array_push($yValues, json_decode($parameter->parameters, true)[$requestedParameter->code]);
                        if ($requestedParameter->code == $requestedParameter->code) {
                            foreach ($requestedParameter->parameterRangeColors as $key => $paraRangeColor) {
                                if (json_decode($parameter->parameters, true)[$requestedParameter->code] >= $paraRangeColor->from && json_decode($parameter->parameters, true)[$requestedParameter->code] < $paraRangeColor->to) {
                                    array_push($multiColor, $paraRangeColor->color);
                                }
                            }
                        }
                    } else {
                        array_push($yValues, 0);
                    }
                }
                array_push($paraValues, $yValues);
                $yValues = [];
            }
            else {
                foreach ($parameters as $parameter) {
                    if ($now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->d <= $from && $now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->d >= $to && $now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->m == 0 && $now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->y == 0) {
                        array_push($xValues, date(DATE_ISO8601, strtotime($parameter->time_of_read)));
                    }
                }
                $warning = 1;
                foreach ($parameters as $parameter) {
                    if ($now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->d <= $from && $now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->d >= $to && $now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->m == 0 && $now->diff(date("m/d/Y", strtotime($parameter->time_of_read)))->y == 0) {
                        if (isset(json_decode($parameter->parameters, true)[$requestedParameter->code])) {
                            array_push($yValues, json_decode($parameter->parameters, true)[$requestedParameter->code]);
                            if ($requestedParameter->code == $requestedParameter->code) {
                                foreach ($requestedParameter->parameterRangeColors as $key => $paraRangeColor) {
                                    if (json_decode($parameter->parameters, true)[$requestedParameter->code] >= $paraRangeColor->from && json_decode($parameter->parameters, true)[$requestedParameter->code] < $paraRangeColor->to) {
                                        array_push($multiColor, $paraRangeColor->color);
                                    }
                                }
                            }
                        } else {
                            array_push($yValues, 0);
                        }

                    }
                }
                array_push($paraValues, $yValues);
                $yValues = [];
            }


        }
        $color = $device_type->deviceParameters()->where('code', $requestedParameter->code)->orderBy('order')->first()->pivot->color;
//        dd($paraValues);
        return array($paraValues, $xValues, $device, $multiColor, $requestedParameter, $color);
    }

    public function details1(Request $request, $id)
    {
        $devFactory = DeviceFactory::findOrFail($id);
        $columns = array(
            0 => 'No',
//            1 =>'title',
//            2=> 'body',
//            3=> 'created_at',
//            4=> 'id',
        );
        foreach ($devFactory->device->deviceType->deviceParameters()->orderBy('order')->get() as $parameter) {
            array_push($columns, $parameter->name);
        }

        array_push($columns, 'time_of_read');

        $totalData = $devFactory->deviceFactoryValues()->orderBy('id', 'desc')->count();

        $totalFiltered = $totalData;
        $counter = 1;

        if (empty($request->input('search.value'))) {
            $posts = $devFactory->deviceFactoryValues()->orderBy('id', 'desc')->get();
        } else {
            $posts = $devFactory->deviceFactoryValues()->orderBy('id', 'desc')->get();
//            $search = $request->input('search.value');

//            $posts =  $posts = $devFactory->deviceFactoryValues()->orderBy('id','desc')->get();
//
//            $totalFiltered = $posts = $devFactory->deviceFactoryValues()->orderBy('id','desc')->count();
        }

        $data = array();
        if (!empty($posts)) {
            foreach ($posts as $post) {
//                $show =  route('posts.show',$post->id);
//                $edit =  route('posts.edit',$post->id);

                $nestedData['No'] = $counter++;

                foreach ($devFactory->device->deviceType->deviceParameters()->orderBy('order')->get() as $parameter) {
                    if (isset(json_decode($post->parameters, true)[$parameter->code])) {
                        $nestedData[$parameter->name] = json_decode($post->parameters, true)[$parameter->code];
                    } else {
                        $nestedData[$parameter->name] = 0;
                    }
                }

                $nestedData['time_of_read'] = Carbon::parse($post->time_of_read)->setTimezone('Europe/Istanbul')->format('Y-d-m h:i a');

                $data[] = $nestedData;

            }
        }

        $json_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );
//        dd($json_data);

        echo json_encode($json_data);
    }

    public function export($id)
    {
        $deviceFactory = DeviceFactory::findOrFail($id);
        $device = $deviceFactory->device_id;
        $factory = $deviceFactory->factory_id;
        $from = $deviceFactory->start_date;
        if ($deviceFactory->is_attached == 0) {
            $to = $deviceFactory->updated_at;
        } else {
            $to = Carbon::now();
        }
        return Excel::download(new FactoryDeviceValueExport($from, $to, $device, $factory), 'parameter.xlsx');
    }


    public function oldDetails($id)
    {
        $devFactory = DeviceFactory::findOrFail($id);
        $device = $devFactory->device;
        $device_type = $device->deviceType;

        $parameters = $devFactory->deviceFactoryValues;
        $xValues = [];
        $yValues = [];
        $paraValues = [];
        $color = [];
        $lastPara = DeviceParametersValues::where('device_id', $id)->orderBy('id', 'desc')->first();
        if (count($parameters) > 0) {

            foreach ($parameters as $parameter) {
                array_push($xValues, date(DATE_ISO8601, strtotime($parameter->time_of_read)));

            }
            $warning = 1;
            foreach ($device_type->deviceParameters()->orderBy('order')->get() as $tPara) {
                array_push($color, $tPara->pivot->color);
                foreach ($parameters as $parameter) {
                    if (isset(json_decode($parameter->parameters, true)[$tPara->code])) {
                        array_push($yValues, json_decode($parameter->parameters, true)[$tPara->code]);
                    } else {
                        array_push($yValues, 0);
                    }


                }
                array_push($paraValues, $yValues);
                $yValues = [];
            }


        } else {
            $warning = 1;
            $status = "Offline";
            $label = 1;
        }
        return view('admin.factory.details', compact('devFactory', 'xValues', 'paraValues', 'color'));
    }
}
