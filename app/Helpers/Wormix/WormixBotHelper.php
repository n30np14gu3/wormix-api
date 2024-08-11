<?php

namespace App\Helpers\Wormix;

use App\Http\Resources\Internal\Account\WeaponRecordList;
use App\Models\Wormix\Race;
use App\Models\Wormix\UserWeapon;
use App\Models\Wormix\Weapon;
use App\Models\Wormix\WormData;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class WormixBotHelper
{
    public const BOT_BASE = -1000;

    public static function GenerateBotInfo()
    {
        return Collection::empty();
    }

    public static function GenerateBots(WormData $userWorm)
    {
        srand(time());
        $user_profile = $userWorm->owner->user_profile;

        //$min_level = min($userWorm->level - 1, 1);
        //$max_level = min($userWorm->level + 1, 30);



        $random_stuff = Weapon::query()
            ->where('id', '>', WormixTrashHelper::STUFF_START_INDEX)
            ->where('required_level', '<=', $userWorm->level)
            ->where('required_rating', '<=', $user_profile->rating)
            ->get()
            ->where('hide_in_shop', 0)
            ->pluck('id')
            ->toArray();

        Log::debug("STUFF", $random_stuff);

        $random_race = Race::query()
            ->where('required_level', '<=', $userWorm->level)
            ->get()->pluck('race_id')->toArray();

        Log::debug("RACE", $random_race);

        $bots = Collection::empty();
        for($i = 0; $i < 4; $i++){
            $random_armor = rand(0, $userWorm->level * 2);
            $random_attack = $userWorm->level * 2 - $random_armor;
            $hat = count($random_stuff) === 0 ? 0 : $random_stuff[array_rand($random_stuff)];
            $race = count($random_race) === 0 ? 0 : $random_race[array_rand($random_race)];

            $profile = [
                'Id' => $i +1,
                'Rating' => rand(0, $user_profile->rating),
                'Money' => rand(0, $user_profile->money),
                'RealMoney' => rand(0, $user_profile->real_money),
                'Recipes' => [],
                'SocialId' => (string)(0 - $user_profile->user_id + self::BOT_BASE + $i),
                'Stuff' => $hat == 0 ? [] : [$hat],
                'WeaponRecordList' => [
                    ['Id' => 1, 'Count' => -1]
                ],
                'WormsGroup' => [
                    [
                        'Armor' => $random_armor,
                        'Attack' => $random_attack,
                        'Experience' => 1,
                        'Level' => $userWorm->level,
                        'Hat' => WormixTrashHelper::getHatByRaceAndHatIds($hat, $race),
                        'OwnerId' => $i + 1,
                        'SocialOwnerId' => (string)(0 - $user_profile->user_id + self::BOT_BASE + $i)
                    ]
                ]
            ];
            $bots->add($profile);
        }
        return $bots;
    }
}
