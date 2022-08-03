<?php

namespace Database\Seeders;

use App\Models\ImageTopics;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ImageTopicsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $topics = [
            [
                "slug" => "3d-renders",
                "name" => "3D Renders",
                "unsplash_id" => "CDwuwXJAbEw"
            ],
            [
                "slug" => "textures-patterns",
                "name" => "Textures & Patterns",
                "unsplash_id" => "iUIsnVtjB0Y"
            ],
            [
                "slug" => "experimental",
                "name" => "Experimental",
                "unsplash_id" => "qPYsDzvJOYc"
            ],
            [
                "slug" => "architecture",
                "name" => "Architecture",
                "unsplash_id" => "rnSKDHwwYUk"
            ],
            [
                "slug" => "nature",
                "name" => "Nature",
                "unsplash_id" => "6sMVjTLSkeQ"
            ],
            [
                "slug" => "fashion",
                "name" => "Fashion",
                "unsplash_id" => "S4MKLAsBB74"
            ],
            [
                "slug" => "food-drink",
                "name" => "Food & Drink",
                "unsplash_id" => "xjPR4hlkBGA"
            ],
            [
                "slug" => "people",
                "name" => "People",
                "unsplash_id" => "towJZFskpGg"
            ],
            [
                "slug" => "animals",
                "name" => "Animals",
                "unsplash_id" => "Jpg6Kidl-Hk"
            ],
            [
                "slug" => "arts-culture",
                "name" => "Arts & Culture",
                "unsplash_id" => "bDo48cUhwnY"
            ],
        ];

        foreach ($topics as $topic) {
            ImageTopics::firstOrCreate($topic);
        }
    }
}
