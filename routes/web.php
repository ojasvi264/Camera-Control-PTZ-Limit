<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ControlSystemController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PhotoController;

Route::get('/', function () {
    return view('admin.index');
});

Route::get('/', [DashboardController::class, 'index'])->name('admin.index');
Route::get('/control-system', [ControlSystemController::class, 'index'])->name('admin.control_system');
Route::post('/save-photo', [PhotoController::class, 'store']);
