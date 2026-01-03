<?php

namespace Database\Factories;

use App\Models\Device;
use App\Models\DeviceParametersValues;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DeviceParametersValues>
 */
class DeviceParametersValuesFactory extends Factory
{
    protected $model = DeviceParametersValues::class;

    public function definition(): array
    {
        return [
            'device_id' => Device::factory(),
            'parameters' => json_encode([
                'P001' => $this->faker->randomFloat(2, 0, 100),
            ]),
            'time_of_read' => $this->faker->dateTimeBetween('-1 day', 'now'),
        ];
    }

    public function withParameters(array $parameters): static
    {
        return $this->state(fn () => [
            'parameters' => json_encode($parameters),
        ]);
    }

    public function forDevice(Device $device): static
    {
        return $this->state(fn () => [
            'device_id' => $device->id,
        ]);
    }
}
