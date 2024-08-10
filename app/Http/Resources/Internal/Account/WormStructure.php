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
        return [
            'OwnerId' => $this->owner_id,
            'SocialOwnerId' => (string)$this->owner_id,

            'Armor' => $this->armor,
            'Attack' => $this->attack,

            'Experience' => $this->experience,
            'Level' => $this->level,
            'Hat' => $this->hat
        ];
    }
}
