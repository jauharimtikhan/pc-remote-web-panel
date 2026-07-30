<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserDeviceController;
use Illuminate\Support\Facades\Route;


Route::controller(AuthController::class)
    ->group(function () {

        Route::get('/', 'login')
            ->name('login');
        Route::get('/register', 'register')
            ->name('register')
        ;
        Route::post('/register/post', 'register_post')
            ->name('register.post');


        Route::post('/login/post', 'login_post')
            ->name('login.post');
    })
    ->middleware('guest');



Route::middleware('auth')
    ->name('admin.')
    ->prefix('admin')
    ->group(function () {
        Route::get('/auth/logout', [AuthController::class, 'logout'])->name('auth.logout');
        Route::get('/', [HomeController::class, 'index'])->name('home.index');



        Route::resource('pengguna', UserController::class)
            ->parameter('pengguna', 'user')
            ->names('pengguna');

        Route::resource('devices', DeviceController::class);
    });
Route::get('/my-devices', [UserDeviceController::class, 'index'])
    ->middleware('auth')
    ->name('user.devices');
Route::put('/my-devices/{id}/config', [UserDeviceController::class, 'updateConfig'])
    ->middleware('auth')
    ->name('user.devices.config.update');
