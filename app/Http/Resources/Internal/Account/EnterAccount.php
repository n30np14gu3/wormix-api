<?php

namespace App\Http\Resources\Internal\Account;

use App\Models\Wormix\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EnterAccount extends JsonResource
{
    private string $session_key;

    public function __construct($resource, $session_key)
    {
        $this->session_key = $session_key;
        return parent::__construct($resource);
    }
    public function toArray(Request $request): array
    {
        // EnterAccount result = new EnterAccount
        // {
        //     UserProfileStructure = new UserProfileStructure
        //     {
        //         Id = 1,
        //         Money = 450,
        //         Rating = 0,
        //         ReactionRate = 0,
        //         RealMoney = 3,
        //         SocialId = "1",
        //         WormsGroup = new List<WormStructure>
        //         {
        //             new WormStructure
        //             {
        //                 OwnerId = 1,
        //                 SocialOwnerId = "1",
        //                 Armor = 1,
        //                 Attack = 1,
        //                 Experience = 0,
        //                 Level = 2,
        //                 Hat = 0,
        //             }
        //         },
        //         WeaponRecordList = new()
        //         {
        //             new() { Id = 1, Count = -1 },
        //             new() { Id = 2, Count = -1 },
        //             new() { Id = 4, Count = -1 },
        //         },
        //         Stuff = new()
        //     },
        //     AvailableSearchKeys = 0,
        //     Friends = 0,
        //     OnlineFriends = 0,
        //     IsBonusDay = false,
        //     DailyBonusStructure = new DailyBonusStructure
        //     {
        //         DailyBonusType = 0,
        //         DailyBonusCount = 0,
        //         LoginSequence = 0
        //     },
        //     SessionKey = "session_key",
        // };
        //
        // return result;

        return [
            'UserProfileStructure' => new  UserProfileStructure($this->user_profile),

            'UserProfileStructures' => UserProfileStructure::collection(UserProfile::query()->where('user_id', '!=', $this->id)->get()),

            'AvailableSearchKeys' => 0,
            'Friends' => 0,
            'OnlineFriends' => 0,
            'IsBonusDay' => false,

            'DailyBonusStructure' => new DailyBonusStructure($this->login_sequence),

            'Reagents' => [],

            'SessionKey' => $this->session_key
        ];
    }
}
