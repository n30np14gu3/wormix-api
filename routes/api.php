<?php

use App\Http\Controllers\VkApiController;
use Illuminate\Support\Facades\Route;

//Vk api
Route::post('', [VkApiController::class, 'handleRequest']);

//Internal api for local server
Route::prefix('internal')->group(base_path('routes/internal/internal_api.php'));
