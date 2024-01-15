<?php

use App\Http\Controllers\ClassesController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecordController;
use App\Http\Controllers\TimeRangeController;
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

Route::middleware('auth')->group(function () {
    Route::get('/', function () { return Inertia::render('Dashboard'); })->name('dashboard');
    Route::resource('/clients', ClientController::class);
    Route::resource('/tasks', TaskController::class);
    Route::resource('/records', RecordController::class);
    Route::post('/records/index', [RecordController::class, 'index'])->name('record.filter');
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
    Route::post('/get_client_info', [RecordController::class, 'getClientInfo'])->name('get_client_info');
    Route::post('/set_record_present', [RecordController::class, 'setPresent'])->name('set_record_present');

    Route::post('/get_records', [RecordController::class, 'get_records'])->name('get_records');
    Route::post('/set_is_present', [RecordController::class, 'set_is_present'])->name('set_is_present');
    Route::post('/delete_record', [RecordController::class, 'delete_record'])->name('delete_record');
})->middleware('auth');

Route::name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('/users', UserController::class);
    Route::resource('/rooms', RoomController::class);
    Route::resource('/classes', ClassesController::class);
    Route::resource('/time-ranges', TimeRangeController::class);
});

require __DIR__.'/auth.php';
