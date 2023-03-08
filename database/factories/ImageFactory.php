<?php

namespace Database\Factories;

use App\Models\Image;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;

/**
 * @extends Factory<Image>
 */
class ImageFactory extends Factory
{
    public function configure():static
    {
        return $this->afterCreating(function (Image $image) {
//            $image = UploadedFile::fake()->image('image.jpg');
//            $image->storeAs('public/images', $image->name .'.png');
        });
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'file' => $this->faker->image('public/storage/images', 640, 480, null, false),
            'enable' => $this->faker->boolean,
        ];
    }
}
