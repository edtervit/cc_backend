<?php

namespace Database\Factories;

use App\Models\Image;
use App\Models\ImageTopics;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Image>
 */
class ImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $ori = rand(0, 2);

        switch ($ori) {
            case 0:
                //landscape
                $width = 500;
                $height = 300;
                $orientation = 'landscape';
                break;
            case 1:
                //portrait
                $width = 300;
                $height = 500;
                $orientation = 'portrait';
                break;
            case 2:
                //squarish
                $width = 500;
                $height = 500;
                $orientation = 'squarish';
                break;
            default:
                break;
        }

        $colour = Image::ALL_IMAGE_COLOURS[rand(0, count(Image::ALL_IMAGE_COLOURS) - 1)];

        return [
            'url' => $this->faker->imageUrl($width, $height, $colour),
            'description' => $this->faker->sentence(),
            'orientation' => $orientation,
            'colour' => $colour,
            'artist_name' => $this->faker->name(),
            'artist_profile_url' => 'https://jointplaylist.com/',
            'unsplash_id' => $this->faker->uuid(),
        ];
    }
}
