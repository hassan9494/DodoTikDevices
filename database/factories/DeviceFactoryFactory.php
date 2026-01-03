<?php

namespace Database\Factories;

use App\Models\Device;
use App\Models\DeviceFactory;
use App\Models\Factory as FactoryModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DeviceFactory>
 */
class DeviceFactoryFactory extends Factory
{
    protected $model = DeviceFactory::class;

    public function definition(): array
    {
        return [
            'device_id' => Device::factory(),
            'factory_id' => FactoryModel::factory(),
            'start_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'is_attached' => true,
        ];
    }
}
