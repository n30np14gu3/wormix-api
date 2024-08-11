<?php

namespace App\Http\Controllers\Internal;

use App\Helpers\Wormix\WormixTrashHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Internal\Shop\BuyShopItemsRequest;
use App\Http\Requests\Internal\Shop\ChangeRaceRequest;
use App\Http\Resources\Internal\Shop\ChangeRaceResult;
use App\Http\Resources\Internal\Shop\ShopResult;
use App\Models\Wormix\Race;
use App\Models\Wormix\UserProfile;
use App\Models\Wormix\UserWeapon;
use App\Models\Wormix\Weapon;
use App\Models\Wormix\WormData;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class ShopController extends Controller
{
    public function buyItems(BuyShopItemsRequest $request)
    {
        try{
            $shopItems = [];
            foreach($request->json('ShopItems') as $item){
                $shopItems["{$item['Id']}"] = [
                    'Count' => $item['Count'],
                    'MoneyType' => $item['MoneyType'],
                ];
            }
            $sum = 0;
            $realSum = 0;

            foreach(Weapon::query()->whereIn('id', array_keys($shopItems))->get() as $weapon){
                if($weapon->hide_in_shop && $weapon->ref_id === null)
                    throw new \Exception("Attempt to buy hidden item!");
                if($shopItems["{$weapon->id}"]['MoneyType'] === 0)
                    $realSum += $weapon->infinity ? $weapon->real_price : ($weapon->real_price * $shopItems["{$weapon->id}"]['Count']);
                if($shopItems["{$weapon->id}"]['MoneyType'] === 1)
                    $sum += $weapon->infinity ? $weapon->price : ($weapon->price * $shopItems["{$weapon->id}"]['Count']);

                //Mb add validation to friends, rating, etc
            }

            $user_profile = UserProfile::query()->where('user_id', $request->json('internal_user_id'))->get()->first();

            if($user_profile->money < $sum || $user_profile->real_money < $realSum)
                return new ShopResult(Collection::empty(), ShopResult::NotEnoughMoney);

            Log::debug("Spending", [
                'user_id' => $request->json('internal_user_id'),
                'money' => $sum,
                'real_money' => $realSum
            ]);

            $user_profile->money -= $sum;
            $user_profile->real_money -= $realSum;
            $user_profile->save();

            $new_weapons = Collection::empty();
            foreach($request->json('ShopItems') as $item){
                $old_weapon = UserWeapon::query()->
                where('owner_id', $request->json('internal_user_id'))->
                where('weapon_id', $item['Id'])->get()->first();

                if($item['Count'] == -1 || $old_weapon === null){
                    $user_weapon = new UserWeapon();
                    $user_weapon->owner_id = $request->json('internal_user_id');
                    $user_weapon->weapon_id = $item['Id'];
                    $user_weapon->count = $item['Count'];
                    $user_weapon->save();
                    $new_weapons->add($user_weapon);
                }
                else{
                    $old_weapon->count += $item['Count'];
                    $old_weapon->save();

                    $old_weapon->count = $item['Count'];
                    $new_weapons->add($old_weapon);
                }
            }
            return new ShopResult($new_weapons, ShopResult::Success);
        }
        catch (\Exception $ex){
            Log::error("Internal exception", [
                'exception' => $ex,
            ]);
            return new ShopResult(Collection::empty(), ShopResult::Error);
        }
    }

    public function changeRace(ChangeRaceRequest $request)
    {
        $user_worm = WormData::query()->where('owner_id', $request->json('internal_user_id'))->get()->first();
        $user_profile = UserProfile::query()->where('user_id', $request->json('internal_user_id'))->get()->first();
        $race = Race::query()->where('race_id', $request->json('RaceId'))->get()->first();

        $raceAndHats = WormixTrashHelper::getRaceAndHatIds($user_worm->hat);
        if($raceAndHats[0] === $request->json('RaceId'))
            return new ChangeRaceResult(Collection::empty(), ChangeRaceResult::Error);

        if(
            ($request->json('MoneyType') === 1 && $user_worm->level < $race->required_level) ||
            ($request->json('MoneyType') === 0 && $user_profile->real_money < $race->real_price) ||
            ($request->json('MoneyType') === 1 && $user_profile->money < $race->price)
        )
            return new ChangeRaceResult(Collection::empty(), ChangeRaceResult::MinRequirementsError);


        $user_worm->hat = WormixTrashHelper::getHatByRaceAndHatIds($request->json('RaceId'), $raceAndHats[1]);
        $user_worm->save();

        if($request->json('MoneyType') === 0)
            $user_profile->real_money -= $race->real_price;
        else
            $user_profile->money -= $race->price;

        $user_profile->save();

        return new ChangeRaceResult(Collection::empty(), ChangeRaceResult::Success);
    }
}
