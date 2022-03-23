<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeviceTypesRequest;
use App\Models\DeviceType;
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
        return view ('admin.device_types.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DeviceTypesRequest $request)
    {
//        dd($request);
        $type = new DeviceType();
        $type->name = $request->name;
        if ($type->save()) {
            return redirect()->route('admin.device_types')->with('success', 'Data added successfully');
        }else {

            return redirect()->route('admin.device_types.create')->with('error', 'Data failed to add');

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
        $type = DeviceType::findOrFail($id);
        return view('admin.device_types.edit',compact('type'));
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

        return redirect()->route('admin.device_types')->with('success', 'Data deleted successfully');
    }
}
