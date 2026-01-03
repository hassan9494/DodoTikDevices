<?php

namespace Database\Factories;

use App\Models\Device;
use App\Models\DeviceComponent as DeviceComponentModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DeviceComponentModel>
 */
class DeviceComponentFactory extends Factory
{
    protected $model = DeviceComponentModel::class;

    public function definition(): array
    {
        return [
            'device_id' => Device::factory(),
            'components' => json_encode([
                'component_ids' => [],
            ]),
            'settings' => json_encode([
                'layout' => 'grid',
                'refresh_rate' => $this->faker->numberBetween(10, 120),
            ]),
        ];
    }
}
