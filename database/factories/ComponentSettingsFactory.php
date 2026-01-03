<?php

namespace Database\Factories;

use App\Models\ComponentSettings;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<ComponentSettings>
 */
class ComponentSettingsFactory extends Factory
{
    protected $model = ComponentSettings::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->words(2, true);

        return [
            'name' => $name,
            'slug' => Str::slug($name) . '-' . $this->faker->unique()->numberBetween(1, 999),
            'settings' => json_encode([
                'parameters' => [],
                'options' => [
                    'color' => $this->faker->hexColor(),
                ],
            ]),
        ];
    }
}
