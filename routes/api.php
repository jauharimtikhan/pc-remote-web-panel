<?php

use App\Http\Controllers\AndroidAppController;
use App\Http\Controllers\UserDeviceController;
use Illuminate\Support\Facades\Route;

Route::post('validate_token', [UserDeviceController::class, 'api_validated_token']);
Route::post('sync_settings', [UserDeviceController::class, 'api_sync_settings']);
Route::post('heartbeat', [UserDeviceController::class, 'api_heartbeat']);

Route::controller(AndroidAppController::class)
    ->prefix('android')
    ->group(function () {
        Route::post('/check-update', 'check_update');
        Route::get('/update-failed', 'update_failed')->name('api.android.fallback-version');
        Route::post('/upload-bundle', 'bundleAssetUploaded');
    });
