<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BoardController;

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


Route::get('columns', [BoardController::class, 'columns']);
Route::post('columns', [BoardController::class, 'updateColumns']);
Route::delete('column/{column}', [BoardController::class, 'deleteColumn']);
Route::post('column', [BoardController::class, 'addColumn']);
Route::post('card', [BoardController::class, 'addCard']);
