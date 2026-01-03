<?php

namespace Database\Factories;

use App\Models\Device;
use App\Models\DeviceType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Device>
 */
class DeviceFactory extends Factory
{
    protected $model = Device::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'device_id' => strtoupper($this->faker->bothify('DEV-####-????')),
            'user_id' => User::factory()->administrator(),
            'type_id' => DeviceType::factory(),
            'tolerance' => $this->faker->numberBetween(1, 10),
            'time_between_two_read' => $this->faker->numberBetween(1, 60),
            'longitude' => $this->faker->longitude(),
            'latitude' => $this->faker->latitude(),
        ];
    }

    public function ownedBy(User $user): static
    {
        return $this->state(fn () => [
            'user_id' => $user->id,
        ]);
    }

    public function forType(DeviceType $type): static
    {
        return $this->state(fn () => [
            'type_id' => $type->id,
        ]);
    }
}
