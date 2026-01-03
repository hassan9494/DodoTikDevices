<?php

namespace Database\Factories;

use App\Models\General;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<General>
 */
class GeneralFactory extends Factory
{
    protected $model = General::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->company(),
            'favicon' => 'images/general/favicon.png',
            'logo' => 'images/general/logo.png',
            'address1' => $this->faker->address(),
            'address2' => $this->faker->secondaryAddress(),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->companyEmail(),
            'twitter' => $this->faker->url(),
            'facebook' => $this->faker->url(),
            'instagram' => $this->faker->url(),
            'linkedin' => $this->faker->url(),
            'footer' => $this->faker->sentence(),
            'gmaps' => $this->faker->url(),
            'tawkto' => $this->faker->url(),
            'disqus' => $this->faker->url(),
            'gverification' => $this->faker->uuid(),
            'sharethis' => $this->faker->url(),
            'keyword' => implode(', ', $this->faker->words(5)),
            'meta_desc' => $this->faker->sentence(12),
        ];
    }
}
