<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {

        $user = User::all();
        return view('admin.user.index',['user' => $user
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create()
    {
       return view ('admin.user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => ['required', 'string', Rule::unique('users', 'username')],
            'role' => ['required', 'string'],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'name' => ['required', 'string'],
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $user = new User();
        $user->username = $validated['username'];
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'];
        $user->password = Hash::make($validated['password']);
        $user->role = $validated['role'];
        $user->remember_token = $request->_token;
        $user->is_active = true;

        if ($user->save()) {
            return redirect()->route('admin.users.index')->with('success', 'Data added successfully');
        }else {

            return redirect()->route('admin.users.create')->with('error', 'Data failed to add');

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
        $user = User::with(['devices.deviceType', 'subscriptionActivations' => function ($query) {
            $query->with('subscriptionCode')->orderByDesc('activated_at');
        }])->findOrFail($id);

        return view('admin.user.show', [
            'user' => $user,
            'devices' => $user->devices,
            'activations' => $user->subscriptionActivations,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.user.edit',compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => ['required', 'string'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($id)],
            'username' => ['required', 'string', Rule::unique('users', 'username')->ignore($id)],
            'phone' => ['required', 'string', 'max:20'],
            'role' => ['nullable', 'string'],
        ]);

        $user = User::findOrFail($id);
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'] ?? $user->role;
        $user->username = $validated['username'];
        $user->phone = $validated['phone'];

        if ( $user->save()) {
            if(auth()->user()->role == 'user'){
                return redirect()->route('admin.users.edit',$id)->with('success', 'Data updated successfully');

            }
            return redirect()->route('admin.users.index')->with('success', 'Data updated successfully');

           } else {

            return redirect()->route('admin.user.edit')->with('error', 'Data failed to update');

           }
    }

    public function toggleStatus(Request $request, $id)
    {
        $admin = $request->user();
        $user = User::findOrFail($id);

        if ($user->id === $admin->id) {
            return redirect()->route('admin.users.index')->with('error', __('message.cannot_deactivate_self'));
        }

        if ($user->role === 'Administrator') {
            return redirect()->route('admin.users.index')->with('error', __('message.cannot_deactivate_admin'));
        }

        $user->is_active = ! $user->is_active;
        $user->remember_token = Str::random(60);
        $user->save();

        if (method_exists($user, 'tokens')) {
            $user->tokens()->delete();
        }

        if (config('session.driver') === 'database' && Schema::hasTable($table = config('session.table', 'sessions'))) {
            DB::table($table)->where('user_id', $user->id)->delete();
        }

        $message = $user->is_active
            ? __('message.user_activated')
            : __('message.user_deactivated');

        return redirect()->route('admin.users.index')->with('success', $message);
    }

    public function changepassword(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->password = Hash::make($request->password);

        if ( $user->save()) {

            return redirect()->route('admin.users.index')->with('success', 'Password updated successfully');

           } else {

            return redirect()->route('admin.users.index')->with('error', 'Password failed to update');

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
        $user = User::findOrFail($id);

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Data deleted successfully');
    }
}
