<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\DietController;
use App\Http\Controllers\Api\DinosaurController;
use App\Http\Controllers\Api\MesozoicController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
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

// Router For Social Connection
Route::get('login/{provider}', [LoginController::class, 'redirectToProvider']);
Route::get('login/{provider}/callback', [LoginController::class, 'handleProviderCallback']);

// Login
Route::post('login',[LoginController::class,'loginManually']);

// Register
Route::post('register',[RegisterController::class,'registerManually']);

// Api Router
// Read Api
Route::get('category', [CategoryController::class, 'index']);
Route::get('mesozoic', [MesozoicController::class, 'index']);
Route::get('country', [CountryController::class, 'index']);
Route::get('diet', [DietController::class, 'index']);
Route::get('dinosaur', [DinosaurController::class, 'index']); 
Route::get('dinosaur/{id}', [DinosaurController::class, 'show']);
Route::get('dinosaur-search',[DinosaurController::class,'homeSearch']);
Route::get('post',[PostController::class,'index']);
Route::get('post/{id}',[PostController::class,'show']);
Route::get('post-search',[PostController::class,'postSearchSomeThing']);
Route::get('post-sorta',[PostController::class,'postSortA']);
Route::get('post-sortb',[PostController::class,'postSortB']);
Route::get('user/{id}',[UserController::class,'show']);
Route::get('insert',[DinosaurController::class,'insert']);

// Create, Update, Delete Api what need token or authorization
Route::middleware('auth:sanctum')->group(function () {
    // User
    Route::put('user/{id}',[UserController::class,'update']);

    // Logout
    Route::post('logout/{id}', [LogoutController::class, 'logoutHandle']);
    
    // Post
    Route::post('post',[PostController::class,'store'])->middleware('abilities:create-post');
    Route::post('post-image',[PostController::class,'postImageAjax'])->middleware('abilities:create-post'); // This route service for ajax upload by quill custom
    Route::get('post-like/{postID}/{userID}',[PostController::class,'postLike']);
    Route::get('post-unlike/{postID}/{userID}',[PostController::class,'postUnlike']);
    Route::put('post/{id}',[PostController::class,'update'])->middleware('abilities:update-post');
    Route::delete('post/{id}',[PostController::class,'destroy'])->middleware('abilities:delete-post');
    Route::get('post-decision/{id}',[PostController::class,'decision'])->middleware('abilities:decision-post');

    // Comment
    Route::post('comment',[CommentController::class,'store'])->middleware('abilities:create-comment');
    Route::delete('comment/{id}',[CommentController::class,'destroy'])->middleware('abilities:delete-comment');
    Route::get('comment-like/{commentID}/{userID}',[CommentController::class,'commentLike']);
    Route::get('comment-unlike/{commentID}/{userID}',[CommentController::class,'commentUnlike']);

    // Dinosaur
    Route::post('dinosaur',[DinosaurController::class,'store'])->middleware('abilities:create-dinosaur');
    Route::put('dinosaur/{id}',[DinosaurController::class,'update'])->middleware('abilities:update-dinosaur');
    Route::delete('dinosaur/{id}',[DinosaurController::class,'destroy'])->middleware('abilities:delete-dinosaur');
    Route::get('dinosaur-like/{dinosaurID}/{userID}',[DinosaurController::class,'dinosaurLike']);
    Route::get('dinosaur-unlike/{dinosaurID}/{userID}',[DinosaurController::class,'dinosaurUnlike']);
    Route::get('dinosaur-decision/{id}',[DinosaurController::class,'decision'])->middleware('abilities:decision-dinosaur');

    // Category
    Route::put('category/{id}',[CategoryController::class,'update'])->middleware('abilities:update-category');
    Route::delete('category/{id}',[CategoryController::class,'destroy'])->middleware('abilities:delete-category');
    
    // Diet
    Route::put('diet/{id}',[DietController::class,'update'])->middleware('abilities:update-diet');
    Route::delete('diet/{id}',[DietController::class,'destroy'])->middleware('abilities:delete-diet');
    
    // Country
    Route::put('country/{id}',[CountryController::class,'update'])->middleware('abilities:update-country');
    Route::delete('country/{id}',[CountryController::class,'destroy'])->middleware('abilities:delete-country');
    
    // Mesozoic
    Route::put('mesozoic/{id}',[MesozoicController::class,'update'])->middleware('abilities:update-mesozoic');
    Route::delete('mesozoic/{id}',[MesozoicController::class,'destroy'])->middleware('abilities:delete-mesozoic');
});
// References
Route::apiResource('dinosaur',DinosaurController::class);
// Router fallback
Route::fallback(function(){
    return response()->json(['status' => false,'message' => 'This route is not found.'],404);
});

// Route::apiResource('dinosaur',DinosaurController::class);