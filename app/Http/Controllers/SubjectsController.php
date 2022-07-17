<?php

namespace App\Http\Controllers;

use App\Models\Subjects;
use Illuminate\Database\Eloquent\Collection;

class SubjectsController extends Controller
{
    //
    public function getSubjects(): Collection
    {
        $request = request();
        $request->validate([
            //limit response amount with random selection
            'limit' => 'integer',
        ]);

        $limit = $request->input('limit');

        $subjects = Subjects::query();

        if($limit){
            $subjects = $subjects->inRandomOrder()->take($limit);
        }


        return $subjects->get();
    }
}
