<?php

namespace App\Http\Controllers;

use App\Models\Messages;

class MessagesController extends Controller
{
    public function getPhotographyMessages()
    {
        return Messages::where('type', '=', Messages::MESSAGE_TYPE_PHOTOGRAPHY)->get();
    }
}
