<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Image extends Model
{
    use HasFactory;

    protected $fillable = ['url', 'description', 'orientation', 'artist_name', 'artist_profile_url', 'unsplash_id', 'colour'];

    const ALL_IMAGE_COLOURS =
    [
        'black_and_white', 'black', 'white', 'yellow', 'orange', 'red', 'purple', 'magenta', 'green', 'teal', 'blue'
    ];

    const ALL_IMAGE_ORIENTATIONS =
    [
        'landscape', 'portrait', 'squarish'
    ];

    public function imageTopics()
    {
        return $this->belongsToMany(ImageTopics::class, 'image_image_topic', 'image_id','image_topic_id');
    }

    public static function orientationChecker($width, $height, $percent)
    {
        //percent value = 10, will check if width is + or minus 10% of the height to determine if its squarish

        if ($width === $height) return 'squarish';

        //squarish check
        $minHeight = $height - ($height / $percent);
        $maxHeight = $height + ($height / $percent);
        if (($minHeight <= $width) && ($width <= $maxHeight)) return 'squarish';

        $minWidth = $width - ($width / $percent);
        $maxWidth = $width + ($width / $percent);
        if (($minWidth <= $height) && ($height <= $maxWidth)) return 'squarish';


        return $width > $height ? 'landscape' : 'portrait';
    }

    public static function insertArryOfImagesFromUnsplashApi($arrayOfPhotos, $colour = null)
    {
        foreach ($arrayOfPhotos as $image) {
            //check if image already exists, check if colour null incase we have image but without colour assignment
            $exists = Image::where('unsplash_id', '=', $image['id'])->whereNotNull('colour')->first();

            if ($exists) continue;

            $existsWithNoColour = Image::where('unsplash_id', '=', $image['id'])->whereNull('colour')->first();

            if($existsWithNoColour){
                $existsWithNoColour->colour = $colour;
                $existsWithNoColour->save();
                continue;
            }

            $createdImage = Image::firstOrCreate([
                'url' => $image['urls']['regular'],
                'description' => $image['alt_description'] ?? $image['description'] ?? null,
                'orientation' => Image::orientationChecker($image['width'], $image['height'], 10),
                'artist_name' => $image['user']['name'],
                'artist_profile_url' => $image['user']['links']['html'],
                'unsplash_id' => $image['id'],
                'colour' => $colour,
            ]);

            //assign the topic
            if (count($image['topic_submissions']) > 0) {
                $topicSlugs = array_keys($image['topic_submissions']);
                foreach ($topicSlugs as $slug) {
                    //check if we have that slug in db
                    $topicWeHave = ImageTopics::where('slug', '=', $slug)->first();
                    if ($topicWeHave) {
                        DB::table('image_image_topic')->insert([
                            'image_id' => $createdImage->id,
                            'image_topic_id' => $topicWeHave->id,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);
                    }
                }
            }
        }
    }

}
