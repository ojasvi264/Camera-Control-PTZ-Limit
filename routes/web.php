<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ControlSystemController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\CameraSettingController;
use App\Http\Controllers\PtzLimitController;

Route::get('/', function () {
    return view('admin.index');
});

Route::get('/', [DashboardController::class, 'index'])->name('admin.index');
Route::get('control-system', [ControlSystemController::class, 'index'])->name('admin.control_system');

Route::get('camera-setting', [CameraSettingController::class, 'index'])->name('admin.ptz_setting');
Route::get('camera-setting/list', [CameraSettingController::class, 'list'])->name('admin.ptz_setting.list');
Route::post('ptz/store', [CameraSettingController::class, 'store'])->name('admin.store_ptz');
Route::post('ptz-control/update', [CameraSettingController::class, 'updatePTZInfo']);
Route::get('camera/info', [CameraSettingController::class, 'cameraInfo']);
Route::get('zoom-value', [PtzLimitController::class, 'getZoomValues']);



Route::get('limit/PTZ', [PtzLimitController::class, 'limitPTZ']);


Route::get('api/camera/info', [CameraSettingController::class, 'getCameraInfo']);

Route::post('save-photo', [PhotoController::class, 'store']);
