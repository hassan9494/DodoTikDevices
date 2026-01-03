<?php

namespace Database\Factories;

use App\Models\Device;
use App\Models\DeviceSettingPerDevice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DeviceSettingPerDevice>
 */
class DeviceSettingPerDeviceFactory extends Factory
{
    protected $model = DeviceSettingPerDevice::class;

    public function definition(): array
    {
        return [
            'device_id' => Device::factory(),
            'settings' => json_encode([
                'sampling_rate' => $this->faker->numberBetween(1, 60),
                'threshold' => $this->faker->numberBetween(10, 100),
            ]),
        ];
    }

    public function forDevice(Device $device): static
    {
        return $this->state(fn () => [
            'device_id' => $device->id,
        ]);
    }
}
