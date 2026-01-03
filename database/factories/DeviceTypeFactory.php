<?php

namespace Database\Factories;

use App\Models\DeviceType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DeviceType>
 */
class DeviceTypeFactory extends Factory
{
    protected $model = DeviceType::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->words(2, true),
        ];
    }
}
