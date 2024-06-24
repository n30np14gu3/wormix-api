<?php

use App\Http\Controllers\Account\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('sign_in', [AuthController::class, 'signIn']);
Route::post('sign_up', [AuthController::class, 'signUp']);
Route::post('sign_out', [AuthController::class, 'signOut']);
