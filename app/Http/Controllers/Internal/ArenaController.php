<?php

namespace App\Http\Controllers\Internal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Internal\Arena\GetArenaRequest;
use App\Http\Resources\Internal\Arena\ArenaResult;
use App\Models\Wormix\UserBattleInfo;
use Illuminate\Http\Request;

class ArenaController extends Controller
{
    public function getArena(GetArenaRequest $request)
    {
        $battle_info = UserBattleInfo::query()
            ->where('user_id', $request
                ->json('internal_user_id'))
            ->get()
            ->first();

        return [
            'data' => new ArenaResult($battle_info)
        ];
    }
}
