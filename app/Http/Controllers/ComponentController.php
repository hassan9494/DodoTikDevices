<?php

namespace App\Http\Controllers;

use App\Http\Requests\ComponentRequest;
use App\Models\Component;
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

        return view ('admin.components.create');
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
        $component->desc = $request->desc;
        $image = $request->file('image');
        if($image){
            $image_path = $image->store('images/components', 'public');
            $component->image = $image_path;
        }
        if ($component->save()) {
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
        return view('admin.components.edit',compact('component'));
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
        $component->delete();

        return redirect()->route('admin.components.index')->with('success', 'Data deleted successfully');
    }
}
