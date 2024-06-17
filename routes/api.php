<?php

use Illuminate\Support\Facades\Route;


//Internal api for local server
Route::prefix('internal')->group(base_path('routes/internal/internal_api.php'));
