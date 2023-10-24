<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;

Route::group(['prefix' => 'v1/auth'], function () {
    Route::post('refresh', 'AuthController@refresh')->name('auth_refresh');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});
