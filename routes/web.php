<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// USER routes
Route::get('/', [UserController::class, "showCorrectHomepage"])->name('login'); // rename the page "name"

Route::post('/register', [UserController::class, "register"]);
Route::post('/login', [UserController::class, "login"]);
Route::post('/logout', [UserController::class, "logout"]);

// BLOG Routes
Route::get("/create-post", [PostController::class, "showCreateForm"])->middleware('auth');
Route::post("/create-post", [PostController::class, "storeNewPost"]);

Route::get("/post/{post}", [PostController::class, "viewSinglePost"]);
