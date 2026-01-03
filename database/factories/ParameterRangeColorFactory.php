<?php

namespace Database\Factories;

use App\Models\DeviceParameters;
use App\Models\ParameterRangeColor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ParameterRangeColor>
 */
class ParameterRangeColorFactory extends Factory
{
    protected $model = ParameterRangeColor::class;

    public function definition(): array
    {
        $from = $this->faker->numberBetween(0, 50);
        $to = $from + $this->faker->numberBetween(5, 20);

        return [
            'parameter_id' => DeviceParameters::factory(),
            'from' => $from,
            'to' => $to,
            'color' => $this->faker->hexColor(),
            'level_name' => $this->faker->randomElement(['Normal', 'Warning', 'Critical']),
            'description' => $this->faker->sentence(),
        ];
    }

    public function forParameter(DeviceParameters $parameter): static
    {
        return $this->state(fn () => [
            'parameter_id' => $parameter->id,
        ]);
    }
}
