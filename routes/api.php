<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ancestryController;
use App\Http\Controllers\userController;


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





// public-------------------------------------------------------------
    Route::get('/index', [ancestryController::class, 'index']);
    Route::get('/show/{id}', [ancestryController::class, 'show']);
    Route::post('/register', [userController::class, 'register']);
    Route::post('/login', [userController::class, 'login']);
    Route::post('/search/{search}', [ancestryController::class, 'search']);

    Route::post('forgotpassword', [userController::class, 'forgotPassword']);
    Route::post('reset-password', [userController::class, 'reset']);
    // Route::post('password-reset', [userController::class, 'test']);
    


// protected-------------------------------------------------------------
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/delete/{id}', [ancestryController::class, 'destroy']);
    Route::post('/update/{id}', [ancestryController::class, 'update']);
    Route::post('/updateuser/{id}', [userController::class, 'update']);
    Route::post('/deleteuser/{id}', [userController::class, 'delete']);
    Route::get('/logout', [userController::class, 'logout']);
    Route::post('/create', [ancestryController::class, 'store']);
    Route::post('/storeimg', [ancestryController::class, 'storeimg']);
    Route::get('/adminlookup/{searchval}', [ancestryController::class, 'adminsearch']);

    // Route::post('/adminsearch/{search}', [ancestryController::class, 'adminsearch']);


});







