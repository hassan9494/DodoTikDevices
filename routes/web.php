<?php

use App\Http\Controllers\{
    Auth\ForgotPasswordController,
    ComponentController,
    ComponentSettingsController,
    CSVController,
    DeviceComponentController,
    DeviceController,
    DeviceParametersController,
    DeviceSettingController,
    DevicTypeController,
    FactoryController,
    FrontController,
    FtpFileController,
    GeneralController,
    HomeController,
    SubscriptionCodeController,
    ProfileController,
    UserController,
    UserSubscriptionController
};

use Illuminate\Support\Facades\Route;

Route::get('/', [FrontController::class, 'home'])->name('homepage');
Route::get('about-us', [FrontController::class, 'about'])->name('about');
Route::post('/upload-csv', [CSVController::class, 'store']);

Route::get('forget-password', [ForgotPasswordController::class, 'showForgetPasswordForm'])->name('forget.password.get');
Route::post('forget-password', [ForgotPasswordController::class, 'submitForgetPasswordForm'])->name('forget.password.post');
Route::get('reset-password/{token}', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('reset.password.get');
Route::post('reset-password', [ForgotPasswordController::class, 'submitResetPasswordForm'])->name('reset.password.post');

Route::middleware(['auth', 'subscription.required'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::get('migrate', [FrontController::class, 'migrate'])->middleware('can:isAdmin')->name('migrate');

Route::middleware(['auth', 'verified', 'subscription.required'])->group(function () {
    Route::get('/dashboard', function () {
        return redirect()->route('admin.dashboard');
    })->name('dashboard');

    Route::get('/home', [HomeController::class, 'index'])->middleware('can:isAdmin')->name('home');

    Route::get('subscription', [UserSubscriptionController::class, 'create'])->name('subscription.prompt');
    Route::post('subscription/redeem', [UserSubscriptionController::class, 'store'])->name('subscription.redeem');

    Route::group(['as' => 'admin.', 'prefix' => 'admin'], function () {
        Route::get('migrate', [FrontController::class, 'migrate'])->middleware('can:isAdmin')->name('migrate');
        Route::get('dashboard', [GeneralController::class, 'dashboard'])->name('dashboard');

        Route::get('general-settings', [GeneralController::class, 'general'])->middleware('can:isAdmin')->name('general');
        Route::post('general-settings', [GeneralController::class, 'generalUpdate'])->middleware('can:isAdmin')->name('general.update');

        Route::get('about', [GeneralController::class, 'about'])->middleware('can:isAdmin')->name('about');
        Route::post('about', [GeneralController::class, 'aboutUpdate'])->middleware('can:isAdmin')->name('about.update');

        Route::get('users', [UserController::class, 'index'])->middleware('can:isAdminOrUser')->name('users.index');
        Route::post('users/{id}', [UserController::class, 'changepassword'])->middleware('can:isAdminOrUser')->name('users.changepassword');
        Route::get('users/create', [UserController::class, 'create'])->middleware('can:isAdminOrUser')->name('users.create');
        Route::get('users/show/{id}', [UserController::class, 'show'])->middleware('can:isAdmin')->name('users.show');
        Route::post('users', [UserController::class, 'store'])->middleware('can:isAdminOrUser')->name('users.store');
        Route::get('users/edit/{id}', [UserController::class, 'edit'])->middleware('can:isAdminOrUser')->name('users.edit');
        Route::post('users/edit/{id}', [UserController::class, 'update'])->middleware('can:isAdminOrUser')->name('users.update');
        Route::delete('users/destroy/{id}', [UserController::class, 'destroy'])->middleware('can:isAdmin')->name('users.destroy');
        Route::post('users/{id}/toggle-status', [UserController::class, 'toggleStatus'])->middleware('can:isAdmin')->name('users.toggle-status');

        Route::get('device_types/add_default_values/{typeid}', [DevicTypeController::class, 'add_default_values'])->middleware('can:isAdmin')->name('device_types.add_default_values');
        Route::post('device_types/add_default_values/{typeid}', [DevicTypeController::class, 'add_default'])->middleware('can:isAdmin')->name('device_types.add_default');

        Route::get('device_types', [DevicTypeController::class, 'index'])->middleware('can:isAdmin')->name('device_types');
        Route::get('device_types/create', [DevicTypeController::class, 'create'])->middleware('can:isAdmin')->name('device_types.create');
        Route::post('device_types/store', [DevicTypeController::class, 'store'])->middleware('can:isAdminOrUser')->name('device_types.store');
        Route::get('device_types/edit/{id}', [DevicTypeController::class, 'edit'])->middleware('can:isAdmin')->name('device_types.edit');
        Route::get('device_types/show/{id}', [DevicTypeController::class, 'show'])->middleware('can:isAdmin')->name('device_types.show');
        Route::post('device_types/edit/{id}', [DevicTypeController::class, 'update'])->middleware('can:isAdmin')->name('device_types.update');
        Route::delete('device_types/destroy/{id}', [DevicTypeController::class, 'destroy'])->middleware('can:isAdmin')->name('device_types.destroy');

        Route::get('device_parameters', [DeviceParametersController::class, 'index'])->middleware('can:isAdmin')->name('device_parameters');
        Route::get('device_parameters/create', [DeviceParametersController::class, 'create'])->middleware('can:isAdmin')->name('device_parameters.create');
        Route::post('device_parameters/store', [DeviceParametersController::class, 'store'])->middleware('can:isAdminOrUser')->name('device_parameters.store');
        Route::get('device_parameters/edit/{id}', [DeviceParametersController::class, 'edit'])->middleware('can:isAdmin')->name('device_parameters.edit');
        Route::get('device_parameters/show/{id}', [DeviceParametersController::class, 'show'])->middleware('can:isAdmin')->name('device_parameters.show');
        Route::post('device_parameters/edit/{id}', [DeviceParametersController::class, 'update'])->middleware('can:isAdmin')->name('device_parameters.update');
        Route::delete('device_parameters/destroy/{id}', [DeviceParametersController::class, 'destroy'])->middleware('can:isAdmin')->name('device_parameters.destroy');
        Route::get('device_parameters/color/{id}', [DeviceParametersController::class, 'color'])->middleware('can:isAdmin')->name('device_parameters.color');
        Route::post('device_parameters/color_range/{id}', [DeviceParametersController::class, 'color_range'])->middleware('can:isAdmin')->name('device_parameters.color_range');
        Route::post('device_parameters/update_color_range/{id}', [DeviceParametersController::class, 'update_color_range'])->middleware('can:isAdmin')->name('device_parameters.update_color_range');

        Route::get('device_setting', [DeviceSettingController::class, 'index'])->middleware('can:isAdmin')->name('device_setting');
        Route::get('device_setting/create', [DeviceSettingController::class, 'create'])->middleware('can:isAdmin')->name('device_setting.create');
        Route::post('device_setting/store', [DeviceSettingController::class, 'store'])->middleware('can:isAdminOrUser')->name('device_setting.store');
        Route::get('device_setting/edit/{id}', [DeviceSettingController::class, 'edit'])->middleware('can:isAdmin')->name('device_setting.edit');
        Route::get('device_setting/show/{id}', [DeviceSettingController::class, 'show'])->middleware('can:isAdmin')->name('device_setting.show');
        Route::post('device_setting/edit/{id}', [DeviceSettingController::class, 'update'])->middleware('can:isAdmin')->name('device_setting.update');
        Route::delete('device_setting/destroy/{id}', [DeviceSettingController::class, 'destroy'])->middleware('can:isAdmin')->name('device_setting.destroy');

        Route::middleware(['can:isAdminOrUser', 'subscription.required'])->group(function () {
            Route::get('devices/add', [DeviceController::class, 'add'])->name('devices.add');
            Route::get('devices/get_devices', [DeviceController::class, 'get_devices'])->name('devices.get_devices');
            Route::post('devices/add/{id}', [DeviceController::class, 'add_device'])->name('devices.add_device');
            Route::post('devices/remove/{id}', [DeviceController::class, 'remove_device'])->name('devices.remove_device');
            Route::get('devices/add_device_setting_values/{id}', [DeviceController::class, 'add_device_setting_values'])->name('devices.add_device_setting_values');
            Route::post('devices/add_device_setting_values/{id}', [DeviceController::class, 'add_setting_values'])->name('devices.add_setting_values');
            Route::get('devices/add_device_limit_values/{id}', [DeviceController::class, 'add_device_limit_values'])->name('devices.add_device_limit_values');
            Route::post('devices/add_device_limit_values/{id}', [DeviceController::class, 'add_limit_values'])->name('devices.add_limit_values');
            Route::get('devices', [DeviceController::class, 'index'])->name('devices');
            Route::get('devices/create', [DeviceController::class, 'create'])->name('devices.create');
            Route::post('devices/store', [DeviceController::class, 'store'])->name('devices.store');
            Route::get('devices/edit/{id}', [DeviceController::class, 'edit'])->name('devices.edit');
            Route::get('devices/show/{id}', [DeviceController::class, 'show'])->name('devices.show');
            Route::get('devices/showWithDate/{id}/{from}/{to}', [DeviceController::class, 'showWithDate'])->name('devices.showWithDate');
            Route::post('devices/edit/{id}', [DeviceController::class, 'update'])->name('devices.update');
            Route::post('devices/update_location/{id}', [DeviceController::class, 'update_location'])->name('devices.update_location');
            Route::delete('devices/destroy/{id}', [DeviceController::class, 'destroy'])->name('devices.destroy');
            Route::get('devices/location/{id}', [DeviceController::class, 'location'])->name('devices.location');
            Route::get('devices/export/{id}', [DeviceController::class, 'export'])->name('devices.export');
            Route::post('devices/exportToDatasheet', [DeviceController::class, 'exportToDatasheet'])->name('devices.exportToDatasheet');
            Route::get('devices/getColumnChartData/{id}', [DeviceController::class, 'getColumnChartData'])->name('devices.getColumnChartData');
            Route::get('devices/getGaugeWithBandsData/{id}', [DeviceController::class, 'getGaugeWithBandsData'])->name('devices.getGaugeWithBandsData');
            Route::get('devices/getMultiAxisChartData/{id}', [DeviceController::class, 'getMultiAxisChartData'])->name('devices.getMultiAxisChartData');
            Route::get('devices/getDeviceStatus/{id}', [DeviceController::class, 'getDeviceStatus'])->name('devices.getDeviceStatus');
            Route::get('device/showParameterData/{device_id}/{parameter_id}', [DeviceController::class, 'showParameterData'])->name('device.showParameterData');
            Route::get('device/showParameterDataWithDate/{device_id}/{parameter_id}/{from}/{to}', [DeviceController::class, 'showParameterDataWithDate'])->name('device.showParameterDataWithDate');
            Route::get('devices/showParameterData/{devFactory_id}/{parameter_id}', [FactoryController::class, 'showParameterData'])->name('devices.showParameterData');
            Route::get('devices/showParameterDataWithDate/{devFactory_id}/{parameter_id}/{from}/{to}', [FactoryController::class, 'showParameterDataWithDate'])->name('devices.showParameterDataWithDate');
        });

        Route::middleware('can:isAdmin')->group(function () {
            Route::resource('subscription-codes', SubscriptionCodeController::class)->except(['show']);
            Route::post('subscription-codes/{subscription_code}/toggle', [SubscriptionCodeController::class, 'toggle'])->name('subscription-codes.toggle');
            Route::get('subscription-activations', [SubscriptionCodeController::class, 'activations'])->name('subscription-activations.index');
        });

        Route::get('components', [ComponentController::class, 'index'])->middleware('can:isAdmin')->name('components.index');
        Route::get('components/create', [ComponentController::class, 'create'])->middleware('can:isAdmin')->name('components.create');
        Route::post('components/store', [ComponentController::class, 'store'])->middleware('can:isAdminOrUser')->name('components.store');
        Route::get('components/edit/{id}', [ComponentController::class, 'edit'])->middleware('can:isAdmin')->name('components.edit');
        Route::get('components/show/{id}', [ComponentController::class, 'show'])->middleware('can:isAdmin')->name('components.show');
        Route::post('components/edit/{id}', [ComponentController::class, 'update'])->middleware('can:isAdmin')->name('components.update');
        Route::delete('components/destroy/{id}', [ComponentController::class, 'destroy'])->middleware('can:isAdmin')->name('components.destroy');

        Route::get('device_components', [DeviceComponentController::class, 'index'])->middleware('can:isAdmin')->name('device_components.index');
        Route::get('device_components/create', [DeviceComponentController::class, 'create'])->middleware('can:isAdmin')->name('device_components.create');
        Route::post('device_components/store', [DeviceComponentController::class, 'store'])->middleware('can:isAdminOrUser')->name('device_components.store');
        Route::get('device_components/edit/{id}', [DeviceComponentController::class, 'edit'])->middleware('can:isAdmin')->name('device_components.edit');
        Route::get('device_components/show/{id}', [DeviceComponentController::class, 'show'])->middleware('can:isAdmin')->name('device_components.show');
        Route::post('device_components/edit/{id}', [DeviceComponentController::class, 'update'])->middleware('can:isAdmin')->name('device_components.update');
        Route::delete('device_components/destroy/{id}', [DeviceComponentController::class, 'destroy'])->middleware('can:isAdmin')->name('device_components.destroy');
        Route::get('device_components/editDisplay/{id}', [DeviceComponentController::class, 'editDisplay'])->name('device_components.editDisplay');
        Route::post('device_components/updateDisplay/{id}', [DeviceComponentController::class, 'updateDisplay'])->name('device_components.updateDisplay');

        Route::get('component_settings', [ComponentSettingsController::class, 'index'])->middleware('can:isAdmin')->name('component_settings');
        Route::get('component_settings/create', [ComponentSettingsController::class, 'create'])->middleware('can:isAdmin')->name('component_settings.create');
        Route::post('component_settings/store', [ComponentSettingsController::class, 'store'])->middleware('can:isAdminOrUser')->name('component_settings.store');
        Route::get('component_settings/edit/{id}', [ComponentSettingsController::class, 'edit'])->middleware('can:isAdmin')->name('component_settings.edit');
        Route::get('component_settings/show/{id}', [ComponentSettingsController::class, 'show'])->middleware('can:isAdmin')->name('component_settings.show');
        Route::post('component_settings/edit/{id}', [ComponentSettingsController::class, 'update'])->middleware('can:isAdmin')->name('component_settings.update');
        Route::delete('component_settings/destroy/{id}', [ComponentSettingsController::class, 'destroy'])->middleware('can:isAdmin')->name('component_settings.destroy');

        Route::get('documentaion', [GeneralController::class, 'documentaion'])->name('documentaion');
        Route::get('test', [GeneralController::class, 'test'])->name('test');

        Route::get('factories', [FactoryController::class, 'index'])->middleware('can:isAdmin')->name('factories');
        Route::get('factories/create', [FactoryController::class, 'create'])->middleware('can:isAdmin')->name('factories.create');
        Route::post('factories/store', [FactoryController::class, 'store'])->middleware('can:isAdminOrUser')->name('factories.store');
        Route::get('factories/edit/{id}', [FactoryController::class, 'edit'])->middleware('can:isAdmin')->name('factories.edit');
        Route::get('factories/show/{id}', [FactoryController::class, 'show'])->middleware('can:isAdmin')->name('factories.show');
        Route::post('factories/edit/{id}', [FactoryController::class, 'update'])->middleware('can:isAdmin')->name('factories.update');
        Route::delete('factories/destroy/{id}', [FactoryController::class, 'destroy'])->middleware('can:isAdmin')->name('factories.destroy');
        Route::get('factories/start/{id}', [FactoryController::class, 'start'])->middleware('can:isAdmin')->name('factories.start');
        Route::post('factories/attach/{id}', [FactoryController::class, 'attach'])->middleware('can:isAdmin')->name('factories.attach');
        Route::get('factories/stop/{id}', [FactoryController::class, 'stop'])->middleware('can:isAdmin')->name('factories.stop');
        Route::post('factories/detach/{id}', [FactoryController::class, 'detach'])->middleware('can:isAdmin')->name('factories.detach');
        Route::get('factories/details/{id}', [FactoryController::class, 'details'])->middleware('can:isAdmin')->name('factories.details');
        Route::get('factories/export/{id}', [FactoryController::class, 'export'])->middleware('can:isAdmin')->name('factories.export');
        Route::post('factories/detail/{id}', [FactoryController::class, 'details1'])->name('factories.detail');
        Route::get('factories/flow/{id}/{from}/{to}', [FactoryController::class, 'flowchartWithDate'])->middleware('can:isAdmin')->name('factories.flowchartWithDate');
        Route::get('testData', [GeneralController::class, 'testData'])->name('testData');

        Route::get('files', [FtpFileController::class, 'index'])->middleware('can:isAdmin')->name('files');
        Route::get('files/import', [FtpFileController::class, 'import'])->middleware('can:isAdmin')->name('files.import');
        Route::get('files/create', [FtpFileController::class, 'create'])->middleware('can:isAdmin')->name('files.create');
        Route::post('files/store', [FtpFileController::class, 'store'])->middleware('can:isAdminOrUser')->name('files.store');
        Route::get('files/edit/{id}', [FtpFileController::class, 'edit'])->middleware('can:isAdmin')->name('files.edit');
        Route::get('files/show/{id}', [FtpFileController::class, 'show'])->middleware('can:isAdmin')->name('files.show');
        Route::post('files/edit/{id}', [FtpFileController::class, 'update'])->middleware('can:isAdmin')->name('files.update');
        Route::delete('files/destroy/{id}', [FtpFileController::class, 'destroy'])->middleware('can:isAdmin')->name('files.destroy');
        Route::get('files/showWithDate/{id}/{from}/{to}', [FtpFileController::class, 'showWithDate'])->middleware('can:isAdminOrUser')->name('files.showWithDate');
        Route::get('files/export/{id}', [FtpFileController::class, 'export'])->middleware('can:isAdminOrUser')->name('files.export');
        Route::post('files/exportToDatasheet', [FtpFileController::class, 'exportToDatasheet'])->middleware('can:isAdminOrUser')->name('files.exportToDatasheet');
    });
});

require __DIR__.'/auth.php';
