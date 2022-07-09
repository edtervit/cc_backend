<?php

use App\Http\Controllers\ColourSchemesController;
use App\Http\Controllers\MessagesController;
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
Route::get('color-schemes', [ColourSchemesController::class, 'getColours']);
//make protected route
Route::get('color-schemes/scrape', [ColourSchemesController::class, 'scrapeColourSchemes']);
