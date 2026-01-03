<?php

namespace Database\Seeders;

use App\Models\About;
use App\Models\Component;
use App\Models\ComponentSettings;
use App\Models\Device;
use App\Models\DeviceFactory;
use App\Models\DeviceFactoryValue;
use App\Models\DeviceParameters;
use App\Models\DeviceParametersValues;
use App\Models\DeviceSettingPerDevice;
use App\Models\DeviceSettings;
use App\Models\DeviceType;
use App\Models\Factory;
use App\Models\FilesParametersValues;
use App\Models\FtpFile;
use App\Models\General;
use App\Models\LimitValues;
use App\Models\ParameterRangeColor;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::factory()->administrator()->create([
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
        ]);

        $operator = User::factory()->create([
            'email' => 'operator@example.com',
            'email_verified_at' => now(),
        ]);

        General::factory()->create([
            'email' => 'contact@example.com',
        ]);
        About::factory()->create();

        $deviceType = DeviceType::factory()->create(['name' => 'Flow Meter']);
        $deviceSettings = DeviceSettings::factory()->count(2)->create();
        $parameters = DeviceParameters::factory()->count(3)->create();

        foreach ($deviceSettings as $index => $setting) {
            $deviceType->deviceSettings()->attach($setting->id, [
                'value' => json_encode(['default' => $index + 1]),
            ]);
        }

        foreach ($parameters as $index => $parameter) {
            $deviceType->deviceParameters()->attach($parameter->id, [
                'order' => $index + 1,
                'length' => 4,
                'rate' => 60,
                'color' => ['#1abc9c', '#3498db', '#e74c3c'][$index % 3],
            ]);

            ParameterRangeColor::factory()->forParameter($parameter)->create([
                'from' => 10,
                'to' => 90,
            ]);
        }

        $device = Device::factory()
            ->ownedBy($operator)
            ->forType($deviceType)
            ->create([
                'device_id' => 'DEV-SEED-1001',
            ]);

        DeviceSettingPerDevice::factory()->forDevice($device)->create([
            'settings' => json_encode([
                'sampling_rate' => 30,
                'threshold' => 75,
            ]),
        ]);

        LimitValues::factory()->forDevice($device)->withThresholds(
            ['temperature' => 20, 'pressure' => 40],
            ['temperature' => 60, 'pressure' => 80]
        )->warnings(true, true)->create();

        DeviceParametersValues::factory()
            ->forDevice($device)
            ->withParameters([
                $parameters[0]->code => 35,
                $parameters[1]->code => 55,
                $parameters[2]->code => 65,
            ])
            ->count(5)
            ->create();

        $component = Component::factory()->create(['name' => 'Gauge Widget']);
        $componentSetting = ComponentSettings::factory()->create();
        $component->componentSettings()->attach($componentSetting->id);

        $device->deviceComponents()->create([
            'component_id' => $component->id,
            'order' => 1,
            'width' => 6,
            'settings' => json_encode([
                'parameters' => [$parameters[0]->id],
                'options' => ['title' => 'Temperature Gauge'],
            ]),
        ]);

        $device->deviceComponent()->create([
            'components' => json_encode([$component->id]),
            'settings' => json_encode([
                'layout' => 'grid',
                'refresh_rate' => 30,
            ]),
        ]);

        $factory = Factory::factory()->create(['name' => 'Seed Factory']);

        $deviceFactory = DeviceFactory::create([
            'device_id' => $device->id,
            'factory_id' => $factory->id,
            'start_date' => Carbon::now()->subDays(7),
            'is_attached' => true,
        ]);

        DeviceFactoryValue::factory()
            ->forDevice($device)
            ->forFactory($factory)
            ->forDeviceFactory($deviceFactory)
            ->count(3)
            ->create();

        $file = FtpFile::factory()->create(['name' => 'seed_file', 'extension' => 'csv']);
        FilesParametersValues::factory()->forFile($file)->count(3)->create();
    }
}
