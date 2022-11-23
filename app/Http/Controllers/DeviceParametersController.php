<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeviceParametersRequest;
use App\Models\DeviceParameters;
use App\Models\ParameterRangeColor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeviceParametersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        $parameters = DeviceParameters::all();
        return view('admin.device_parameters.index', compact('parameters'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.device_parameters.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(DeviceParametersRequest $request)
    {
        $parameter = new DeviceParameters();
        $parameter->name = $request->name;
        $parameter->code = $request->code;
        $parameter->unit = $request->unit;
        if ($parameter->save()) {
            return redirect()->route('admin.device_parameters')->with('success', 'Data added successfully');
        } else {

            return redirect()->route('admin.device_parameters.create')->with('error', 'Data failed to add');

        }
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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function edit($id)
    {
        $parameter = DeviceParameters::findOrFail($id);
        return view('admin.device_parameters.edit', compact('parameter'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(DeviceParametersRequest $request, $id)
    {
        $parameter = DeviceParameters::findOrFail($id);
        $parameter->name = $request->name;
        $parameter->code = $request->code;
        $parameter->unit = $request->unit;
        if ($parameter->save()) {

            return redirect()->route('admin.device_parameters')->with('success', 'Data updated successfully');

        } else {

            return redirect()->route('admin.device_parameters.edit')->with('error', 'Data failed to update');

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $parameter = DeviceParameters::findOrFail($id);

        $parameter->delete();

        return redirect()->route('admin.device_parameters')->with('success', 'Data deleted successfully');
    }

    public function color($id)
    {
        $parameter = DeviceParameters::findOrFail($id);
        if (count($parameter->parameterRangeColors) > 0){
            return view('admin.device_parameters.edit_color', compact('parameter'));
        }else{
            return view('admin.device_parameters.color', compact('parameter'));
        }

    }

    public function color_range(Request $request, $id)
    {
        try {
            $parameter = DeviceParameters::findOrFail($id);
            foreach ($request['color'] as $key => $name) {
                if ($request['from'][$key] != null && $request['to'][$key] != null && $request['level_name'][$key] != null && $request['description'][$key] != null) {
                    $parameterRangColor[$key] = new ParameterRangeColor();
                    $parameterRangColor[$key]->parameter_id = $parameter->id;
                    $parameterRangColor[$key]->from = $request['from'][$key];
                    $parameterRangColor[$key]->to = $request['to'][$key];
                    $parameterRangColor[$key]->color = $request['color'][$key];
                    $parameterRangColor[$key]->level_name = $request['level_name'][$key];
                    $parameterRangColor[$key]->description = $request['description'][$key];
                }
            }
            DB::beginTransaction();
            foreach ($parameterRangColor as $item) {
                $item->save();
            }
            DB::commit();
            return redirect()->route('admin.device_parameters')->with('success', 'Data updated successfully');
        }catch(\Exception $ex) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data failed to create');
        }

    }



    public function update_color_range (Request $request, $id)
    {
        try {

            $parameter = DeviceParameters::findOrFail($id);
            foreach($parameter->parameterRangeColors as $key=>$range){
                $range->delete();
            }
            foreach ($request['color'] as $key => $name) {
                if ($request['from'][$key] != null && $request['to'][$key] != null && $request['level_name'][$key] != null && $request['description'][$key] != null) {
                    $parameterRangColor[$key] = new ParameterRangeColor();
                    $parameterRangColor[$key]->parameter_id = $parameter->id;
                    $parameterRangColor[$key]->from = $request['from'][$key];
                    $parameterRangColor[$key]->to = $request['to'][$key];
                    $parameterRangColor[$key]->color = $request['color'][$key];
                    $parameterRangColor[$key]->level_name = $request['level_name'][$key];
                    $parameterRangColor[$key]->description = $request['description'][$key];
                }
            }
            DB::beginTransaction();
            foreach ($parameterRangColor as $item) {
                $item->save();
            }
            DB::commit();
            return redirect()->route('admin.device_parameters')->with('success', 'Data updated successfully');
        }catch(\Exception $ex) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data failed to create');
        }

    }
}
