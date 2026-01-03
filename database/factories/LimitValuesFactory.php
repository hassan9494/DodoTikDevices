<?php

namespace Database\Factories;

use App\Models\Device;
use App\Models\LimitValues;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends Factory<LimitValues>
 */
class LimitValuesFactory extends Factory
{
    protected $model = LimitValues::class;

    public function definition(): array
    {
        $defaults = [
            'temperature' => $this->faker->numberBetween(10, 20),
            'pressure' => $this->faker->numberBetween(30, 50),
        ];

        return [
            'device_id' => Device::factory(),
            'min_value' => json_encode($defaults),
            'max_value' => json_encode(Arr::map($defaults, fn ($value) => $value + $this->faker->numberBetween(5, 15))),
            'min_warning' => true,
            'max_warning' => true,
        ];
    }

    public function forDevice(Device $device): static
    {
        return $this->state(fn () => [
            'device_id' => $device->id,
        ]);
    }

    public function withThresholds(array $min, array $max): static
    {
        return $this->state(fn () => [
            'min_value' => json_encode($min),
            'max_value' => json_encode($max),
        ]);
    }

    public function warnings(bool $minEnabled, bool $maxEnabled): static
    {
        return $this->state(fn () => [
            'min_warning' => $minEnabled,
            'max_warning' => $maxEnabled,
        ]);
    }
}
