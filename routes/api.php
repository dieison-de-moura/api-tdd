<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::apiResource('books', \App\Http\Controllers\API\BooksController::class);

// Route::get('books', [\App\Http\Controllers\API\BooksController::class, 'index']);
// Route::post('books', [\App\Http\Controllers\API\BooksController::class, 'store']);
// Route::get('books/{id}', [\App\Http\Controllers\API\BooksController::class, 'show']);
// Route::match(['put', 'patch'], 'books/{id}', [\App\Http\Controllers\API\BooksController::class, 'update']);
// Route::delete('books/{id}', [\App\Http\Controllers\API\BooksController::class, 'destroy']);