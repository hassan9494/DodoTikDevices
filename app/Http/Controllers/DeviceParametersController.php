<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeviceParametersRequest;
use App\Models\DeviceParameters;
use Illuminate\Http\Request;

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
        return view('admin.device_parameters.index',compact('parameters'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create()
    {
        return view ('admin.device_parameters.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(DeviceParametersRequest $request)
    {
        $parameter = new DeviceParameters();
        $parameter->name = $request->name;
        $parameter->code = $request->code;
        if ($parameter->save()) {
            return redirect()->route('admin.device_parameters')->with('success', 'Data added successfully');
        }else {

            return redirect()->route('admin.device_parameters.create')->with('error', 'Data failed to add');

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
        $parameter = DeviceParameters::findOrFail($id);
        return view('admin.device_parameters.edit',compact('parameter'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(DeviceParametersRequest $request, $id)
    {
        $parameter = DeviceParameters::findOrFail($id);
        $parameter->name = $request->name;
        $parameter->code = $request->code;
        if ( $parameter->save()) {

            return redirect()->route('admin.device_parameters')->with('success', 'Data updated successfully');

        } else {

            return redirect()->route('admin.device_parameters.edit')->with('error', 'Data failed to update');

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
        $parameter = DeviceParameters::findOrFail($id);

        $parameter->delete();

        return redirect()->route('admin.device_parameters')->with('success', 'Data deleted successfully');
    }
}
