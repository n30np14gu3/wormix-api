<?php

namespace App\Http\Controllers\Internal;

use App\Helpers\Wormix\WormixTrashHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Internal\Arena\EndBattleRequest;
use App\Http\Requests\Internal\Arena\GetArenaRequest;
use App\Http\Requests\Internal\Arena\StartBattleRequest;
use App\Http\Resources\Internal\Arena\ArenaLocked;
use App\Http\Resources\Internal\Arena\ArenaResult;
use App\Models\Wormix\Reagent;
use App\Models\Wormix\UserBattleInfo;
use App\Models\Wormix\UserProfile;
use App\Models\Wormix\WormData;
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
        else
            $battle_info->battle_type = 1;

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

    public function endBattle(EndBattleRequest $request)
    {
        $battle_info =
            UserBattleInfo::query()->
            where('user_id', $request->json('internal_user_id'))
                ->get()
                ->first();

        $result = $request->json('Result') - $battle_info->current_battle_id;
        if(abs($result) >= 2){
            Log::debug("Invalid battle id", [
                'request' => $request->json('BattleId'),
                'current' => $battle_info->current_battle_id,
            ]);
        }

        $this->processBattleResult($request, $result, $battle_info);
        return [
            'Status' => 'OK'
        ];
    }

    private function processBattleResult(EndBattleRequest $request, int $result, UserBattleInfo $battleInfo)
    {
        $wormData = WormData::query()->where('owner_id', $battleInfo->user_id)->get()->first();
        $user_info = UserProfile::query()->where('user_id', $battleInfo->user_id)->get()->first();

        if($battleInfo->battle_type !== 1)
            $wormData->experience += $request->json('ExpBonus');

        switch($result){
            case 0:
                //Draw (nothing)
                break;
            case 1: //Winner
                if($battleInfo->battle_type === 1){
                    Log::debug("TOTO MAKE BOT");
                    if($battleInfo->mission_id <= -1){
                        $battleInfo->mission_id -= 1;
                    }elseif($battleInfo->mission_id == -3){
                        $battleInfo->mission_id = 0;
                    }
                    else{
                        $battleInfo->mission_id += 1;
                    }
                    $battleInfo->save();
                }
                else{
                    switch ($request->json('Type')){
                        case 0:
                            $user_info->money += config('wormix.game.missions.awards.medium.money');
                            $wormData->experience += config('wormix.game.missions.awards.medium.experience');
                            break;
                        case 1:
                            $user_info->money += config('wormix.game.missions.awards.high.money');
                            $wormData->experience += config('wormix.game.missions.awards.high.experience');
                            break;
                        case 2:
                            $user_info->money += config('wormix.game.missions.awards.low.money');
                            $wormData->experience += config('wormix.game.missions.awards.low.experience');
                            break;
                    }
                }
                break;
            case -1:
                if($battleInfo->battle_type == 0){
                    $user_info->money += config('wormix.game.missions.awards.loose.money');
                    $wormData->experience += config('wormix.game.missions.awards.loose.experience');
                }
                break;
        }

        $user_info->save();
        $wormData->save();
        $battleInfo->current_battle_id = 0;
        $battleInfo->save();

        if($battleInfo->battle_type !== 1){
           $valid = true;
            foreach($battleInfo->awards['reagents'] as $reagent){
                if(!in_array($reagent, $request->json('CollectedReagents'))){
                    $valid = false;
                    break;
                }
            }

            if($valid)
                WormixTrashHelper::addReagents($user_info, $request->json('CollectedReagents'));
        }
    }
}

