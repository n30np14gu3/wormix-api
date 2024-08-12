<?php

use App\Http\Controllers\Internal\ArenaController;
use App\Http\Controllers\Internal\InternalAccountController;
use App\Http\Controllers\Internal\InternalLoginController;
use App\Http\Controllers\Internal\ShopController;
use Illuminate\Support\Facades\Route;

Route::post('login', [InternalLoginController::class, 'login']);

Route::middleware(['internal-auth'])->group(function () {
    Route::prefix('account')->group(function () {
        Route::post('buy_items', [ShopController::class, 'buyItems']);
        Route::post('change_race', [ShopController::class, 'changeRace']);
        Route::post('select_stuff', [InternalAccountController::class, 'selectStuff']);
    });
    Route::prefix('game')->group(function (){
        Route::post('get_arena', [ArenaController::class, 'getArena']);
        Route::post('start_battle', [ArenaController::class, 'startBattle']);
    });
});
