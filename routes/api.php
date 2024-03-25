<?php

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CommentLikeController;
use App\Http\Controllers\PostLikeController;

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


// Protected routes

Route::group(['middleware' => ['auth:sanctum']], function () {
    //create a post
    Route::post('/posts', [PostController::class, 'store']);
    //Update a post
    Route::patch('/posts/{post:slug}', [PostController::class, 'update']);
    //Delete a post
    Route::delete('/posts/{post:slug}', [PostController::class, 'destroy']);
    //Logout a user
    Route::post('/logout', [SessionController::class, 'destroy']);
    //Update a user
    Route::patch('/users/{user:username}', [UserController::class, 'update']);
    //Delete a user
    Route::delete('/users/{user:username}', [UserController::class, 'destroy']);
    //create a comment
    Route::post('/comments', [CommentController::class, 'store']);
    //delete a comment 
    Route::delete('/comments/{id}', [CommentController::class, 'destroy']);

    //Storing a new post like
    Route::post('/likes/posts', [PostLikeController::class, 'store']);
    //Storing a new comment like
    Route::post('/likes/comments', [CommentLikeController::class, 'store']);
    //Destroying a post like
    Route::delete('/likes/posts/{id}', [PostLikeController::class, 'destroy']);
    //Destroying a comment like
    Route::delete('/likes/comments/{id}', [CommentLikeController::class, 'destroy']);
});

// Public routes

//get all users
Route::get('/users', [UserController::class, 'index']);
//get a single user by his username
Route::get('/users/{user:username}', [UserController::class, 'show']);
//get all posts
Route::get('/posts', [PostController::class, 'index']);
// get a single post by it slug
Route::get('/posts/{post:slug}', [PostController::class, 'show']);
//get all categories
Route::get('/categories', [CategoryController::class, 'index']);
//store a new user on database
Route::post('/register', [UserController::class, 'store']);
//start a new user session
Route::post('/login', [SessionController::class, 'store']);
//get all comments by a post
Route::get('/comments', [CommentController::class, 'index']);
//get all comment likes
Route::get('likes/comments/{id}',[CommentLikeController::class, 'index']);
//get all post likes
Route::get('likes/posts/{id}',[PostLikeController::class, 'index']);