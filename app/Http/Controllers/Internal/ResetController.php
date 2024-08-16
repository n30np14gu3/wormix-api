<?php

namespace App\Http\Controllers\Internal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Internal\Account\ResetParametersRequest;
use App\Http\Resources\Internal\Account\ResetParametersResult;
use App\Models\Wormix\UserProfile;
use App\Models\Wormix\WormData;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class ResetController extends Controller
{
    public function resetAccount()
    {

    }

    public function confirmReset()
    {

    }

    public function resetParameters(ResetParametersRequest $request)
    {
        $user_profile = UserProfile::query()
            ->where('user_id', $request->json('internal_user_id'))
            ->get()
            ->first();
        $wormData = WormData::query()
            ->where('owner_id', $request->json('internal_user_id'))
            ->get()
            ->first();

        if(
            ($request->json('MoneyType') === 1 && $user_profile->money < config('wormix.game.buy.reset_stats.money')) ||
            $request->json('MoneyType') === 0 && $user_profile->real_money < config('wormix.game.buy.reset_stats.real_money')
        )
            return [
                'data' => new ResetParametersResult(Collection::empty(), ResetParametersResult::NotEnoughMoney)
            ];

        if($request->json('MoneyType') === 1)
            $user_profile->money -= config('wormix.game.buy.reset_stats.money');
        else
            $user_profile->real_money -= config('wormix.game.buy.reset_stats.real_money');

        $wormData->armor = 0;
        $wormData->attack = 0;
        $wormData->save();

        $user_profile->save();

        return [
            'data' => new ResetParametersResult(Collection::empty(), ResetParametersResult::Success)
        ];

    }
}
