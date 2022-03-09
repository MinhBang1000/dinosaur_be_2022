<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\DecisionPostController;
use App\Http\Controllers\Api\DietController;
use App\Http\Controllers\Api\DinosaurController;
use App\Http\Controllers\Api\MesozoicController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
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

// Api Router
// Read Api
Route::get('category', [CategoryController::class, 'index']);
Route::get('mesozoic', [MesozoicController::class, 'index']);
Route::get('country', [CountryController::class, 'index']);
Route::get('diet', [DietController::class, 'index']);
Route::get('dinosaur', [DinosaurController::class, 'index']); 
Route::get('dinosaur/{id}', [DinosaurController::class, 'show']);

// Create, Update, Delete Api what need token or authorization
Route::middleware('auth:sanctum')->group(function () {
    // Logout
    Route::post('logout/{id}', [LogoutController::class, 'logoutHandle']);
    
    // Dinosaur
    Route::post('dinosaur',[DinosaurController::class,'store'])->middleware('abilities:create-dinosaur');
    Route::put('dinosaur/{id}',[DinosaurController::class,'update'])->middleware('abilities:update-dinosaur');
    Route::delete('dinosaur/{id}',[DinosaurController::class,'delete'])->middleware('abilities:delete-dinosaur');

    // Category
    Route::put('category/{id}',[CategoryController::class,'update'])->middleware('abilities:update-category');
    Route::delete('category/{id}',[CategoryController::class,'delete'])->middleware('abilities:delete-category');
    
    // Diet
    Route::put('diet/{id}',[DietController::class,'update'])->middleware('abilities:update-diet');
    Route::delete('diet/{id}',[DietController::class,'delete'])->middleware('abilities:delete-diet');
    
    // Country
    Route::put('country/{id}',[CountryController::class,'update'])->middleware('abilities:update-country');
    Route::delete('country/{id}',[CountryController::class,'delete'])->middleware('abilities:delete-country');
    
    // Mesozoic
    Route::put('mesozoic/{id}',[MesozoicController::class,'update'])->middleware('abilities:update-mesozoic');
    Route::delete('mesozoic/{id}',[MesozoicController::class,'delete'])->middleware('abilities:delete-mesozoic');

    // Decision
    Route::post('decision-post/{id}',[DecisionPostController::class,'store'])->middleware('abilities:decision-post');
    Route::post('decision-dinosaur/{id}',[DecisionDinosaurController::class,'store'])->middleware('abilities:decision-dinosaur');
});

// Router fallback
Route::fallback(function(){
    return response()->json(['status' => false,'message' => 'This route is not found.'],404);
});

// Route::apiResource('dinosaur',DinosaurController::class);