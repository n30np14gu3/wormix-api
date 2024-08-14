<?php

namespace App\Http\Controllers\Internal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Internal\Arena\GetArenaRequest;
use App\Http\Requests\Internal\Arena\StartBattleRequest;
use App\Http\Resources\Internal\Arena\ArenaLocked;
use App\Http\Resources\Internal\Arena\ArenaResult;
use App\Models\Wormix\Reagent;
use App\Models\Wormix\UserBattleInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ArenaController extends Controller
{
    private const BATTLE_BASE = 1000;

    public function getArena(GetArenaRequest $request)
    {
        $battle_info = UserBattleInfo::query()
            ->where('user_id', $request->json('internal_user_id'))
            ->get()
            ->first();

        //Add battles (one battle per x minutes)
        if($battle_info->battles_count < config('wormix.game.missions.max')){
            $battle_info->battles_count += min(
                (int)((time() - $battle_info->last_battle_time) / config('wormix.game.missions.delay')),
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

    public function startBattle(StartBattleRequest $request)
    {
        $battle_info = UserBattleInfo::query()
            ->where('user_id', $request->json('internal_user_id'))
            ->get()
            ->first();

        $battle_info->last_battle_time = time();
        if($request->json('MissionId') >= 0)
            $battle_info->battles_count -= 1;

        $battle_info->current_battle_id = self::BATTLE_BASE + $battle_info->user_id + $request->json('MissionId');

        //Random reagents generation
        $reagents = [];
        srand(time());

        if($request->json('MissionId') === 0)
            $reagents = Reagent::query()->select('reagent_id')->pluck('reagent_id')->random(rand(0, 10))->toArray();

        $awards = [];


        $battle_info->awards = [
            'reagents' => $reagents,
            'awards'   => $awards
        ];
        $battle_info->save();

        return [
            'data' => [
                'Awards' => $awards,
                'BattleId' => $battle_info->current_battle_id,
                'ReagentForBattle' => $reagents
            ]
        ];
    }
}

