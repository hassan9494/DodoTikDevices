<?php

namespace Database\Factories;

use App\Models\Component;
use App\Models\DevicesComponents;
use App\Models\Device;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DevicesComponents>
 */
class DevicesComponentsFactory extends Factory
{
    protected $model = DevicesComponents::class;

    public function definition(): array
    {
        return [
            'device_id' => Device::factory(),
            'component_id' => Component::factory(),
            'settings' => json_encode([
                'parameters' => [],
                'options' => [
                    'title' => $this->faker->words(2, true),
                ],
            ]),
            'order' => $this->faker->numberBetween(1, 10),
            'width' => $this->faker->randomElement([3, 4, 6, 12]),
        ];
    }

    public function forDevice(Device $device): static
    {
        return $this->state(fn () => [
            'device_id' => $device->id,
        ]);
    }

    public function forComponent(Component $component): static
    {
        return $this->state(fn () => [
            'component_id' => $component->id,
        ]);
    }

    public function withSettings(array $settings): static
    {
        return $this->state(fn (array $attributes) => [
            'settings' => json_encode(array_merge(json_decode($attributes['settings'] ?? '{}', true) ?: [], $settings)),
        ]);
    }
}
