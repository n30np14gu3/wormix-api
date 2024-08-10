<?php

namespace App\Helpers\Wormix;

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
        $raceId = 0;
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
}
