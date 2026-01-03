<?php

namespace Database\Factories;

use App\Models\Component;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Component>
 */
class ComponentFactory extends Factory
{
    protected $model = Component::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word(),
            'desc' => $this->faker->sentence(),
            'image' => $this->faker->imageUrl(320, 240, 'technology'),
            'slug' => $this->faker->unique()->slug(),
            'settings' => json_encode(['options' => ['title' => $this->faker->sentence(3)]]),
        ];
    }
}
