<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Messages extends Model
{
    protected $fillable = ['message', 'is_oblique', 'type'];

    use HasFactory;
    const MESSAGE_TYPE_GENERAL = 'general';
    const MESSAGE_TYPE_PHOTOGRAPHY = 'photography';
    const MESSAGE_TYPE_MUSIC = 'music';

    const ALL_MESSAGE_TYPES = [
        Messages::MESSAGE_TYPE_GENERAL,
        Messages::MESSAGE_TYPE_PHOTOGRAPHY,
        Messages::MESSAGE_TYPE_MUSIC,
    ];
}
