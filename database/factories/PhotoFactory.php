<?php

namespace Database\Factories;

use App\Models\Album;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Photo>
 */
class PhotoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->text(60),
            'description' => $this->faker->text(128),
            'img_path' => $this->faker->imageUrl(),
            'created_at' => $this->faker->dateTime(),
            'album_id' => Album::factory()
        ];
    }
}
