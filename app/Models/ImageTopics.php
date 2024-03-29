<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImageTopics extends Model
{
    use HasFactory;

    public function images()
    {
        return $this->belongsToMany(Image::class, 'image_image_topic', 'image_topic_id', 'image_id');
    }
}
