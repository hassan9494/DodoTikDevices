<?php

namespace Database\Factories;

use App\Models\About;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<About>
 */
class AboutFactory extends Factory
{
    protected $model = About::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(4),
            'subject' => $this->faker->sentence(8),
            'desc' => $this->faker->paragraphs(3, true),
        ];
    }
}
