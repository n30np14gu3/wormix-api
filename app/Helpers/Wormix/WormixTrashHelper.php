<?php

namespace App\Helpers\Wormix;

use App\Models\Wormix\HouseAction;

class WormixTrashHelper
{
    private const RACE_BASE = 500;
    private const RACE_LIMIT = 50;

    public const STUFF_START_INDEX = 1000;

    /**
     * @param int $hat worm_structure.hat
     * @return int[] 0-race id, 1-hat id
     */
    public static function getRaceAndHatIds(int $hat) : array
    {
        $hatId = 0;
        if($hat < self::RACE_LIMIT){
            $raceId = $hat;
        }
        else{
            $hatId = self::STUFF_START_INDEX + $hat % self::RACE_BASE;
            $raceId = (int)(($hat - self::STUFF_START_INDEX) / self::RACE_BASE);
        }
        return  [
            $raceId,
            $hatId
        ];
    }

    /**
     * @param int $hatId
     * @param int $raceId
     * @return int worm_structure.hat
     */
    public static function getHatByRaceAndHatIds(int $hatId, int $raceId) : int
    {
        return $raceId * self::RACE_BASE + $hatId;
    }

    /**
     * @param int $user_id
     * @return int
     */
    public static function getSearchKeys(int $user_id) : int
    {
        return max(
            0,
            (config('wormix.game.search_keys_per_day') -
                HouseAction::query()
                    ->where('action_type', 1)
                    ->where('user_id', $user_id)
                    ->where('created_at', '>=', now()->subDay())
                    ->count()
            )
        );
    }

    /**
     * @param int $user_id
     * @param int $to_user_id
     * @return bool
     */
    public static function isSearchedToday(int $user_id, int $to_user_id)
    {
        return HouseAction::query()
                ->where('user_id', $user_id)
                ->where('to_user_id', $to_user_id)
                ->where('action_type', 1)
                ->where('created_at', '>=', now()->subDay())->count() > 0;
    }
}
