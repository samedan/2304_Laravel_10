<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// /api/login
Route::post('/login', [UserController::class, 'loginApi']);
// /api/create-post
Route::post('/create-post', [PostController::class, 'storeNewPostApi'])->middleware('auth:sanctum');
// /api/delete-post/xx
Route::delete('/delete-post/{post}', [PostController::class, 'deleteApi'])->middleware('auth:sanctum', 'can:delete,post');
