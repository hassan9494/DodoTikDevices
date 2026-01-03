<?php

namespace Database\Factories;

use App\Models\Device;
use App\Models\DeviceFactory;
use App\Models\DeviceFactoryValue;
use App\Models\Factory as FactoryModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DeviceFactoryValue>
 */
class DeviceFactoryValueFactory extends Factory
{
    protected $model = DeviceFactoryValue::class;

    public function definition(): array
    {
        $parameters = [
            'temperature' => $this->faker->randomFloat(2, 10, 40),
            'pressure' => $this->faker->randomFloat(2, 20, 90),
        ];

        return [
            'device_id' => Device::factory(),
            'factory_id' => FactoryModel::factory(),
            'device_factory_id' => DeviceFactory::factory(),
            'parameters' => json_encode($parameters),
            'time_of_read' => $this->faker->dateTimeBetween('-2 days', 'now'),
        ];
    }

    public function forDevice(Device $device): static
    {
        return $this->state(fn () => [
            'device_id' => $device->id,
        ]);
    }

    public function forFactory(FactoryModel $factory): static
    {
        return $this->state(fn () => [
            'factory_id' => $factory->id,
        ]);
    }

    public function forDeviceFactory(DeviceFactory $deviceFactory): static
    {
        return $this->state(fn () => [
            'device_factory_id' => $deviceFactory->id,
        ]);
    }

    public function withParameters(array $parameters): static
    {
        return $this->state(fn () => [
            'parameters' => json_encode($parameters),
        ]);
    }
}
