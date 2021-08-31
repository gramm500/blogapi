<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\LikeController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\SampleController;
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

Route::group(
    [],
    function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
        Route::get('/posts/search', [PostController::class, 'search']);
        Route::get('/tags/search', [TagController::class, 'search']);
        Route::get('/posts', [PostController::class, 'index']);
        Route::get('/posts/{post}/likes', [LikeController::class, 'index']);
        Route::get('/posts/{post}', [PostController::class, 'show']);
        Route::get('/posts/{post}/comments', [CommentController::class, 'index']);
        Route::get('/tags', [TagController::class, 'index']);
        Route::get('/tags/{tag}', [TagController::class, 'show']);
    }
);

Route::group(
    ['middleware' => 'auth:sanctum'],
    function () {
        Route::post('/posts', [PostController::class, 'store']);
        Route::put('/posts/{post}', [PostController::class, 'update']);
        Route::delete('/posts/{post}', [PostController::class, 'destroy']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/user/{user}', [UserController::class, 'update']);
        Route::get('/user/{user}', [UserController::class, 'show']);
        Route::post('/posts/{post}/comments', [CommentController::class, 'store']);
        Route::post('/posts/{post}/likes', [LikeController::class, 'addLike']);
        Route::post('/tags', [TagController::class, 'store']);
        Route::put('/tags/{tag}', [TagController::class, 'update']);
        Route::delete('/tags/{tag}', [TagController::class, 'destroy']);
    }
);
