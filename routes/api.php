<?php

use App\Http\Controllers\ColourSchemesController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\SubjectsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//messages
Route::get('messages', [MessagesController::class, 'getMessages']);

//colours
Route::get('colour-schemes', [ColourSchemesController::class, 'getColours']);

//subjects
Route::get('subjects', [SubjectsController::class, 'getSubjects']);

//images
Route::get('images', [ImageController::class, 'getImages']);
Route::get('topics', [ImageController::class, 'getImageTopics']);


//keep disabled on prod until auth ready
// Route::get('color-schemes/scrape', [ColourSchemesController::class, 'scrapeColourSchemes']);
