<?php

namespace App\Http\Controllers\Internal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Internal\Arena\GetArenaRequest;
use App\Http\Resources\Internal\Arena\ArenaLocked;
use App\Http\Resources\Internal\Arena\ArenaResult;
use App\Models\Wormix\UserBattleInfo;
use Illuminate\Http\Request;

class ArenaController extends Controller
{
    public function getArena(GetArenaRequest $request)
    {
        $battle_info = UserBattleInfo::query()
            ->where('user_id', $request->json('internal_user_id'))
            ->get()
            ->first();

        //Add battles (one battle per x minutes)
        if($battle_info->battles_count < config('wormix.game.missions.max')){
            $battle_info->battles_count += max((int)(
                (time() - $battle_info->last_battle_time) / config('wormix.game.missions.delay')),
                config('wormix.game.missions.max')
            );
            $battle_info->battles_count = min(
                $battle_info->battles_count,
                config('wormix.game.missions.max')
            );
            $battle_info->save();
        }

        if($battle_info->battles_count === 0){
            return [
                'type' => 'ArenaLocked',
                'data' => new ArenaLocked($battle_info)
            ];
        }
        return [
            'type' => 'ArenaResult',
            'data' => new ArenaResult($battle_info)
        ];
    }

    public function startBattle()
    {

    }
}

