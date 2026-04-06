<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DorctorController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('clear-cache', function(){
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('optimize');
    return 'Cache cleared';
});

Route::get('/', [HomeController::class, 'index']);

Route::get('doctors/{region}/{branch}', [DorctorController::class, 'allDoctors'])->name('branch.doctors');
Route::get('get-session-by-doctor', [DorctorController::class, 'getSessionByDoctor']);
Route::get('get-slots-by-session', [DorctorController::class, 'getslotsBySession']);
