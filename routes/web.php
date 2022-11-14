<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{Auth\ForgotPasswordController,
    ComponentController,
    ComponentSettingsController,
    DeviceComponentController,
    DeviceController,
    DeviceParametersController,
    DeviceSettingController,
    DevicTypeController,
    FactoryController,
    FrontController,
    GeneralController,
    UserController};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', [FrontController::class, 'home'])->name('homepage');
//Route::post('/', [FrontController::class, 'subscribe'])->name('subscribe');
Route::get('about-us', [FrontController::class, 'about'])->name('about');

Route::get('forget-password', [ForgotPasswordController::class, 'showForgetPasswordForm'])->name('forget.password.get');
Route::post('forget-password', [ForgotPasswordController::class, 'submitForgetPasswordForm'])->name('forget.password.post');
Route::get('reset-password/{token}', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('reset.password.get');
Route::post('reset-password', [ForgotPasswordController::class, 'submitResetPasswordForm'])->name('reset.password.post');




Auth::routes([
    'register' => true
]);
Auth::routes(['verify' => true]);
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->middleware('verified')->middleware('can:isAdmin')->name('home');

Route::group(['as'=>'admin.','prefix'=>'admin','middleware'=>'auth','middleware'=>'verified'],function () {
    Route::get('migrate', [FrontController::class, 'migrate'])->middleware('can:isAdmin')->name('migrate');
    Route::get('dashboard', [GeneralController::class, 'dashboard'])->name('dashboard');

    // General settings
    Route::get('general-settings', [GeneralController::class, 'general'])->middleware('can:isAdmin')->name('general');
    Route::post('general-settings', [GeneralController::class, 'generalUpdate'])->middleware('can:isAdmin')->name('general.update');

    // About
    Route::get('about', [GeneralController::class, 'about'])->middleware('can:isAdmin')->name('about');
    Route::post('about', [GeneralController::class, 'aboutUpdate'])->middleware('can:isAdmin')->name('about.update');

    // Manage Admin
//    Route::resource('users',UserController::class);
    Route::get('users', [UserController::class, 'index'])->middleware('can:isAdminOrUser')->name('users.index');
    Route::post('users/{id}', [UserController::class, 'changepassword'])->middleware('can:isAdminOrUser')->name('users.changepassword');
    Route::get('users/create', [UserController::class, 'create'])->middleware('can:isAdminOrUser')->name('users.create');
    Route::post('users', [UserController::class, 'store'])->middleware('can:isAdminOrUser')->name('users.store');
    Route::get('users/edit/{id}', [UserController::class, 'edit'])->middleware('can:isAdminOrUser')->name('users.edit');
    Route::post('users/edit/{id}', [UserController::class, 'update'])->middleware('can:isAdminOrUser')->name('users.update');
    Route::delete('users/destroy/{id}',[UserController::class, 'destroy'])->middleware('can:isAdmin')->name('users.destroy');

    // Manage device_types
    Route::get('device_types/add_default_values/{typeid}', [DevicTypeController::class, 'add_default_values'])->middleware('can:isAdmin')->name('device_types.add_default_values');
    Route::post('device_types/add_default_values/{typeid}', [DevicTypeController::class, 'add_default'])->middleware('can:isAdmin')->name('device_types.add_default');


    Route::get('device_types', [DevicTypeController::class, 'index'])->middleware('can:isAdmin')->name('device_types');
    Route::get('device_types/create', [DevicTypeController::class, 'create'])->middleware('can:isAdmin')->name('device_types.create');
    Route::post('device_types/store', [DevicTypeController::class, 'store'])->middleware('can:isAdminOrUser')->name('device_types.store');
    Route::get('device_types/edit/{id}', [DevicTypeController::class, 'edit'])->middleware('can:isAdmin')->name('device_types.edit');
    Route::get('device_types/show/{id}', [DevicTypeController::class, 'show'])->middleware('can:isAdmin')->name('device_types.show');
    Route::post('device_types/edit/{id}', [DevicTypeController::class, 'update'])->middleware('can:isAdmin')->name('device_types.update');
    Route::delete('device_types/destroy/{id}',[DevicTypeController::class, 'destroy'])->middleware('can:isAdmin')->name('device_types.destroy');

    // Manage device_parameters
//    Route::resource('device_parameters',DeviceParametersController::class);
    Route::get('device_parameters', [DeviceParametersController::class, 'index'])->middleware('can:isAdmin')->name('device_parameters');
    Route::get('device_parameters/create', [DeviceParametersController::class, 'create'])->middleware('can:isAdmin')->name('device_parameters.create');
    Route::post('device_parameters/store', [DeviceParametersController::class, 'store'])->middleware('can:isAdminOrUser')->name('device_parameters.store');
    Route::get('device_parameters/edit/{id}', [DeviceParametersController::class, 'edit'])->middleware('can:isAdmin')->name('device_parameters.edit');
    Route::get('device_parameters/show/{id}', [DeviceParametersController::class, 'show'])->middleware('can:isAdmin')->name('device_parameters.show');
    Route::post('device_parameters/edit/{id}', [DeviceParametersController::class, 'update'])->middleware('can:isAdmin')->name('device_parameters.update');
    Route::delete('device_parameters/destroy/{id}',[DeviceParametersController::class, 'destroy'])->middleware('can:isAdmin')->name('device_parameters.destroy');


    // Manage device_types
//    Route::resource('device_setting',DeviceSettingController::class);
    Route::get('device_setting', [DeviceSettingController::class, 'index'])->middleware('can:isAdmin')->name('device_setting');
    Route::get('device_setting/create', [DeviceSettingController::class, 'create'])->middleware('can:isAdmin')->name('device_setting.create');
    Route::post('device_setting/store', [DeviceSettingController::class, 'store'])->middleware('can:isAdminOrUser')->name('device_setting.store');
    Route::get('device_setting/edit/{id}', [DeviceSettingController::class, 'edit'])->middleware('can:isAdmin')->name('device_setting.edit');
    Route::get('device_setting/show/{id}', [DeviceSettingController::class, 'show'])->middleware('can:isAdmin')->name('device_setting.show');
    Route::post('device_setting/edit/{id}', [DeviceSettingController::class, 'update'])->middleware('can:isAdmin')->name('device_setting.update');
    Route::delete('device_setting/destroy/{id}',[DeviceSettingController::class, 'destroy'])->middleware('can:isAdmin')->name('device_setting.destroy');


    // Manage device
    Route::get('devices/add', [DeviceController::class, 'add'])->middleware('can:isAdminOrUser')->name('devices.add');
    Route::get('devices/get_devices', [DeviceController::class, 'get_devices'])->middleware('can:isAdminOrUser')->name('devices.get_devices');
    Route::post('devices/add/{id}', [DeviceController::class, 'add_device'])->middleware('can:isAdminOrUser')->name('devices.add_device');
    Route::post('devices/remove/{id}', [DeviceController::class, 'remove_device'])->middleware('can:isAdminOrUser')->name('devices.remove_device');
    Route::get('devices/add_device_setting_values/{id}', [DeviceController::class, 'add_device_setting_values'])->middleware('can:isAdminOrUser')->name('devices.add_device_setting_values');
    Route::post('devices/add_device_setting_values/{id}', [DeviceController::class, 'add_setting_values'])->middleware('can:isAdminOrUser')->name('devices.add_setting_values');
    Route::get('devices/add_device_limit_values/{id}', [DeviceController::class, 'add_device_limit_values'])->middleware('can:isAdminOrUser')->name('devices.add_device_limit_values');
    Route::post('devices/add_device_limit_values/{id}', [DeviceController::class, 'add_limit_values'])->middleware('can:isAdminOrUser')->name('devices.add_limit_values');

//    Route::resource('devices',DeviceController::class);
    Route::get('devices', [DeviceController::class, 'index'])->middleware('can:isAdminOrUser')->name('devices');
    Route::get('devices/create', [DeviceController::class, 'create'])->middleware('can:isAdminOrUser')->name('devices.create');
    Route::post('devices/store', [DeviceController::class, 'store'])->middleware('can:isAdminOrUser')->name('devices.store');
    Route::get('devices/edit/{id}', [DeviceController::class, 'edit'])->middleware('can:isAdminOrUser')->name('devices.edit');
    Route::get('devices/show/{id}', [DeviceController::class, 'show'])->middleware('can:isAdminOrUser')->name('devices.show');
    Route::get('devices/showWithDate/{id}/{from}/{to}', [DeviceController::class, 'showWithDate'])->middleware('can:isAdminOrUser')->name('devices.showWithDate');
    Route::post('devices/edit/{id}', [DeviceController::class, 'update'])->middleware('can:isAdminOrUser')->name('devices.update');
    Route::post('devices/update_location/{id}', [DeviceController::class, 'update_location'])->middleware('can:isAdminOrUser')->name('devices.update_location');
    Route::delete('devices/destroy/{id}',[DeviceController::class, 'destroy'])->middleware('can:isAdminOrUser')->name('devices.destroy');
    Route::get('devices/location/{id}', [DeviceController::class, 'location'])->middleware('can:isAdminOrUser')->name('devices.location');
    Route::get('devices/export/{id}',[DeviceController::class, 'export'])->middleware('can:isAdminOrUser')->name('devices.export');
    Route::post('devices/exportToDatasheet', [DeviceController::class, 'exportToDatasheet'])->middleware('can:isAdminOrUser')->name('devices.exportToDatasheet');
//    Route::post('devices/setLocation', [DeviceController::class, 'setLocation'])->middleware('can:isAdminOrUser')->name('devices.setLocation');
    Route::get('devices/getColumnChartData/{id}', [DeviceController::class, 'getColumnChartData'])->middleware('can:isAdminOrUser')->name('devices.getColumnChartData');
    Route::get('devices/getGaugeWithBandsData/{id}', [DeviceController::class, 'getGaugeWithBandsData'])->middleware('can:isAdminOrUser')->name('devices.getGaugeWithBandsData');
    Route::get('devices/getMultiAxisChartData/{id}', [DeviceController::class, 'getMultiAxisChartData'])->middleware('can:isAdminOrUser')->name('devices.getMultiAxisChartData');
    Route::get('devices/getDeviceStatus/{id}', [DeviceController::class, 'getDeviceStatus'])->middleware('can:isAdminOrUser')->name('devices.getDeviceStatus');


//    Route::resource('components',ComponentController::class);
//    Route::post('components/edit/{id}', [ComponentController::class, 'update'])->name('components.update');
    Route::get('components', [ComponentController::class, 'index'])->middleware('can:isAdmin')->name('components.index');
    Route::get('components/create', [ComponentController::class, 'create'])->middleware('can:isAdmin')->name('components.create');
    Route::post('components/store', [ComponentController::class, 'store'])->middleware('can:isAdminOrUser')->name('components.store');
    Route::get('components/edit/{id}', [ComponentController::class, 'edit'])->middleware('can:isAdmin')->name('components.edit');
    Route::get('components/show/{id}', [ComponentController::class, 'show'])->middleware('can:isAdmin')->name('components.show');
    Route::post('components/edit/{id}', [ComponentController::class, 'update'])->middleware('can:isAdmin')->name('components.update');
    Route::delete('components/destroy/{id}',[ComponentController::class, 'destroy'])->middleware('can:isAdmin')->name('components.destroy');


//    Route::resource('device_components',DeviceComponentController::class);
//    Route::post('device_components/edit/{id}', [DeviceComponentController::class, 'update'])->name('device_components.update');
    Route::get('device_components', [DeviceComponentController::class, 'index'])->middleware('can:isAdmin')->name('device_components.index');
    Route::get('device_components/create', [DeviceComponentController::class, 'create'])->middleware('can:isAdmin')->name('device_components.create');
    Route::post('device_components/store', [DeviceComponentController::class, 'store'])->middleware('can:isAdminOrUser')->name('device_components.store');
    Route::get('device_components/edit/{id}', [DeviceComponentController::class, 'edit'])->middleware('can:isAdmin')->name('device_components.edit');
    Route::get('device_components/show/{id}', [DeviceComponentController::class, 'show'])->middleware('can:isAdmin')->name('device_components.show');
    Route::post('device_components/edit/{id}', [DeviceComponentController::class, 'update'])->middleware('can:isAdmin')->name('device_components.update');
    Route::delete('device_components/destroy/{id}',[DeviceComponentController::class, 'destroy'])->middleware('can:isAdmin')->name('device_components.destroy');


    Route::get('device_components/editDisplay/{id}', [DeviceComponentController::class, 'editDisplay'])->name('device_components.editDisplay');
    Route::post('device_components/updateDisplay/{id}', [DeviceComponentController::class, 'updateDisplay'])->name('device_components.updateDisplay');


    Route::get('component_settings', [ComponentSettingsController::class, 'index'])->middleware('can:isAdmin')->name('component_settings');
    Route::get('component_settings/create', [ComponentSettingsController::class, 'create'])->middleware('can:isAdmin')->name('component_settings.create');
    Route::post('component_settings/store', [ComponentSettingsController::class, 'store'])->middleware('can:isAdminOrUser')->name('component_settings.store');
    Route::get('component_settings/edit/{id}', [ComponentSettingsController::class, 'edit'])->middleware('can:isAdmin')->name('component_settings.edit');
    Route::get('component_settings/show/{id}', [ComponentSettingsController::class, 'show'])->middleware('can:isAdmin')->name('component_settings.show');
    Route::post('component_settings/edit/{id}', [ComponentSettingsController::class, 'update'])->middleware('can:isAdmin')->name('component_settings.update');
    Route::delete('component_settings/destroy/{id}',[ComponentSettingsController::class, 'destroy'])->middleware('can:isAdmin')->name('component_settings.destroy');


    Route::get('documentaion', [GeneralController::class, 'documentaion'])->name('documentaion');
    Route::get('test', [GeneralController::class, 'test'])->name('test');


    Route::get('factories', [FactoryController::class, 'index'])->middleware('can:isAdmin')->name('factories');
    Route::get('factories/create', [FactoryController::class, 'create'])->middleware('can:isAdmin')->name('factories.create');
    Route::post('factories/store', [FactoryController::class, 'store'])->middleware('can:isAdminOrUser')->name('factories.store');
    Route::get('factories/edit/{id}', [FactoryController::class, 'edit'])->middleware('can:isAdmin')->name('factories.edit');
    Route::get('factories/show/{id}', [FactoryController::class, 'show'])->middleware('can:isAdmin')->name('factories.show');
    Route::post('factories/edit/{id}', [FactoryController::class, 'update'])->middleware('can:isAdmin')->name('factories.update');
    Route::delete('factories/destroy/{id}',[FactoryController::class, 'destroy'])->middleware('can:isAdmin')->name('factories.destroy');
    Route::get('factories/start/{id}', [FactoryController::class, 'start'])->middleware('can:isAdmin')->name('factories.start');
    Route::post('factories/attach/{id}', [FactoryController::class, 'attach'])->middleware('can:isAdmin')->name('factories.attach');
    Route::get('factories/stop/{id}', [FactoryController::class, 'stop'])->middleware('can:isAdmin')->name('factories.stop');
    Route::post('factories/detach/{id}', [FactoryController::class, 'detach'])->middleware('can:isAdmin')->name('factories.detach');
    Route::get('factories/details/{id}', [FactoryController::class, 'details'])->middleware('can:isAdmin')->name('factories.details');
    Route::get('factories/export/{id}',[FactoryController::class, 'export'])->middleware('can:isAdmin')->name('factories.export');
//    Route::post('factories/detail/{id}', [FactoryController::class, 'details1'])->middleware('can:isAdmin')->name('factories.test');
    Route::post('factories/detail/{id}', [FactoryController::class, 'details1'] )->name('factories.detail');
    Route::get('factories/flow/{id}', [FactoryController::class, 'flowchartWithDate'])->middleware('can:isAdmin')->name('factories.flowchartWithDate');
});
