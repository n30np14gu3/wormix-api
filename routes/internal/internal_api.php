<?php

use App\Http\Controllers\Internal\InternalLoginController;
use Illuminate\Support\Facades\Route;

Route::post('login', [InternalLoginController::class, 'login']);
