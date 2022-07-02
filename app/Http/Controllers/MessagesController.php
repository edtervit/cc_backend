<?php

namespace App\Http\Controllers;

use App\Models\Messages;
use Illuminate\Database\Eloquent\Collection;

class MessagesController extends Controller
{
    //get messages, returns general type only as default
    public function getMessages(): Collection
    {

        $request = request();
        $request->validate([
            'type' => ['string', 'in:"' . implode('", "', Messages::ALL_MESSAGE_TYPES) . '"'],
            'is_oblique' => 'boolean',
            //limit response amount with random selection
            'limit' => 'integer',
        ]);

        $type = $request->input('type');
        $is_oblique = $request->input('is_oblique');
        $limit = $request->input('limit');

        $messages = Messages::query();

        if($type){
            $messages = $messages->where('type', '=', $type);
        }

        if(filled($is_oblique)){
            $messages = $messages->where('is_oblique', '=', $is_oblique);
        }

        if($limit){
            $messages = $messages->inRandomOrder()->take($limit);
        }

        return $messages->get();
    }
}
