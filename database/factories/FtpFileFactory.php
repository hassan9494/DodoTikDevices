<?php

namespace Database\Factories;

use App\Models\FtpFile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<FtpFile>
 */
class FtpFileFactory extends Factory
{
    protected $model = FtpFile::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->slug();

        return [
            'name' => $name,
            'extension' => $this->faker->fileExtension(),
        ];
    }
}
