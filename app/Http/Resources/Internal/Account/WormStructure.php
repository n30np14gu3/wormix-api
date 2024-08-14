<?php

namespace App\Http\Resources\Internal\Account;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WormStructure extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $armor = $this->teammate->armor;
        $attack = $this->teammate->attack;
        $level = $this->teammate->level;

        if($this->user_id !== $this->teammate_id && $this->owner->level !== $this->teammate->level){
            $newPoints = $this->stripData($level, $this->owner->level, $armor, $attack);
            $level = $this->owner->level;
            $armor = $newPoints['armor'];
            $attack = $newPoints['attack'];
        }
        return [
            'OwnerId' => $this->teammate_id,
            'SocialOwnerId' => (string)$this->teammate_id,

            'Armor' => $armor,
            'Attack' => $attack,

            'Experience' => $this->teammate->experience,
            'Level' => $level,
            'Hat' => $this->teammate->hat
        ];
    }

    private function stripData(
        int $level,
        int $toLevel,
        int $armor,
        int $attack
    )
    {
        $levelPoints = $level * 2;
        $toLevelPoints = $toLevel * 2;

        if($armor + $attack < $levelPoints)
            $armor += $levelPoints - ($armor + $attack);


        $armorPoints = (int)(($armor / $levelPoints) * $toLevelPoints);
        $attackPoints = $toLevelPoints - $armorPoints;

        return [
            'armor' => $armorPoints,
            'attack' => $attackPoints
        ];
    }
}
