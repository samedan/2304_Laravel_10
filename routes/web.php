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

// ADMIN routes
Route::get('/admins-only', function() {
    return "only visible to admins";
})->middleware('can:visitAdminPages');

// USER routes
Route::get('/', [UserController::class, "showCorrectHomepage"])->name('login'); // rename the page "name"

Route::post('/register', [UserController::class, "register"])->middleware('guest');
Route::post('/login', [UserController::class, "login"])->middleware('guest');
Route::post('/logout', [UserController::class, "logout"])->middleware('mustBeLoggedIn');

// BLOG Routes
Route::get("/create-post", [PostController::class, "showCreateForm"])->middleware('mustBeLoggedIn');
Route::post("/create-post", [PostController::class, "storeNewPost"])->middleware('mustBeLoggedIn');

Route::get("/post/{post}", [PostController::class, "viewSinglePost"]);
Route::delete("/post/{post}", [PostController::class, "delete"])->middleware('can:delete,post');
Route::get('/post/{post}/edit', [PostController::class, 'showEditForm'])->middleware('can:update,post');
Route::put('/post/{post}', [PostController::class, 'actuallyUpdate'])->middleware('can:update,post');

// PROFILE routes
Route::get('/profile/{user:username}', // look for the user using 'username', not teh default 'id'
     [UserController::class, 'profile']);

