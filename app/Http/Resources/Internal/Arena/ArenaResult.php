<?php

namespace App\Http\Resources\Internal\Arena;

use App\Helpers\Wormix\WormixBotHelper;
use App\Models\Wormix\WormData;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArenaResult extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'UserProfileStructures' => WormixBotHelper::GenerateBots(
                WormData::query()
                    ->where('owner_id', $this->user_id)
                    ->get()
                    ->first()
            ),
            'BattlesCount' => $this->battles_count,
            'CurrentMission' => $this->last_mission_id,
            'BossAvailable' => time() - $this->last_boss_fight_time > 24 * 60 * 60,
        ];
    }
}
