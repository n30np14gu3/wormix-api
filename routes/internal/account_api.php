<?php

use App\Http\Controllers\Internal\Account\LoginController;
use Illuminate\Support\Facades\Route;

Route::post('login', [LoginController::class, 'login'])->name('internal.account.login');
