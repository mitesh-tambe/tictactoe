<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicTacToeController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [TicTacToeController::class, 'index']);
Route::post('/play', [TicTacToeController::class, 'play']);
Route::post('/restart', [TicTacToeController::class, 'restart']);