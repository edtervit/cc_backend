<?php

namespace Database\Seeders;

use App\Models\Image;
use App\Models\ImageTopics;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //seed images
        Image::factory()->count(100)->create();

        //add a random topic to each image
        $images = Image::all();
        foreach ($images as $image) {
            $imageId = $image->id;
            $topicId = ImageTopics::inRandomOrder()->take(1)->value('id');
            DB::table('image_image_topic')->insert([
                'image_id' => $imageId,
                'image_topic_id' => $topicId,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
