<?php

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

Route::post('register',[App\Http\Controllers\UserController::class,'register']);
Route::post('login',[App\Http\Controllers\UserController::class,'login']);

Route::post('todo',[App\Http\Controllers\TodoController::class,'store']);
Route::get('todoList',[App\Http\Controllers\TodoController::class,'index']);
Route::patch('todoUpdate/{id}',[App\Http\Controllers\TodoController::class,'update']);
Route::delete('todoDelete/{id}',[App\Http\Controllers\TodoController::class,'destroy']);
Route::post('markCompleted',[App\Http\Controllers\TodoController::class,'markCompleted']);
Route::post('markNotCompleted',[App\Http\Controllers\TodoController::class,'markNotCompleted']);