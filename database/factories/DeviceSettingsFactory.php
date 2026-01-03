<?php

namespace Database\Factories;

use App\Models\DeviceSettings;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<DeviceSettings>
 */
class DeviceSettingsFactory extends Factory
{
    protected $model = DeviceSettings::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->words(2, true),
            'code' => Str::upper($this->faker->unique()->lexify('S???')),
        ];
    }
}
