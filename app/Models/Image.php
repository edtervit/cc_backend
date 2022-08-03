<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $fillable = ['url', 'description', 'orientation', 'artist_name', 'artist_profile_url', 'unsplash_id',];

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
        return $this->belongsToMany(ImageTopics::class);
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
}
