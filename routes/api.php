<?php

use App\Http\Controllers\UserDeviceController;
use Illuminate\Support\Facades\Route;

Route::post('validate_token', [UserDeviceController::class, 'api_validated_token']);
Route::post('sync_settings', [UserDeviceController::class, 'api_sync_settings']);
Route::post('heartbeat', [UserDeviceController::class, 'api_heartbeat']);
