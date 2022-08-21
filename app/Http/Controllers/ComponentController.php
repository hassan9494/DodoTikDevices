<?php

namespace App\Http\Controllers;

use App\Http\Requests\ComponentRequest;
use App\Models\Component;
use App\Models\ComponentSettings;
use Illuminate\Http\Request;

class ComponentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        $components = Component::all();
        return view('admin.components.index',compact('components'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create()
    {

        $component_settings = ComponentSettings::all();
        return view ('admin.components.create',compact('component_settings'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ComponentRequest $request)
    {
        $component = new Component();
        $component->name = $request->name;
        $component->slug = \Str::slug($request->name);
        $component->desc = $request->desc;
        $image = $request->file('image');
        if($image){
            $image_path = $image->store('images/components', 'public');
            $component->image = $image_path;
        }
        if ($component->save()) {
            $component->componentSettings()->attach(request('settings'));
            return redirect()->route('admin.components.index')->with('success', 'Data added successfully');
        }else {

            return redirect()->route('admin.components.create')->with('error', 'Data failed to add');

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
        $component = Component::findOrFail($id);
        $component_settings = ComponentSettings::all();
        return view('admin.components.edit',compact('component','component_settings'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ComponentRequest $request, $id)
    {
        $component = Component::findOrFail($id);
        $component->name = $request->name;
        $component->desc = $request->desc;
        $image = $request->file('image');
        if($image){
            $image_path = $image->store('images/components', 'public');
            $component->image = $image_path;
        }
        if ($component->save()) {

            $component->componentSettings()->sync(request('settings'));
            return redirect()->route('admin.components.index')->with('success', 'Data added successfully');
        }else {

            return redirect()->route('admin.components.edit')->with('error', 'Data failed to add');

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
        $component = Component::findOrFail($id);
        $component->componentSettings()->detach();
        $component->delete();



        return redirect()->route('admin.components.index')->with('success', 'Data deleted successfully');
    }
}
