<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ColourSchemes extends Model
{
    protected $fillable = ['colour_1', 'colour_2', 'colour_3', 'colour_4', 'colour_5'];

    use HasFactory;
}
