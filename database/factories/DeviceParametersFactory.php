<?php

namespace Database\Factories;

use App\Models\DeviceParameters;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<DeviceParameters>
 */
class DeviceParametersFactory extends Factory
{
    protected $model = DeviceParameters::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->words(2, true),
            'code' => Str::upper($this->faker->unique()->lexify('P???')),
            'unit' => $this->faker->randomElement(['°C', 'ppm', '%', 'bar']),
        ];
    }
}
