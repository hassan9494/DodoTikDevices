<?php

namespace Database\Factories;

use App\Models\FilesParametersValues;
use App\Models\FtpFile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<FilesParametersValues>
 */
class FilesParametersValuesFactory extends Factory
{
    protected $model = FilesParametersValues::class;

    public function definition(): array
    {
        return [
            'file_id' => FtpFile::factory(),
            'parameters' => json_encode([
                'flow' => $this->faker->randomFloat(2, 0, 1000),
                'tot' => $this->faker->randomFloat(2, 0, 10000),
            ]),
            'time_of_read' => $this->faker->dateTimeBetween('-7 days', 'now'),
        ];
    }

    public function forFile(FtpFile $file): static
    {
        return $this->state(fn () => [
            'file_id' => $file->id,
        ]);
    }

    public function withParameters(array $parameters): static
    {
        return $this->state(fn () => [
            'parameters' => json_encode($parameters),
        ]);
    }
}
