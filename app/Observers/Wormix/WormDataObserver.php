<?php

namespace App\Observers\Wormix;

use App\Models\Wormix\UserWeapon;
use App\Models\Wormix\Weapon;
use App\Models\Wormix\WormData;

class WormDataObserver
{
    /**
     * Handle the WormData "created" event.
     */
    public function created(WormData $wormData): void
    {

    }

    /**
     * Handle the WormData "updated" event.
     */
    public function updated(WormData $wormData): void
    {
        if($wormData->experience < $wormData->level_model->required_experience)
            return;

        //Save new level
        WormData::withoutEvents(function () use ($wormData) {
            $wormData->level += 1;
            $wormData->experience = $wormData->experience - $wormData->level_model->required_experience;
            $wormData->save();
            $wormData->refresh();
        });



        //Add awards weapons
        $awards = $wormData->level_model->awards;
        $awards_weapons_ids = array_map(function ($x) { return $x[0];}, $awards);
        $weapons = Weapon::query()
            ->whereIn('id', $awards_weapons_ids)
            ->whereNotIn('id',
                UserWeapon::query()
                    ->where('owner_id', $wormData->owner_id)
                    ->whereIn('weapon_id', $awards_weapons_ids)
                    ->where('count', '!=', '-1')
                    ->select('weapon_id')
                    ->pluck('weapon_id')
                    ->toArray()
            )->get();

        //Bad coding
        $weapons_ids = $weapons->pluck('id')->toArray();
        $new_weapons = array_values(array_filter($awards, function($x) use ($weapons_ids)  {return !in_array($x[0], $weapons_ids);}));

        foreach($new_weapons as $weapon){
            $old_weapon = UserWeapon::query()->where('weapon_id', $weapon[0])->get()->first();
            $user_weapon = $old_weapon === null ? new UserWeapon() : $old_weapon;
            $user_weapon->owner_id = $wormData->owner_id;
            $user_weapon->weapon_id = $weapon[0];
            $user_weapon->count = $weapon[1];
            $user_weapon->save();
        }

        //Add money
        $user_profile = $wormData->owner->user_profile;
        $user_profile->money += config('wormix.game.next_level_award.money');
        $user_profile->save();

    }

    /**
     * Handle the WormData "deleted" event.
     */
    public function deleted(WormData $wormData): void
    {
        //
    }

    /**
     * Handle the WormData "restored" event.
     */
    public function restored(WormData $wormData): void
    {
        //
    }

    /**
     * Handle the WormData "force deleted" event.
     */
    public function forceDeleted(WormData $wormData): void
    {
        //
    }
}
