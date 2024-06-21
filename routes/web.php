<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

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

// Route::get('/{any}', [HomeController::class, 'index'])->where('any', '^(?!api).*$');

Route::get('/', [HomeController::class, 'index']);
// Route::get('/', [HomeController::class, 'kkk']);



Route::get('/api/aaa', [HomeController::class, 'kkk']);

Route::post('/api/aaa', [HomeController::class, 'kkk'])->name('mytest');




