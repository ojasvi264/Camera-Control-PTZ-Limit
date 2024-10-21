<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ControlSystemController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\CameraSettingController;

Route::get('/', function () {
    return view('admin.index');
});

Route::get('/', [DashboardController::class, 'index'])->name('admin.index');
Route::get('/control-system', [ControlSystemController::class, 'index'])->name('admin.control_system');
Route::get('/camera-setting', [CameraSettingController::class, 'index'])->name('admin.ptz_setting');
Route::post('ptz/store', [CameraSettingController::class, 'store'])->name('admin.store_ptz');
Route::post('/save-photo', [PhotoController::class, 'store']);
Route::get('camera/info', [CameraSettingController::class, 'getCameraInfo']);
