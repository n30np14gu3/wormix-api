<?php

namespace App\Http\Controllers\Internal;

use App\Helpers\Wormix\WormixTrashHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Internal\Account\SelectStuffRequest;
use App\Http\Resources\Internal\Account\SelectStuffResult;
use App\Models\Wormix\UserWeapon;
use App\Models\Wormix\WormData;
use Illuminate\Database\Eloquent\Collection;

class InternalAccountController extends Controller
{
    public function selectStuff(SelectStuffRequest $request)
    {
        $user_weapon = UserWeapon::query()
            ->where('owner_id', $request->json('internal_user_id'))
            ->where('weapon_id', $request->json('StuffId'))
            ->get()
            ->first();

        if($user_weapon === null)
            return [
                'data' => new SelectStuffResult(
                    Collection::empty(),
                    SelectStuffResult::Error,
                    0)
            ];

        $worm = WormData::query()
            ->where('owner_id', $request->json('internal_user_id'))
            ->get()
            ->first();

        $raceAndHat = WormixTrashHelper::getRaceAndHatIds($worm->hat);
        $worm->hat = WormixTrashHelper::getHatByRaceAndHatIds($user_weapon->weapon_id, $raceAndHat[0]);
        $worm->save();

        return [
            'data' => new SelectStuffResult(
                Collection::empty(),
                SelectStuffResult::Success,
                $request->json('StuffId')
            )
        ];

    }
}
