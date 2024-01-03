<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecordController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('getSessionTimeOut', function () {
    return response(['session_time_out' => config('session.lifetime', 120) * 60 * 1000]);
})->middleware(['auth'])->name('timeout');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/', function () { return Inertia::render('Dashboard'); })->name('dashboard');
    Route::resource('/clients', ClientController::class);
    Route::resource('/tasks', TaskController::class);
    Route::resource('/records', RecordController::class);
});

Route::prefix('api')->group(function(){
    Route::get('/zeroRequest', function () {
        return response('zero', 200);
    })->name('zero');  
    Route::get('/getSessionTimeOut', function () {
        return response(['session_time_out' => config('session.lifetime', 120) * 60 * 1000]);
    })->name('timeout');  
    Route::post('/get_free_time_ranges', [RecordController::class, 'getFreeTimeRanges'])->name('get_free_time_ranges');
    Route::post('/get_free_rooms', [RecordController::class, 'getFreeRooms'])->name('get_free_rooms');
})->middleware('auth');

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('/users', UserController::class);
});

require __DIR__.'/auth.php';
