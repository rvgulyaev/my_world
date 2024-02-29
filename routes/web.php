<?php

use App\Http\Controllers\ClassesController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ReportController;
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

    Route::get('/clients/trashed', [ClientController::class, 'trashed'])->name('clients.trashed');
    Route::post('/clients/terminate', [ClientController::class, 'terminate'])->name('clients.terminate');
    Route::post('/clients/restore', [ClientController::class, 'restore'])->name('clients.restore');
    Route::get('export-clients', [ClientController::class, 'exportExcel'])->name('clients.export');
    Route::resource('/clients', ClientController::class);

    Route::get('/tasks/trashed', [TaskController::class, 'trashed'])->name('tasks.trashed');
    Route::post('/tasks/terminate', [TaskController::class, 'terminate'])->name('tasks.terminate');
    Route::post('/tasks/restore', [TaskController::class, 'restore'])->name('tasks.restore');

    Route::resource('/tasks', TaskController::class);
    Route::resource('/records', RecordController::class);
    Route::post('/records/index', [RecordController::class, 'index'])->name('record.filter');
});

Route::name('reports.')->prefix('/reports')->middleware(['auth'])->group(function () {
    Route::get('/clients/index', [ReportController::class, 'clients_index'])->name('clients.index');
    Route::get('/specialists/index', [ReportController::class, 'specialists_index'])->name('specialist.index');
});

Route::prefix('api')->group(function(){
    Route::get('/zeroRequest', function () {
        return response('zero', 200);
    })->name('zero');  
    Route::get('/getSessionTimeOut', function () {
        return response(['session_time_out' => config('session.lifetime', 120) * 60 * 1000]);
    })->name('timeout');  
    Route::post('/get_busy_time', [RecordController::class, 'getBusyTime'])->name('get_busy_time');
    Route::post('/get_free_rooms', [RecordController::class, 'getFreeRooms'])->name('get_free_rooms');
    Route::post('/get_client_info', [RecordController::class, 'getClientInfo'])->name('get_client_info');
    Route::post('/set_record_present', [RecordController::class, 'setPresent'])->name('set_record_present');
    Route::post('/store_record_comment', [RecordController::class, 'store_record_comment'])->name('store_record_comment');

    Route::post('/get_records', [RecordController::class, 'get_records'])->name('get_records');
    Route::post('/set_is_present', [RecordController::class, 'set_is_present'])->name('set_is_present');
    Route::post('/delete_record', [RecordController::class, 'delete_record'])->name('delete_record');

    Route::post('/get_clients_report', [ReportController::class, 'get_clients_report'])->name('get_clients_report');
    Route::get('/get_clients_list', [ReportController::class, 'get_clients'])->name('get_clients_list');

    Route::post('/get_specialists_report', [ReportController::class, 'get_specialists_report'])->name('get_specialists_report');
    Route::get('/get_specialists_list', [ReportController::class, 'get_specialists'])->name('get_specialists_list');
})->middleware('auth');

Route::name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/users/trashed', [UserController::class, 'trashed'])->name('users.trashed');
    Route::post('/users/terminate', [UserController::class, 'terminate'])->name('users.terminate');
    Route::post('/users/restore', [UserController::class, 'restore'])->name('users.restore');
    Route::post('/users/ban', [UserController::class, 'ban'])->name('users.ban');
    Route::post('/users/unban', [UserController::class, 'unBan'])->name('users.unban');
    Route::resource('/users', UserController::class);

    Route::get('/rooms/trashed', [RoomController::class, 'trashed'])->name('rooms.trashed');
    Route::post('/rooms/terminate', [RoomController::class, 'terminate'])->name('rooms.terminate');
    Route::post('/rooms/restore', [RoomController::class, 'restore'])->name('rooms.restore');
    Route::resource('/rooms', RoomController::class);

    Route::get('/classes/trashed', [ClassesController::class, 'trashed'])->name('classes.trashed');
    Route::post('/classes/terminate', [ClassesController::class, 'terminate'])->name('classes.terminate');
    Route::post('/classes/restore', [ClassesController::class, 'restore'])->name('classes.restore');
    Route::resource('/classes', ClassesController::class);

    Route::get('/time-ranges/trashed', [TimeRangeController::class, 'trashed'])->name('time-ranges.trashed');
    Route::post('/time-ranges/terminate', [TimeRangeController::class, 'terminate'])->name('time-ranges.terminate');
    Route::post('/time-ranges/restore', [TimeRangeController::class, 'restore'])->name('time-ranges.restore');
    Route::resource('/time-ranges', TimeRangeController::class);
});

require __DIR__.'/auth.php';
